<?php 
require_once __DIR__.'/controllers/Account.php'; 
require_once __DIR__.'/controllers/Member.php';
require_once __DIR__.'/controllers/Auth.php';


    $auth = new Auth();

    if(!$auth->isLoggedIn()){
        header("Location: index.php");
        exit();
    }else{

        $account = new Account();
        $email = $_SESSION['user']['email'];

        //----------Update User's Profile----------//
        if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['profile_update'] == 'profile_update'){

            $member = new Member();
            $profile_resp = $member->update_member_profile();
            $profile_data = json_decode($profile_resp,true);

            if(isset($profile_data) && $profile_data['status'] =='SUCCESS'){
                $_SESSION['message'] = "Details updated successfully...";
            }else{
                $_SESSION['error'] = $profile_data['message'];
            }
        }

        //----------Update Password Section----------//
        if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['pass'] == 'updatePass'){

            $pass_resp = $auth->update_password($_POST['password'],$_POST['newPass'],$_POST['conPass']);

            if(isset($pass_resp['message'])){
                $_SESSION['message'] = $pass_resp['message'];
            }else{
                $_SESSION['error'] = $pass_resp['error'];
            } 
        }


        //--------Fetching customer details for Profile Section------------//
        $cust_details = $account->customer_details($email); 
        $cust_data = json_decode($cust_details,true);

        $order_count = $cust_data['order_count'];
        $order_id = $cust_data['order_list'];

        $_SESSION['fname'] = $cust_data['first_name'];

        //----------Fetching all unfiltered orders----------------//
          //$order_details = $account->order_details($order_id,$order_count);
          //$order_data = json_decode($order_details ,true);

        //-----------Fetching unfiltered Subscribed orders--------------//
         //$orders = $order_count == 1 ? $order_data : $order_data['data'][$order_id[0]];

    } 

?>

