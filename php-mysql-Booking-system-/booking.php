<?php

// Set content type to JSON
header('Content-Type: application/json');

// Enable CORS if needed (uncomment if frontend and backend are on different domains)
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type');

date_default_timezone_set('Europe/Helsinki');

require 'db.php';


if (isset($_GET["day"]) && isset($_GET["month"]) && isset($_GET["year"])) {

    /*$Kuukaudet = array(
        "",
        "tammikuu",   // January
        "helmikuu",   // February
        "maaliskuu",  // March
        "huhtikuu",   // April
        "toukokuu",   // May
        "kesäkuu",    // June
        "heinakuu",   // July
        "elokuu",     // August
        "syyskuu",    // September
        "lokakuu",    // October
        "marraskuu",  // November
        "joulukuu"    // December
    ); */

    $min_year = date("Y");
    $max_year = date("Y") + 1;
    $year = trim($_GET["year"]);
    $month = trim($_GET["month"]);
    $day = trim($_GET["day"]);


    if (!checkdate($month, $day, $year)) {
        echo json_encode(["success" => false, "message" => "Invalid date"]);
        exit;
    }

    if (!ctype_digit($year) || $year < $min_year || $year > $max_year) {
        echo json_encode(["success" => false, "message" => "Bookings only available between $min_year and $max_year"]);
        exit;
    }

    if (!ctype_digit($day)) {
        echo json_encode(["success" => false, "message" => "Invalid day parameter"]);
        exit;
    }

    $date_str = sprintf('%04d-%02d-%02d', $year, $month, $day);

    try {
        // Prepare and execute the query to get bookings for the date
        $stmt = $pdo->prepare("SELECT booking_id, user_id , booking_date , start_time, end_time, description FROM bookings WHERE booking_date = ? ORDER BY start_time");
        $stmt->execute([$date_str]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($bookings) {
            $times = [];
            $now = time();
            foreach ($bookings as $booking) {
                $bookingDateTimeStr = $booking['booking_date'] . ' ' . $booking['start_time'];
                $bookingDateTime = strtotime($bookingDateTimeStr);

                if ($booking["user_id"] == 0 && $bookingDateTime > $now + 30 * 60) {
                    $start = substr($booking['start_time'], 0, 5);
                    $end = substr($booking['end_time'], 0, 5);

                    $times[] = [
                        "id" => $booking['booking_id'],
                        "start" => $start,
                        "end" => $end,
                        "description" => $booking['description']
                    ];
                }
            }

            echo json_encode([
                "success" => true,
                "times" => $times,
                "day" => $day,
                "month" => $month,
                "year" => $year
            ]);
        } else {
            echo json_encode([
                "success" => false,
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Database query failed: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "No day specified"
    ]);
}
?>