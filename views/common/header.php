<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="public/images/favicon.png" type="image/png">
    <title>Highline Wellness</title>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <!-- Font Awosome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <!-- css -->
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/index_style.css?v=<?=time()?>" rel="stylesheet">
    <link href="public/css/calender.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Full-screen loader */
        .loader-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            z-index: 9999;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Top Header -->
    <header class="top_header py-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo_sec">
                    <img src="public/images/logo.png" alt="">
                </div>
                <div class="user_sec position-relative">
                    <i class="fa-solid fa-circle-user text-white"></i>
                    <ul class="mt-3">
                        <li><a href="account.php"><i class="fa-solid fa-user"></i> Profile</a></li>
                        <li id="log_out"><a href="#"><i class="fa-solid fa-right-from-bracket"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Full-Screen Loader -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="loader" id="loaderMessage"></div>
    </div>
    <!-- Main Header -->
    <section class="main_header bg-white">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="logo_sec">
                    <h4>Greetings, <?php echo $_SESSION['fname']; ?>!</h4>
                </div>
                <nav class="navigation">
                    <?php $uri = $_SERVER['REQUEST_URI']; ?>
                    <ul>
                        <!-- <li class="<?php echo (strpos($uri, "shipment.php") !== false) ? "active" : "" ?>" >
                            <a href="shipment.php">Next Shipment</a>
                        </li> -->
                        <li class="<?php echo (strpos($uri, "subscriptions.php") !== false) ? "active" : "" ?>" >
                            <a href="subscriptions.php">Orders & Subscriptions</a>
                        </li>
                        <li class="<?php echo (strpos($uri, "orders.php") !== false) ? "active" : "" ?>" >
                            <a href="orders.php">Order History</a>
                        </li>
                        <!-- <li><a href="request.html">Requests</a></li> -->
                        <li class="<?php echo (strpos($uri, "account.php") !== false) ? "active" : "" ?>" >
                            <a href="account.php">My Account</a>
                        </li>
                    </ul>
                </nav>
                <div class="hamburger" id="for-nav">
                    <div class="line1"></div>
                    <div class="line2"></div>
                    <div class="line3"></div>
                </div>
            </div>
        </div>
    </section>