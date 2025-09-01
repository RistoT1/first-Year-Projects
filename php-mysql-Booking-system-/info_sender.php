<?php
session_start();
header('Content-Type: application/json');
date_default_timezone_set('Europe/Helsinki');


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'errors' => ['Invalid CSRF token']
        ]);
        exit;
    }
    require_once "db.php";

    $errors = [];
    $name = trim($_POST["nimi"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $id = trim($_POST['slot_id'] ?? '');

    if ($name === '') {
        $errors[] = "Nimi on pakollinen.";
    }
    if ($email === '') {
        $errors[] = "Sähköposti on pakollinen.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Sähköpostiosoite ei ole kelvollinen.";
    }
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        $errors[] = "Id ei täsmää";
    } else {
        // Check if the booking exists in DB
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ?");
        $stmt->execute([$id]);
        $booking = $stmt->fetch();

        if (!$booking) {
            $errors[] = "Booking slot not found.";
        }
        if ($booking['user_id'] != 0) {
            $errors[] = 'Booking aldready booked';
        }
        $bookingDateTimeStr = $booking['booking_date'] . ' ' . $booking['start_time'];
        $bookingDateTime = strtotime($bookingDateTimeStr);
        $now = time();

        if ($bookingDateTime <  $now + 30 * 60) {
            $errors[] = "Et voi varata menneisyyteen."; // You cannot book in the past.
        }
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            // Email already exists – use that user
            $_SESSION['user_id'] = $existingUser['id'];
        } else {
            // Insert new user
            $statement = $pdo->prepare("INSERT INTO users (nimi, email) VALUES (:nimi, :email)");
            $statement->execute([
                ':nimi' => $name,
                ':email' => $email
            ]);

            // Get inserted user ID
            $_SESSION['user_id'] = $pdo->lastInsertId();
        }

        $bookingCountStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
        $bookingCountStmt->execute([$_SESSION['user_id']]);
        $bookingCount = $bookingCountStmt->fetchColumn();

        if ($bookingCount >= 3) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'errors' => ["Et voi varata useampaa kuin 3 aikaa."]
            ]);
            exit;
        }


        //Update bookings table and change the bookked times user_id to users id.
        $updatebooking = $pdo->prepare('UPDATE bookings SET user_id = ? Where booking_id = ?');
        $updatebooking->execute([$_SESSION["user_id"], $id]);

        $start = substr($booking['start_time'], 0, 5); // gets "HH:MM"
        $end = substr($booking['end_time'], 0, 5);

        $bookedtime = sprintf("%s–%s %s", $start, $end, $booking['description']);


        $_SESSION['bookedtime'] = $bookedtime;

        echo json_encode([
            'success' => true,
            'redirect' => 'thank_you.php?'
        ]);
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'errors' => ["Tietokantavirhe: " . $e->getMessage()]
        ]);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'errors' => ["Väärä pyyntö."]
    ]);
    exit;
}

?>