<!--  Including Header-->
<?php include 'common/header.php' ; ?>  

    <!-- Main Sec -->
    <section class="main_sec account_page my-5">
        <div class="container">
            <div class="row cmn_box py-5 px-0 px-md-4">
                <h1 class="order_title text-dark mb-4">My Account</h1>
                <div class="col-md-12">
                    <div class="each_list">
                        <div class="tab_sec">
                            <div class="tab_list">
                                <button class="tab_button active_tab" onclick="openTab(event, 'login')">Login & Security</button>
                                <!-- <button class="tab_button" onclick="openTab(event, 'payment')">Payment & Addresses</button> -->
                            </div>
                            <div id="login" class="tab_content login_tab active_tab">

                                  <form action="account.php" id="login_security_form" method="POST">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="m-0">My Profile</h4>
                                        <p class="m-0"><a href="javascript:void(0);" class="text-dark" id="edit_profile">Edit Profile</a></p>
                                    </div>
                                    
                                    <?php if(isset($cust_data)){ ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="each_input mb-4">
                                                <label for="firstname" class="d-block text-dark mb-2">First Name <sup class="text-danger">*</sup></label>
                                                <input type="text" placeholder="First Name" name="fname" class="w-100" value="<?php echo $cust_data['first_name'];?>" required>
                                            </div>
                                            <input type="hidden" name="profile_update" value="profile_update">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="each_input mb-4">
                                                <label for="lastname" class="d-block text-dark mb-2">Last Name <sup class="text-danger">*</sup></label>
                                                <input type="text" placeholder="Last Name" name="lname" class="w-100" value="<?php echo $cust_data['last_name'];?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="each_input mb-4">
                                                <label for="email" class="d-block text-dark mb-2">Email</label>
                                                <input type="email" placeholder="Email" name='email' class="w-100" value="<?php echo $cust_data['email']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="each_input mb-4">
                                                <label for="phone" class="d-block text-dark mb-2">Phone <sup class="text-danger">*</sup></label>
                                                <input type="tel" placeholder="Phone" name="phone" class="w-100" value="<?php echo $cust_data['phone']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="change_pass_btn" style="display: none;">
                                            <div class="mt-4 text-end">
                                                <a href="javascript:void(0);" class="cancel_btn bg-white"
                                                    id="change_pass_cancel">Cancel</a>
                                                <input type="submit" value="Save Changes" class="save_btn text-white border-0 ms-3" >
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php } ?>

                                    
                                <form action="account.php" method="POST">
                                    <div class="row mt-3 mt-md-5">
                                        <h4 class="mb-4">Change Password</h4>
                                        <div>
                                        <?php  
                                            if(isset($_SESSION['msg_pass'])){
                                              echo $_SESSION['msg_pass'] ;
                                               unset($_SESSION['msg_pass']); 
                                            }  
                                         ?> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="each_input mb-4 position-relative">
                                                <label for="password" class="d-block text-dark mb-2">Password <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="password" name="password" class="w-100 pw_field" placeholder="Existing Password.." required>
                                                <i class="fa-solid fa-eye position-absolute eye_btn" id="pw_btn"
                                                    toggle=".pw_field"></i>
                                            </div>
                                            <input type="hidden" name="pass" value="updatePass">
                                        </div>
                                        <div class="col-md-6 d-none d-md-block">
                                            <div class="each_input mb-4 position-relative"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="each_input mb-4 position-relative">
                                                <label for="newpassword" class="d-block text-dark mb-2">New Password
                                                    <sup class="text-danger">*</sup></label>
                                                <input type="password" name="newPass" placeholder="New Password.." class="w-100 npw_field" required>
                                                <i class="fa-solid fa-eye position-absolute eye_btn" id="npw_btn"
                                                    toggle=".npw_field"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="each_input mb-4 position-relative">
                                                <label for="conewpassword" class="d-block text-dark mb-2">Confirm New
                                                    Password <sup class="text-danger">*</sup></label>
                                                <input type="password" name="conPass" placeholder="Confirm new Password.." class="w-100 cnp_field" required>
                                                <i class="fa-solid fa-eye position-absolute eye_btn" id="cnp_btn"
                                                    toggle=".cnp_field"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="profile_btn_sec" style="display: none;">
                                            <div class="mt-4 text-end">
                                                <a href="javascript:void(0);" class="cancel_btn bg-white"
                                                    id="profile_cancel">Cancel</a>
                                                <input type="submit" value="Save Changes"
                                                    class="save_btn text-white border-0 ms-3">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--<div id="payment" class="tab_content payment_tab">
                                <h4 class="mb-4">Payment Method</h4>
                                <div class="payment_box bg-white">
                                    <div class="each_list bg-white p-3">
                                        <div class="content_sec d-flex justify-content-between">
                                            <h6 class="text-uppercase"><strong>PAYMENT METHOD</strong></h6>
                                            <div class="default_sec position-relative">
                                                <h6 class="text-uppercase"><strong>DEFAULT</strong><i class="fa-solid fa-ellipsis-vertical ms-1 default_edit"></i></h6>
                                                <ul class="default_edit_list">
                                                    <li><a href="javascript:void(0);" id="account_edit_btn">Edit</a></li>
                                                    <li><a href="javascript:void(0);">Set as Default</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php 
                                        if(isset($order_data['total_orders'])){ 
                                            $odr_id = end($order_data['order_id']);     //fetch last value from 'order_id' array
                                            $rcnt_order = $order_data['data'][$odr_id]; // fetch recent order from 'data' aarray
                                        }       
                                        ?>
                                        <?php if(isset($order_data['total_orders'])) { ?>

                                        <div class="btm_content">
                                            <p class="my-3">Credit Card</p>
                                            <div class="card_details d-flex justify-content-between">
                                                <p>VISA Ending in 4440</p>
                                                <p><?php echo strtoupper($rcnt_order['cc_type']); ?> Ending with <?php echo $rcnt_order['cc_orig_last_4']; ?></p>
                                                <?php 
                                                 $input = $rcnt_order['cc_expires'];
                                                 $month = substr($input, 0, 2);
                                                 $year = "20" . substr($input, 2, 2); // Prefix "20" to the last two digits

                                                 $mmyyyy = $month .'/'.$year ; // MM/YYYY
                                                ?>
                                                <p>Expires: <?php echo $mmyyyy; ?> </p>
                                            </div>
                                        </div>

                                        <?php }else{ ?> 

                                        <div class="btm_content">
                                            <p class="my-3">Credit Card</p>
                                            <div class="card_details d-flex justify-content-between">
                                                <p>VISA Ending in 4440</p>
                                                <p><?php echo strtoupper($order_data['cc_type']); ?> Ending with <?php echo $order_data['cc_orig_last_4']; ?></p>
                                                <?php 
                                                 $input = $order_data['cc_expires'];
                                                 $month = substr($input, 0, 2);
                                                 $year = "20" . substr($input, 2, 2); // Prefix "20" to the last two digits

                                                 $mmyyyy = $month .'/'.$year ; // MM/YYYY
                                                ?>
                                                <p>Expires: <?php echo $mmyyyy; ?> </p>
                                            </div>
                                        </div>

                                        <?php } ?>
                                    </div>
                                    
                                    <?php if(isset($order_data['total_orders'])) { ?>

                                    <div class="each_list bg-white p-3">
                                        <div class="content_sec d-flex justify-content-between">
                                            <h6 class="text-uppercase"><strong>BILLING ADDRESS</strong></h6>
                                        </div>
                                        <div class="btm_content mt-1">
                                            <p><?php echo $rcnt_order['billing_first_name']; ?> <?php echo $rcnt_order['billing_last_name']; ?></p>
                                            <p><?php echo $rcnt_order['billing_street_address']; ?> , <?php echo $rcnt_order['billing_city']; ?></p>
                                            <p><?php echo $rcnt_order['billing_state']; ?>, <?php echo $rcnt_order['billing_postcode']; ?>, <?php echo $rcnt_order['billing_country']; ?></p>
                                        </div>
                                    </div>
                                    <div class="each_list bg-white p-3">
                                        <div class="content_sec d-flex justify-content-between">
                                            <h6 class="text-uppercase"><strong>SHIPPING ADDRESS</strong></h6>
                                        </div>
                                        <div class="btm_content mt-1">
                                           <p><?php echo $rcnt_order['shipping_street_address']; ?> , <?php echo $rcnt_order['shipping_city']; ?></p>
                                           <p><?php echo $rcnt_order['shipping_state']; ?>, <?php echo $rcnt_order['shipping_postcode']; ?>, <?php echo $rcnt_order['shipping_country']; ?></p>
                                        </div>
                                    </div>
                                    <div class="each_list bg-white p-3">
                                        <div class="content_sec d-flex justify-content-between">
                                            <h6 class="text-uppercase"><strong>ASSOCIATED ORDERS</strong></h6>
                                        </div>
                                        <div class="prod_details mt-2 ms-3">
                                           <?php foreach($order_data['order_id'] as $id){ ?> 
                                            <h5><strong><?php echo $order_data['data'][$id]['products'][0]['name'] ?></strong></h5>
                                            <p>Order ID: <?php echo $id ; ?></p>
                                           <?php } ?> 
                                        </div>
                                    </div>

                                    <?php }else{ ?>

                                    <div class="each_list bg-white p-3">
                                        <div class="content_sec d-flex justify-content-between">
                                            <h6 class="text-uppercase"><strong>BILLING ADDRESS</strong></h6>
                                        </div>
                                        <div class="btm_content mt-1">
                                            <p><?php echo $order_data['billing_first_name']; ?> <?php echo $order_data['billing_last_name']; ?></p>
                                            <p><?php echo $order_data['billing_street_address']; ?> , <?php echo $order_data['billing_city']; ?></p>
                                            <p><?php echo $order_data['billing_state']; ?>, <?php echo $order_data['billing_postcode']; ?>, <?php echo $order_data['billing_country']; ?></p>
                                        </div>
                                    </div>
                                    <div class="each_list bg-white p-3">
                                        <div class="content_sec d-flex justify-content-between">
                                            <h6 class="text-uppercase"><strong>SHIPPING ADDRESS</strong></h6>
                                        </div>
                                        <div class="btm_content mt-1">
                                           <p><?php echo $order_data['shipping_street_address']; ?> , <?php echo $order_data['shipping_city']; ?></p>
                                           <p><?php echo $order_data['shipping_state']; ?>, <?php echo $order_data['shipping_postcode']; ?>, <?php echo $order_data['shipping_country']; ?></p>
                                        </div>
                                    </div>
                                    <div class="each_list bg-white p-3">
                                        <div class="content_sec d-flex justify-content-between">
                                            <h6 class="text-uppercase"><strong>ASSOCIATED ORDERS</strong></h6>
                                        </div>
                                        <div class="prod_details mt-2 ms-3">
                                            <h5><strong>Titan</strong></h5>
                                            <p>Order ID: 288409</p>
                                        </div>
                                    </div>

                                    <?php } ?>

                                </div>
                            </div> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Common Drawer -->
     <!-- <section class="common_drawer account_edit_drawer">
        <div class="drawer_body bg-white">
            <div class="drawer_header d-flex justify-content-between align-items-center p-4 border-bottom border-1 border-dark">
                <h2 class="text-dark mb-0">Edit Billing Information</h2>
                <span class="account_edit_cross"><i class="fa-solid fa-xmark"></i></span>
            </div>
            <div class="drawer_main">

                <?php if(isset($orders)){ ?> 
                <form action="account.php" method="POST">
                    <div class="row card_info p-4 border-bottom border-1 border-dark">
                        <h5 class="cmn_head mb-3">Card Information</h5>
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Card Type</label>
                                <select name="cc_type" id="">
                                    <option value="">Select Card Type</option>
                                    <option value="visa" <?php if ($orders['cc_type'] == 'visa') echo "selected" ?>>Visa</option>
                                    <option value="mastercard" <?php if ($orders['cc_type'] == 'mastercard') echo "selected" ?>>Mastercard</option>
                                    <option value="maestro" <?php if ($orders['cc_type'] == 'maestro') echo "selected" ?>>Maestro</option>
                                    <option value="amex" <?php if ($orders['cc_type'] == 'amex') echo "selected" ?>>Amex</option>
                                    <option value="discover" <?php if ($orders['cc_type'] == 'discover') echo "selected" ?>>Discover</option>
                                    <option value="">Diners</option>
                                    <option value="">Solo</option> 
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="order_update" value="billing_info_update">
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Card Number</label>
                                <input type="tel" name="cc_number" placeholder="Card Number" value="<?php echo $orders['credit_card_number'];  ?>">
                            </div>
                        </div>
                        <?php
                            $input = $orders['cc_expires'];
                            $month = substr($input, 0, 2);
                            $year = "20" . substr($input, 2, 2); // Prefix "20" to the last two digits

                            $mmyyyy = $month .'/'.$year ; // MM/YYYY                         
                        ?>
                        <div class="col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Expiration Date(MM/YYYY) <sup class="text-danger">*</sup></label>
                                <input type="text" name="cc_exp" placeholder="02/2029" value="<?php echo $mmyyyy;  ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class=" mb-2 d-flex justify-content-between align-items-center">CVV <sup class="text-danger">*</sup><span><a href="javascript:void(0);" class="text-dark">What's this?</a></span></label>
                                <input type="tel" name="cc_cvv">
                            </div>
                        </div>
                    </div>
                    <div class="row shipping_info p-4 border-bottom border-1 border-dark">
                        <h5 class="cmn_head mb-3">Shipping Address</h5>
                        <div class="col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">First Name</label>
                                <input type="text" name="sh_fname" placeholder="First Name" value="<?php echo $orders['shipping_first_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Last Name</label>
                                <input type="text" name="sh_lname" placeholder="Last Name" value="<?php echo $orders['shipping_last_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Address</label>
                                <input type="text" name="sh_add1" placeholder="Address" value="<?php echo $orders['shipping_street_address'];  ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Address 2 (Optional)</label>
                                <input type="text" name="sh_add2" value="<?php echo $orders['shipping_street_address2']; ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Country</label>
                                <select name="sh_country" id="">
                                    <option value="">Select Country</option>
                                    <option value="US" <?php if($orders['shipping_country']=="US") echo "selected"; ?>>United States</option>
                                    <option value="">Canada</option>
                                    <option value="">United Kingdom</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">State</label>
                                <select name="sh_state" id="">
                                    <option value="">Select State</option>
                                    <option value="">Alabama</option>
                                    <option value="">Alska</option>
                                    <option value="">Arizona</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">ZIP Code</label>
                                <input type="tel" name="sh_zip" placeholder="12345" value="<?php echo $orders['shipping_postcode'];?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Phone</label>
                                <input type="tel" name="phone" placeholder="12345" value="<?php echo $orders['customers_telephone'];?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input mb-4 d-flex align-items-center">
                                <input type="checkbox" id="shipping_check">
                                <p class="text-dark mb-0 ms-2">Billing address is the same as shipping</p>
                            </div>
                        </div>
                    </div>
                    <div class="row billing_info p-4 border-bottom border-1 border-dark">
                        <h5 class="cmn_head mb-3">Billing Address</h5>
                        <div class="col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">First Name</label>
                                <input type="text" name="b_fname" placeholder="First Name" value="<?php echo $orders['billing_first_name'];?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Last Name</label>
                                <input type="text" name="b_lname" placeholder="Last Name"  value="<?php echo $orders['billing_last_name'];?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Address</label>
                                <input type="text" name="b_add1" placeholder="Address"  value="<?php echo $orders['billing_street_address'];?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Address 2 (Optional)</label>
                                <input type="text" name="b_add2" value="<?php echo $orders['billing_street_address2'];?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Country</label>
                                <select name="b_country" id="">
                                    <option value="">Select Country</option>
                                    <option value="US" <?php if($orders['billing_country']=="US") echo "selected"; ?>>United States</option>
                                    <option value="">Canada</option>
                                    <option value="">United Kingdom</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">State</label>
                                <select name="b_state" id="">
                                    <option value="">Select State</option>
                                    <option value="">Alabama</option>
                                    <option value="">Alska</option>
                                    <option value="">Arizona</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">ZIP Code</label>
                                <input type="tel" name="b_zip" placeholder="12345"  value="<?php echo $orders['billing_postcode'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="row update_order_info p-4 pb-0">
                        <h5 class="cmn_head mb-3">Update Associated Order(s)</h5>
                        <div class="col-md-12">
                            <?php foreach($cust_data['order_list'] as $order_id){ ?>
                            <div class="each_input mb-4 d-flex align-items-center">
                                <input type="checkbox" name="orders[]" value="<?php echo $order_id; ?>">
                                <p class="text-dark mb-0 ms-2"><?php echo $order_id; ?></p>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="each_input" >
                                <input type="submit" value="Update Payment Method" class="drawer_submit_btn text-white border-0">
                            </div>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </div>
        </div>
     </section> -->

<!--  Including Header-->
<?php include 'common/footer.php' ; ?>  

<script>
    document.addEventListener("DOMContentLoaded", function () {

        //----------Loader for all form submit------//
        let forms = document.querySelectorAll("form"); 
        let edit = document.getElementById("edit_profile");
        let cancelbtn = document.querySelector('.cancel_btn');
        
        //-----disable input field by default---------------//
        forms.forEach(function (form) { 
            let inputs = form.querySelectorAll("input");
            inputs.forEach(input => input.disabled = true);
        });
        
        //-----On Edit Click Enable input field---------------//
        edit.addEventListener("click",function() {
            forms.forEach(function (form) { 
                let inputs = form.querySelectorAll("input");
                inputs.forEach(input => input.disabled = false);
            });
            
        });
        
        //-----On Cancel Click disable input field---------------//
        cancelbtn.addEventListener("click",function() {

            document.querySelectorAll('.profile_btn_sec').forEach(el => {
                el.style.display = 'none';
            });
            forms.forEach(function (form) {  
                let inputs = form.querySelectorAll("input");
                inputs.forEach(input => input.disabled = true);
            });
            
        });
        
    });
</script>