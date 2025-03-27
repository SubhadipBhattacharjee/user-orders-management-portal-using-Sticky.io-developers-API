<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/controllers/Auth.php' ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $auth = new Auth();
    $regisEmail = trim($_POST["regis_email"]);

    $resp = $auth->forgetPassword($regisEmail);
    $respData = json_decode($resp,true);

    if(isset($respData) && $respData['member_notification_sent'] == 1){

        $_SESSION['temp_password'] = $respData['data']['temp_password'];
        $_SESSION['message'] = "Check your email & reset your password..";

        header("location:resetPass.php");
        exit();
    }else{
        $_SESSION['error'] = $respData["response_message"];
    }   
}

?>

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
            <div class="d-flex justify-content-center align-items-center">
                <div class="logo_sec">
                    <img src="public/images/logo.png" alt="">
                </div>
            </div>
        </div>
    </header>

    <!-- Full-Screen Loader -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="loader" id="loaderMessage"></div>
    </div>
    <!-- Main Sec -->
    <section class="main_sec">
        <div class="container">
            <div class="cmn_box py-4 px-4 py-md-5">
                <div class="login_form forgot_password_form mx-auto my-4 my-md-5" id="forgot_div" style="display: block;">
                    <?php
                        if(isset($msg)){
                            echo $msg ;
                        }
                    ?>
                    <h2 class="text-center text-dark">Reset Your Password</h2>
                    <p class="text-dark mt-4 mb-0">Enter your email and we'll send you a temporary password </p>
                    <form action="forgetpass.php" method="POST">
                        <div class="each_input">
                            <label for="email" class="d-block">Email</label>
                            <input type="email" name="regis_email" required>
                        </div>
                        <div class="each_input">
                            <input type="submit" class="login_btn text-white" value="Submit">
                            <a href="index.php"
                                class="back_sign_btn w-100 d-block text-center text-dark mt-2">Back to Sign
                                In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Scripts -->
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/custom_js.js"></script>
    <?php
        if (isset($_SESSION['message'])) {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: '{$_SESSION['message']}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>";
            unset($_SESSION['message']); // Clear session after showing message
        }

        if (isset($_SESSION['error'])) {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: '{$_SESSION['error']}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
            unset($_SESSION['error']); // Clear session after showing message
        }
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            //----------Loader for all form submit------//
            let forms = document.querySelectorAll("form"); 
            forms.forEach(function (form) { // Loop through each form
                form.addEventListener("submit", function (event) {

                    document.getElementById("loaderOverlay").style.display = "block"; // Show loader
                    let submitButton = form.querySelector("[type='submit']"); // Select the submit button inside the form
                    if (submitButton) {
                        submitButton.disabled = true; // Disable only the button in the current form
                    }
                });
            });

        });
    </script>

</body>

</html>