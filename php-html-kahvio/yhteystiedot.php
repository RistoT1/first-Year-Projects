<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kahvio - Sähköposti lähetetty</title>
    <!-- Only include one version of Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Proper order: jQuery first, then Bootstrap JS (only include once) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<style>
    html {
        scroll-behavior: smooth;
    }

    body {
        overflow-x: hidden;
        /* Apply the background image to the entire body */
        background-image:
            linear-gradient(135deg, rgba(149, 200, 247, 0.171), rgba(247, 154, 193, 0.445)),
            url("https://images.pexels.com/photos/6692150/pexels-photo-6692150.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        /* This keeps the background fixed while scrolling */
        /* Add padding to the top to prevent content from being hidden under fixed navbar */
        padding-top: 70px;
    }

    .navbar {
        background: linear-gradient(90deg, rgba(253, 133, 54, 0.575), rgba(231, 209, 175, 0.582)) !important;
        /* Make the navbar fixed */
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        /* Make sure navbar stays on top of other content */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        /* Add subtle shadow for better visibility */
    }

    .img-container {
        min-height: 100vh;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .navbar-collapse {
        display: flex;
        align-items: center;
    }

    .navbar-nav {
        font-size: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-left: auto;
        flex: 1;
    }

    @media (max-width: 992px) {
        .navbar-nav {
            margin-left: 0;
        }
    }

    .confirmation-container {
        padding: 40px;
        width: 100%;
        max-width: 700px;
        border-radius: 20px;
        background-color: rgba(255, 255, 255, 0.85);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .confirmation-container h1 {
        color: rgb(243, 144, 15);
        margin-bottom: 30px;
        font-weight: bold;
        text-align: center;
    }

    .confirmation-icon {
        font-size: 60px;
        color: rgb(243, 144, 15);
        margin-bottom: 25px;
    }

    .confirmation-message {
        font-size: 1.2rem;
        color: #444;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .confirmation-details {
        background: linear-gradient(135deg, rgba(253, 133, 54, 0.1), rgba(231, 209, 175, 0.2));
        padding: 20px;
        border-radius: 10px;
        margin: 20px 0;
        text-align: left;
    }

    .confirmation-details p {
        margin-bottom: 10px;
        color: #555;
    }

    .confirmation-details strong {
        color: #333;
    }

    .back-button {
        display: inline-block;
        background: linear-gradient(90deg, rgba(253, 133, 54, 0.8), rgba(231, 209, 175, 0.8));
        color: white;
        padding: 12px 30px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
        margin-top: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: white;
    }

    .nav-link:hover {
        background-color: rgba(216, 134, 11, 0.281) !important;
        z-index: 1;
        opacity: 0.8;
        padding: 8px;
        transition: opacity 0.3s ease;
        border-radius: 4px;
    }

    .title-container {
        text-align: center;
        padding: 20px;
        margin: 20px auto 10px;
        max-width: 500px;
        border-radius: 15px;
        background: linear-gradient(135deg, rgba(253, 133, 54, 0.2), rgba(231, 209, 175, 0.3));
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .main-title {
        font-family: 'Georgia', serif;
        color: rgb(243, 144, 15);
        font-size: 2.8rem;
        font-weight: bold;
        margin-bottom: 5px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .subtitle {
        font-family: 'Arial', sans-serif;
        color: #333;
        font-size: 1.5rem;
        font-style: italic;
        position: relative;
        display: inline-block;
    }

    .subtitle::before,
    .subtitle::after {
        content: "";
        height: 2px;
        width: 30px;
        background: linear-gradient(90deg, transparent, rgb(243, 144, 15), transparent);
        position: absolute;
        top: 50%;
    }

    .subtitle::before {
        right: 105%;
    }

    .subtitle::after {
        left: 105%;
    }

    .footer-note {
        text-align: center;
        font-style: italic;
        color: #666;
        margin-top: 20px;
        font-size: 0.9rem;
    }

    .social-icons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 25px;
    }

    .social-icon {
        width: 40px;
        height: 40px;
        background-color: rgb(243, 144, 15);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 20px;
        transition: all 0.3s ease;
    }

    .social-icon:hover {
        transform: scale(1.1);
        background-color: rgba(243, 144, 15, 0.8);
    }

    @media (max-width: 768px) {
        .confirmation-container {
            padding: 25px 15px;
        }

        .main-title {
            font-size: 2.2rem;
        }

        .subtitle {
            font-size: 1.2rem;
        }

        .confirmation-icon {
            font-size: 50px;
        }

        .confirmation-message {
            font-size: 1.1rem;
        }
    }
</style>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nimi = $_POST["nimi"] ?? "";
        $email = $_POST["email"] ?? "";
        $viesti = $_POST["viesti"] ?? "";
    } else {
        $nimi = $email = $viesti = "";
    }
    ?>
?>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <div class="navbar-brand logo-left" style="font-weight: bold; font-size: 25px; padding-right: 100px; margin: 0; float: left;">Kahvio</div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ml-auto">
                    <a class="nav-link" href="index.html">Koti</a>
                    <a class="nav-link" href="index.html#hinnasto#tietoja">Tietoja</a>
                    <a class="nav-link" href="index.html#hinnasto">Hinnasto</a>
                    <a class="nav-link active" href="yhteystiedot.html">Yhteystiedot <span class="sr-only">(current)</span></a>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <div class="container-fluid p-0">
            <div class="title-container">
                <div class="main-title">Kiitos!</div>
                <div class="subtitle">Olemme vastaanottaneet viestisi</div>
            </div>

            <div class="row no-gutters">
                <div class="col-12 img-container">
                    <div class="confirmation-container">
                        <div class="confirmation-icon">✉️</div>
                        <h1>Viestisi on vastaanotettu</h1>
                        <div class="confirmation-message">
                            <p>Kiitos yhteydenotostasi! Olemme vastaanottaneet viestisi ja palaamme asiaan
                                mahdollisimman pian.</p>
                            <p>Pyrimme vastaamaan kaikkiin viesteihin 24 tunnin kuluessa.</p>
                        </div>

                        <div class="confirmation-details">
                            <p><strong>Nimi:</strong> <?php echo htmlspecialchars($nimi); ?></p>
                            <p><strong>Sähköposti:</strong> <?php echo htmlspecialchars($email); ?></p>
                            <p><strong>Viesti:</strong> <?php echo htmlspecialchars($viesti); ?></p>
                        </div>

                        <a href="index.html" class="back-button">Palaa etusivulle</a>

                        <div class="social-icons">
                            <a href="https://www.linkedin.com/in/risto-toivanen/" class="social-icon">in</a>
                            <a href="https://www.instagram.com/risto_toivanenn/?hl=fi" class="social-icon">ig</a>
                        </div>

                        <div class="footer-note">
                            <p>Seuraa meitä sosiaalisessa mediassa saadaksesi tietoa tulevista tapahtumista ja
                                tarjouksista!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<script>
    $(document).ready(function () {
        // Close the mobile navbar when clicking any nav link
        $('.navbar-nav .nav-link').on('click', function () {
            if ($('#navbarNavAltMarkup').hasClass('show')) {
                $('#navbarNavAltMarkup').collapse('hide');
            }
        });

        // Close the navbar when scrolling down
        $(window).on('scroll', function () {
            if ($('#navbarNavAltMarkup').hasClass('show')) {
                $('#navbarNavAltMarkup').collapse('hide');
            }
        });
    });
</script>

</html>