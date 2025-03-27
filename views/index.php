<?php 
require_once __DIR__ . '/controllers/Auth.php' ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $auth = new Auth();
    
    $email = trim($_POST["email"]);
    $pass = trim($_POST["password"]);

    $data = $auth->login($email,$pass);     
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
        <div class="loader"></div>
    </div>
    <!-- Main Sec -->
    <section class="main_sec">
        <div class="container">
            <div class="cmn_box py-4 px-4 py-md-5">
                <div class="login_form mx-auto my-4 my-md-5" id="login_div">
                    <?php
                        if (isset($data)) {
                            echo '<div class="alert alert-danger">';
                            foreach ($data as $error) {
                                echo "<p>$error</p>";
                            }
                            echo '</div>';
                            //unset($_SESSION["errors"]); // Clear errors after displaying
                        }
                    ?>
                    <h2 class="text-center text-dark">Members Sign In</h2>
                    <form action="index.php" method="POST">
                        <div class="each_input">
                            <label for="email" class="d-block">Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="each_input position-relative">
                            <label for="password" class="d-block">Password</label>
                            <input type="password" name="password" class="password_field">
                            <i class="fa-solid fa-eye position-absolute eye_btn" id="eye_btn" toggle=".password_field"></i>
                        </div>
                        <div class="each_input mt-3 d-flex justify-content-between align-items-center">
                            <p class="forgot_link"><a href="forgetpass.php"
                                    class="text-decoration-underline">Forgot/Generate Password</a></p>
                            <p class="forgot_link"><a href="resetPass.php"
                                    class="text-decoration-underline">Reset Password</a></p>
                        </div>
                        <div class="each_input">
                            <input type="submit" class="login_btn text-white" value="Sign In">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Scripts -->
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/custom_js.js"></script>
</body>

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

</html>