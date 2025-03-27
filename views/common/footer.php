<!-- The Popup Modal -->
<div id="logout_popup" class="logout_popup">
        <div class="popup_content">
            <h2 class="d-flex justify-content-between align-items-center">Ready to Leave? <span class="cross_btn"><i
                        class="fa-solid fa-xmark"></i></span></h2>
            <p>Are you sure you want to logout?</p>
            <div class="pop_btn_sec px-3 py-4 text-end">
                <a href="javascript:void(0);" class="cancel_btn text-dark">Cancel</a>
                <a href="logout.php" class="popup_btn text-white ms-3">Logout</a>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/custom_js.js?v=1"></script>
    <script src="public/js/calender.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>
    <?php
        if (isset($_SESSION['message'])) {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: "{$_SESSION['message']}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>";
            unset($_SESSION['message']); // Clear session after showing message
        }

        if (isset($_SESSION['error'])) {
            echo "<script>
                Swal.fire({
                    title: "Error!",
                    text: "{$_SESSION['error']}",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            </script>";
            unset($_SESSION['error']); // Clear session after showing message
        }
    ?>
    <script>
        //======================= For Request Page Date Picker
        $(function () {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function (start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#edit_profile').click(function () {
                $('#change_pass_btn,#profile_btn_sec').show();
            });

            $('#change_pass_cancel').click(function () {
                $('#change_pass_btn').hide();
            });
            $('#profile_cancel').click(function () {
                $('#profile_btn_sec').hide();
            });
        });
    </script>
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            //----------Loader for all Menu items click------//
            let nav = document.querySelectorAll(".navigation ul li a"); 
            nav.forEach(function (elm) { 

                elm.addEventListener("click", function (event) {
                    //let link = elm.querySelector("a");
                    document.getElementById("loaderOverlay").style.display = "block"; // Show loader
                    elm.disabled = true ;
                });
            });

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