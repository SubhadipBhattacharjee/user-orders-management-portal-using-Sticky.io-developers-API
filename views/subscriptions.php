<?php 
require_once __DIR__.'/controllers/Subscription.php';
require_once __DIR__.'/controllers/Account.php';
require_once __DIR__.'/controllers/Auth.php';


    $auth = new Auth();

    if(!$auth->isLoggedIn()){
        header("Location: index.php");
        exit();
    }else{
        $subs = new Subscription();
        $account = new Account();
        $email = $_SESSION['user']['email'];

        //============Update Recurring Date=============//
        if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['recur'] == 'recurring'){
            
            if(!empty($_POST['recDate'])){
                $resp = $subs->update_recurring($_POST['recOrder'],$_POST['recDate']);
                if($resp['message']){
                    $_SESSION['message'] =  $resp['message'];
                }else{
                    $_SESSION['error'] = $resp['error'];
                }
            }else{
                $_SESSION['error'] = "Please select a future date to proceed...";
            }
        }
        
        //-----------Update Subscription Frequency--------------//
        if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['updateBilling'] == 'billing_model'){

            $bill_resp = $subs->update_billing_model($_POST['bill_model'],$_POST['subscriptionId']);
            if($bill_resp['message']){
                $_SESSION["message"] = $bill_resp['message'] ;
            }else{
                $_SESSION["error"] = $bill_resp['error'] ;
            }
        }

        //-------------Update Delivery Address----------//
        if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['delAdd'] == 'delivery_address'){

            $del_resp = $subs->update_delivery_address($_POST['del_oid']);
            $_SESSION['message'] = "Delivery address updated..";   
        }

        //------------Update Billing Address------------//
        if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['billAdd'] == 'billing_address'){

            $bill_add_resp = $subs->update_billing_address($_POST['bill_oid']);
            $_SESSION['message'] = "Billing address updated..";  
        }

        //-------------Update Payment Info-------------//
        if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['payment'] == 'payment'){

            $pay_resp = $subs->update_payment_info($_POST['pay_oid']);
            $_SESSION['message'] = "Payment Info updated.."; 
        }

        //==================Subscription Cancellation===================//
        if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['cancel_subs'] == 'cancel'){

            if(!empty($_POST['cancel_subs_id'])){
                $cancel_resp = $subs->stop_subscription($_POST['cancel_subs_id']);

                if($cancel_resp['message']){
                    $_SESSION['message'] = $cancel_resp['message'];
                }else{
                    $_SESSION['error'] = $cancel_resp['error'];
                }
            }else{
                $_SESSION['error'] = "Subscription ID not found..";
            }
        }


        //----Fetching all Products and States details------//
        $jsonData = file_get_contents('public/js/data.json'); 
        $dataArr = json_decode($jsonData, true);

        //----Fetching customer details for Profile Section-----//
        $cust_details = $account->customer_details($email); 
        $cust_data = json_decode($cust_details,true);

        $order_count = $cust_data['order_count'];
        $order_id = $cust_data['order_list'];

        $idArray = $order_count == 1 ? [$order_id] : $order_id ;

        //----------Fetching all Order_details----------------//
        $order_details = $account->order_details($order_id,$order_count);
        $order_data = json_decode($order_details ,true);  

        //---------All subscribed order_details------------//
        $orderRecords = $account->filtered_subscribed_orders($order_data);

         //-------fetched billing models in array format------//
         $subscription_id = $orderRecords[0]['subscription_id'];
         $billing_model = $subs->all_billing_models($subscription_id);

    } 
?>

<!--  Including footer-->
<?php include 'common/header.php' ; ?>  

     <!-- Main Sec -->
     <section class="main_sec my-5">
        <div class="container">
            <div class="row cmn_box py-5 px-0 px-md-4">
                <!-- <h1 class="order_title text-dark mb-0 mb-md-4">Your Orders & Subscriptions</h1> -->
                <h1 class="order_title text-dark mb-0 mb-md-4">Your Subscribed Order(s)</h1>
                <?php foreach($orderRecords as $order){ 
                      if($order['is_recur'] == '1' || $order['hold'] == '1' ){?>
                <div class="col-lg-4 col-md-6 my-4 mt-md-0">
                    <div class="right_sec subscription_page">

                        <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                            <div class="content_sec">
                                <h3 class="font_large"><?php echo $order['product_name']; ?></h3>
                                <h6>Order #<?php echo $order['order_id']; ?></h6>
                            </div>
                           
                            <?php 
                                $id = $order['order_id'];
                                $subs = $order['subscription_id'];
                            ?>
                            <div class="icon_sec position-relative">
                                <?php if($order['recurring_date'] != 'None'){ ?>
                                  <i class="fa-solid fa-ellipsis subs_edit" id="<?php echo $order['order_id'];?>"></i>
                                <?php } ?>
                                <ul class="subs_edit_list" style="display:none;" id="content<?php echo $order['order_id'];?>">
                                    <!-- <li><a href="javascript:void(0);" disabled>Cancellation Request</a></li> -->
                                    <li><a onclick="appendQueryParam({id: id })" id="<?php echo $order['order_id'];?>recur">Change Recurring Date</a></li>
                                    <li><a onclick="appendQueryParam({subs:'<?php echo $subs ?>',id: id })" id="<?php echo $order['order_id'];?>mng_subs">Manage Subscription</a></li>
                                    <?php if($order['hold'] != 1){ ?>
                                    <li><a href="javascript:void(0);" id="<?php echo $order['order_id'];?>pause_subs">Cancel Subscription</a></li>
                                    <?php } ?> 
                                </ul>
                            </div>
                        </div>
                        <div class="each_list bg-white p-4 d-flex justify-content-center align-items-center">
                            <?php 
                               foreach($dataArr['products'] as $prod){ 
                                if($prod['stickyId'] == $order['prod_id']){ 
                            ?>
                            <div class="img_sec">
                                <img src="<?= $prod['image']; ?>" alt="">
                            </div>
                            <?php }} ?>
                        </div>
                        <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                            <div class="content_sec">
                                <h3>Total (Excl. Tax & Shipping)</h3>
                                <h6>$<?php echo number_format($order['total_price'],2); ?></h6>
                            </div>
                        </div>
                        <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                            <div class="content_sec">
                                <h3>Next Shipment</h3>
                                <?php if($order['hold'] == 1){ ?>
                                 <sup class="text-danger">Subscription Stopped</sup>
                                <?php }else{ ?>
                                    <h6><?php echo $order['recurring_date']; ?></h6>
                                <?php } ?>    
                            </div>
                        </div>
                        <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                            <div class="content_sec">
                                <h3>Frequency</h3>
                                <h6><?php echo $order['recurring_freq']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }} ?>

            </div>
        </div>
    </section>

    <!--Common Drawer for Change Recurring-->
    <section class="common_drawer change_rec_drawer order_subs_page" >
        <div class="drawer_body bg-white">
            <div
                class="drawer_header d-flex justify-content-between align-items-center p-4 border-bottom border-1 border-dark">
                <h2 class="text-dark mb-0">Change Recurring</h2>
                <span class="account_edit_cross"><i class="fa-solid fa-xmark"></i></span>
            </div>
            <div class="drawer_main">
                <div class="each_sec p-4">
                    <h4 class="cmn_head">Next Recurring Date</h4>
                    <form action="subscriptions.php" id="change_rec_form" method="POST">
                        <div class="calendar-container mt-5">
                            <div class="calendar-header mx-auto">
                                <button type="button" id="prev-month"><i class="fa-solid fa-angle-left"></i></button>
                                <span id="month-year"></span>
                                <button type="button" id="next-month"><i class="fa-solid fa-angle-right"></i></button>
                            </div>
                            <div class="calendar-days">
                                <div class="day">Su</div>
                                <div class="day">Mo</div>
                                <div class="day">Tu</div>
                                <div class="day">We</div>
                                <div class="day">Th</div>
                                <div class="day">Fr</div>
                                <div class="day">Sa</div>
                            </div>
                            <div id="calendar-dates" class="calendar-dates"></div>
                            <div id="selected-date" class="selected-date d-none">Selected Date: None</div>
                        </div>
                        <input type="hidden" name="recur"  value="recurring">
                        <input type="hidden" name="recOrder" id="recId"  value="">
                        <input type="hidden" name="recDate" id="recDate" value="">
                        <input type="submit" value="Change Shipping Date" class="drawer_submit_btn text-white border-0 w-100" id="rec-change">
                        <a href="javascript:void(0);" class="rec_cancel_btn text-dark text-center mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
   


    <!-- Common Drawer For Manage Subscription-->
    <!-- Fetching all orders details-->
    <?php 
        //fetching all_order_ids in array
        $allOrders = isset($order_data['total_orders']) ? $order_data['order_id'] : [$order_data['order_id']] ;
        foreach ($allOrders as $orderId) { 
            $detail = isset($order_data['total_orders']) ? $order_data['data'][$orderId] : $order_data ;     
    ?>
    <section class="common_drawer account_edit_drawer order_subs_page next_shipment_page" id="<?php echo $orderId; ?>manage_subs">
        <div class="drawer_body bg-white">
            <div
                class="drawer_header d-flex justify-content-between align-items-center p-4 border-bottom border-1 border-dark">
                <h2 class="text-dark mb-0">Manage Subscription</h2>
                <span class="account_edit_cross"><i class="fa-solid fa-xmark"></i></span>
            </div>
            <div class="drawer_main">
                <div class="each_sec p-4 border-bottom border-1 border-dark">
                    <h5 class="cmn_head">Subscription</h5>
                    <div class="prod_sec d-flex align-items-center mt-4">
                    
                        <!-- <img src="public/images/Titan-BottleBox-1.png" alt=""> -->
                        <h4 class="cmn_head mb-0 ms-2"><?php echo $detail['products'][0]['name']; ?></h4>
                    </div>
                </div>

                <!-- <div class="each_sec p-4 border-bottom border-1 border-dark">
                    <h5 class="cmn_head">Next Recurring Date</h5>
                    <div class="calender_form">
                        <form action="">
                            <form action="">
                                <div class="calendar-container mt-5">
                                    <div class="calendar-header mx-auto">
                                        <button id="prev-month"><i class="fa-solid fa-angle-left"></i></button>
                                        <span id="month-year"></span>
                                        <button id="next-month"><i class="fa-solid fa-angle-right"></i></button>
                                    </div>
                                    <div class="calendar-days">
                                        <div class="day">Su</div>
                                        <div class="day">Mo</div>
                                        <div class="day">Tu</div>
                                        <div class="day">We</div>
                                        <div class="day">Th</div>
                                        <div class="day">Fr</div>
                                        <div class="day">Sa</div>
                                    </div>
                                    <div id="calendar-dates" class="calendar-dates"></div>
                                    <div id="selected-date" class="selected-date d-none">Selected Date: None</div>
                                </div>
                            <input type="submit" value="Change Shipping Date"
                                class="drawer_submit_btn text-white border-0 w-100" disabled>
                        </form>
                    </div>
                </div> -->

                <div class="each_sec p-4 border-bottom border-1 border-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="cmn_head">Frequency</h5>
                        <p class="font_small m-0">
                            <a href="javascript:void(0);" class="text-dark text_change" id="<?= $orderId; ?>freqEdit">Edit</a>
                        </p>
                    </div>
                    <p class="text-dark mt-3 show_text"><?php echo $detail['products'][0]['subscription_desc']; ?></p>
                    <div class="drawer_form frequency_form mt-3" id="<?= $orderId; ?>freqForm" style="display: none;">
                        <form action="subscriptions.php" method="POST">
                            <select name="bill_model" id="" class="w-100 mb-4">
                                <option value="">Please select a billing..</option>
                                   <?php if(isset($billing_model)){
                                    foreach($billing_model as $bill){
                                   ?>
                                  <option value="<?php echo $bill['id'] ?>"><?php echo $bill['period'] ?></option>
                                <?php }} ?>
                                <!-- <option value="">Every 90 days</option>
                                <option value="">Every 150 days</option>
                                <option value="">Every 60 days</option>
                                <option value="">Every 180 days</option>
                                <option value="">Every 360 days</option> -->
                                
                            </select>
                            <input type="hidden" name="updateBilling" value="billing_model">
                            <input type="hidden" name="subscriptionId" id="mngSubs" value="<?php echo $detail['products'][0]['subscription_id']; ?>">
                            <input type="submit" value="Change Billing Frequency" class="drawer_submit_btn text-white border-0 w-100">
                        </form>
                    </div>
                </div>

                <div class="each_sec p-4 border-bottom border-1 border-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="cmn_head">Delivery Address</h5>
                        <p class="font_small m-0">
                            <a href="javascript:void(0);" class="text-dark text_change2"id="<?= $orderId; ?>delEdit">Edit</a>
                        </p>
                    </div>
                    <div class="address_info">
                        <p class="text-dark mt-3 mb-0"><?php echo $detail['shipping_street_address']; ?></p>
                        <p class="text-dark mt-2 mb-0"><?php echo $detail['shipping_street_address2']; ?></p>
                        <p class="text-dark mt-2 mb-0"><?php echo $detail['shipping_country']; ?></p>
                    </div>
                    <div class="drawer_form delivery_form mt-3" id="<?= $orderId; ?>delForm" style="display: none;">
                        <form action="subscriptions.php" method="POST">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Address Line 1</label>
                                <input type="text" name="sh_add1" value="<?php echo $detail['shipping_street_address']; ?>" required>
                            </div>
                            <input type="hidden" name="del_oid" id="delAdd" value="<?= $orderId; ?>">
                            <input type="hidden" name="delAdd" value="delivery_address">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Address Line 2</label>
                                <input type="text" name="sh_add2" value="<?php echo $detail['shipping_street_address2']; ?>" required>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block">Country</label>
                                <select name="sh_country" id="" class="w-100" required>
                                    <option value="">Select Country</option>
                                    <option value="US"<?php if($detail['shipping_country'] == 'US') echo "selected"; ?>>United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="UK">United Kingdom</option>
                                </select>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">State</label>
                                <select name="sh_state" id="" class="w-100" required>
                                    <option value="">Select State</option>
                                    <?php foreach($dataArr['states'] as $state){ ?>
                                    <option value="<?= $state['code'] ?>"<?php if($detail['shipping_state'] == $state['code']) echo "selected";?>> <?= $state['name'] ?> </option>
                                    <?php } ?>
                                    <!-- <option value="AL">Alaska</option>
                                    <option value="AR">Arizona</option> -->
                                </select>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">City</label>
                                <input type="text" name="sh_city" value="<?php echo $detail['shipping_city']; ?>" required>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Zip Code</label>
                                <input type="tel" name="sh_zip" value="<?php echo $detail['shipping_postcode']; ?>" required>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Phone</label>
                                <input type="tel" name="phone" value="<?php echo $detail['customers_telephone']; ?>" required>
                            </div>
                            <div class="each_input">
                                <input type="submit" value="Update Address" class="drawer_submit_btn text-white border-0 w-100" >
                            </div>
                        </form>
                    </div>
                </div>


                <div class="each_sec p-4 border-bottom border-1 border-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="cmn_head">Billing Address</h5>
                        <p class="font_small m-0">
                            <a href="javascript:void(0);" class="text-dark text_change3"id="<?= $orderId; ?>billEdit">Edit</a>
                        </p>
                    </div>
                    <div class="billing_info">
                        <p class="text-dark mt-3 mb-0"><?= $detail['billing_street_address']; ?></p>
                        <p class="text-dark mt-2 mb-0"><?= $detail['billing_street_address2']; ?></p>
                        <p class="text-dark mt-2 mb-0"><?= $detail['billing_country']; ?></p>
                    </div>
                    <div class="drawer_form billing_form mt-3" id="<?= $orderId; ?>billForm" style="display: none;">
                        <form action="subscriptions.php" method="POST">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Address Line 1</label>
                                <input type="text" name="b_add1" value="<?= $detail['billing_street_address']; ?>" required>
                            </div>
                            <input type="hidden" name="bill_oid" id="billAdd" value="<?= $orderId; ?>">
                            <input type="hidden" name="billAdd" value="billing_address">
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Address Line 2</label>
                                <input type="text" name="b_add2" value="<?= $detail['billing_street_address2']; ?>" required>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block">Country</label>
                                <select name="b_country" id="" class="w-100" required>
                                    <option value="">Select Country</option>
                                    <option value="US" <?php if($detail['billing_country'] == 'US') echo "selected";?>>United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="UK">United Kingdom</option>
                                </select>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">State</label>
                                <select name="b_state" id="" class="w-100" required>
                                     <option value="">Select State</option>
                                    <?php foreach($dataArr['states'] as $st){ ?>
                                     <option value="<?= $st['code'] ?>" <?php if($detail['billing_state'] == $st['code']) echo "selected";?>><?= $st['name']; ?></option>
                                    <?php } ?>
                                    <!-- <option value="AL">Alaska</option>
                                    <option value="AR">Arizona</option> -->
                                </select>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">City</label>
                                <input type="text" name="b_city" value="<?= $detail['billing_city']; ?>" required>
                            </div>
                            <div class="each_input mb-4">
                                <label for="" class="d-block mb-2">Zip Code</label>
                                <input type="tel" name="b_zip" value="<?= $detail['billing_postcode']; ?>" required>
                            </div>
                            <div class="each_input">
                                <input type="submit" value="Update Address" class="drawer_submit_btn text-white border-0 w-100">
                            </div>
                        </form>

                    </div>
                </div>

                <div class="each_sec p-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="cmn_head">Payment Information</h5>
                        <p class="font_small m-0">
                            <a href="javascript:void(0);" class="text-dark text_change4" id="<?= $orderId; ?>payEdit">Edit</a>
                        </p>
                    </div>
                    <div class="payment_info">
                        <p class="text-dark mt-3 mb-0"><?= strtoupper($detail['cc_type']); ?></p>
                        <!-- <p class="text-dark mt-2 mb-0">test, AL, 12345</p> -->
                        <?php 
                            $input = $detail['cc_expires'];
                            $month = substr($input, 0, 2);
                            $year = "20" . substr($input, 2, 2); // Prefix "20" to the last two digits

                            $mmyyyy = $month .'/'.$year ; // MM/YYYY
                        ?>
                        <div class="card_details d-flex justify-content-between mt-2">
                            <p class="text-dark mb-0">VISA Ending with <?= $detail['cc_last_4']; ?></p>
                            <p class="text-dark mb-0">Expires <?= $mmyyyy ?> </p>
                        </div>
                    </div>

                    <div class="drawer_form payment_form mt-3" id="<?= $orderId; ?>payForm" style="display: none;">
                        <form action="subscriptions.php" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="each_input mb-4">
                                    <label for="" class="d-block mb-2">Card Type <sup
                                            class="text-danger">*</sup></label>
                                    <select name="cc_type" id="" required>
                                        <option value="">Select Card Type</option>
                                        <option value="visa" <?php if($detail['cc_type'] == "visa") echo "selected"; ?> >Visa</option>
                                        <option value="mastercard" <?php if($detail['cc_type'] == "mastercard") echo "selected"; ?>>Mastercard</option>
                                        <option value="discover" <?php if($detail['cc_type'] == "discover") echo "selected"; ?>>Discover</option>
                                        <!-- <option value="">Diners</option>
                                        <option value="">Solo</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="each_input mb-4">
                                    <label for="" class="d-block mb-2">Card Number <sup
                                            class="text-danger">*</sup></label>
                                    <input type="tel" name="cc_number" placeholder="Card Number" value="<?= $detail['credit_card_number']; ?>" required>
                                </div>
                                <input type="hidden" id="payment" name="pay_oid" value="<?= $orderId; ?>">
                                <input type="hidden" name="payment" value="payment" >
                            </div>
                            <div class="col-md-6">
                                <div class="each_input mb-4">
                                    <label for="" class="d-block mb-2">Expiration Date(MM/YYYY) <sup
                                            class="text-danger">*</sup></label>
                                    <input type="text" name="cc_exp" placeholder="02/2029" value="<?= $mmyyyy; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="each_input mb-4">
                                    <label for="" class=" mb-2 d-flex justify-content-between align-items-center">CVV
                                        <span><a href="javascript:void(0);" class="text-dark" id="cvv_click">
                                                </a></span></label>
                                    <input type="tel" name="cc_cvv" value="<?= $detail['cc_cvv']; ?>" >
                                </div>
                                <div id="cvv_popup" class="logout_popup">
                                    <div class="popup_content">
                                        <span class="cross_btn d-block text-end pt-2 pe-3 h4"><i
                                                class="fa-solid fa-xmark"></i></span>
                                        <img src="public/images/cvv_img.png" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="each_input">
                                    <input type="submit" value="Update Card Info"
                                        class="drawer_submit_btn text-white border-0 w-100" >
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>


    <!-- Common Drawer for Pause Subscription-->
    <?php 
        //fetching all_order_ids in array
        $allOrders = isset($order_data['total_orders']) ? $order_data['order_id'] : [$order_data['order_id']] ;
        foreach ($allOrders as $orderId) { 
            $detail = isset($order_data['total_orders']) ? $order_data['data'][$orderId] : $order_data ;     
    ?>
    <section class="common_drawer pause_subs_drawer order_subs_page" id="<?php echo $orderId; ?>cancel_subs">
        <div class="drawer_body bg-white">
            <div
                class="drawer_header d-flex justify-content-between align-items-center p-4 border-bottom border-1 border-dark">
                <h2 class="text-dark mb-0">Cancel Subscription</h2>
                <span class="account_edit_cross"><i class="fa-solid fa-xmark"></i></span>
            </div>
            <div class="drawer_main">
                <div class="each_sec p-4">
                    <!-- <p class="text-dark mb-0">Your subscription can be paused for a specific period or indefinitely. Choose the length of time you wish to hold.</p> -->
                    <div class="prod_sec d-flex align-items-center my-4">
                        <?php 
                           foreach($dataArr['products'] as $prod){ 
                              if($detail['products'][0]['product_id'] == $prod['stickyId'] ) {
                        ?>
                          <img src="<?= $prod['image'] ?>" alt="">
                        <?php }} ?>
                        <p class="mb-0 ms-2 text-dark"><?= $detail['products'][0]['name']; ?></p>
                    </div>
                    <form action="subscriptions.php" id="pause_subs_form" method="POST">
                        <!-- <div class="each_input d-flex align-items-center position-relative">
                            <input type="radio" class="w-auto">
                            <i></i>
                            <div class="text_sec ms-4">
                                <p class="text-dark m-0">1 Month rebill subscription (30 Days)</p>
                                <h6 class="m-0">Next Recurring Date: April 19, 2025</h6>    
                            </div>
                        </div> -->
                        <input type="hidden" name="cancel_subs" value="cancel">
                        <input type="hidden" name="cancel_subs_id" value="<?= $detail['products'][0]['subscription_id'] ?>">
                        <div class="each_input my-4">
                            <label for="" class="d-block cmn_head mb-2">Add Comment <sup class="text-danger">*(optional)</sup></label>
                            <textarea name="" id="" placeholder="Let us know why you're pausing your subscription..."></textarea>
                        </div>
                        <div class="each_input">
                               <input type="submit" value="Submit Request" class="drawer_submit_btn text-white border-0 w-100">
                            <a href="javascript:void(0);" class="pause_subs_btn text-white border-0 text-center mt-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>


<!--  Including footer-->
<?php include 'common/footer.php' ; ?>  

<script>
        function appendQueryParam(params) {
            let url = new URL(window.location.href);

            // Loop through the provided params object and update query parameters
            Object.keys(params).forEach(key => {
                url.searchParams.set(key, params[key]);
            });

            // Update the URL without reloading
            history.replaceState({}, '', url);
        }

        document.addEventListener("DOMContentLoaded", function () {

          <?php foreach ($idArray as $order) { ?>
            //var rcr = "#<?php echo $order; ?>recur" ; 
            //var mng_subs = "#<?php echo $order;?>mng_subs" ;


            //-----cancel Subscription Dynamic-------------//
            $(document).on("click", "#<?php echo $order;?>pause_subs" , function () { 
                $("#<?php echo $order;?>cancel_subs").addClass('active_drawer');
                //$(this).closest('.each_sec').find('.show_text').toggleClass('hide_text');
            });


            //----Frequency Edit dynamic-ID(Start) ------------//
            //var freq = "#<?php echo $order; ?>freqEdit";
            //var freqForm = "#<?php echo $order; ?>freqForm";

            $(document).on("click", "#<?php echo $order; ?>freqEdit" , function () { 
                $("#<?= $order; ?>freqForm").toggleClass('active_frequency');
                $(this).closest('.each_sec').find('.show_text').toggleClass('hide_text');
            });

            var isSelected = false;
            $("#<?php echo $order; ?>freqEdit").click(function () {
                if (!isSelected) {
                    $('.text_change').text('Cancel');
                    isSelected = true;
                } else {
                    $('.text_change').text('Edit');
                    isSelected = false;
                }
            });
            //----Frequency Edit dynamic-ID(End) ------------//


            //--------Delivery Add dynamic-ID(Start)-------//
            //var deladd = "#<?php echo $order; ?>delEdit";

            $(document).on("click", "#<?php echo $order; ?>delEdit" , function () { 
                $("#<?= $order; ?>delForm").toggleClass('active_delivery');
                $(this).closest('.each_sec').find('.address_info').toggleClass('hide_text');
            });

            var isSelected1 = false;
            $("#<?php echo $order; ?>delEdit").click(function () {
                if (!isSelected1) {
                    $('.text_change2').text('Cancel');
                    isSelected1 = true;
                } else {
                    $('.text_change2').text('Edit');
                    isSelected1 = false;
                }
            });


            //--------Billing Add dynamic-ID(Start)-------//
            //var billadd = "#<?php echo $order; ?>billEdit";

            $(document).on("click", "#<?php echo $order; ?>billEdit" , function () { 
                $("#<?= $order; ?>billForm").toggleClass('active_billing');
                $(this).closest('.each_sec').find('.billing_info').toggleClass('hide_text');
            });

            var isSelected2 = false;
            $("#<?php echo $order; ?>billEdit").click(function () {
                if (!isSelected2) {
                    $('.text_change2').text('Cancel');
                    isSelected2 = true;
                } else {
                    $('.text_change2').text('Edit');
                    isSelected2 = false;
                }
            });

            //--------Payment Info dynamic-ID(Start)-------//
            var payId = "#<?php echo $order; ?>payEdit";

            $(document).on("click", "#<?php echo $order; ?>payEdit" , function () { 
                $("#<?= $order; ?>payForm").toggleClass('active_payment');
                $(this).closest('.each_sec').find('.payment_info').toggleClass('hide_text');
            });

            var isSelected3 = false;
            $("#<?php echo $order; ?>payEdit").click(function () {
                if (!isSelected3) {
                    $('.text_change4').text('Cancel');
                    isSelected3 = true;
                } else {
                    $('.text_change4').text('Edit');
                    isSelected3 = false;
                }
            });


            //----------For Subscription Manage-----------//
            $(document).on("click", "#<?php echo $order;?>mng_subs", function () {

                $('#<?= $order?>manage_subs').addClass('active_drawer');
                let url = new URLSearchParams(window.location.search);

                let subs = url.get("subs");
                let oid = url.get("id");

                $("#mngSubs").val(subs);

                $("#delAdd").val(parseInt(oid));
                $("#billAdd").val(parseInt(oid));
                $("#payment").val(parseInt(oid));

            });

            //-------For Recurring date management----------//
            $(document).on("click", "#<?php echo $order; ?>recur", function () {
                $(".change_rec_drawer").addClass("active_drawer");
                let urlParams = new URLSearchParams(window.location.search);
                let recid = urlParams.get("id");
                $("#recId").val(parseInt(recid));
            });

           
            $("#<?php echo $order; ?>").on("click",function (event) {

                event.stopPropagation(); // Prevent bubbling
                //console.log($(this).next());

                let activeClass = "active_class_<?php echo $order; ?>";

                //Toggle class on the clicked element's next sibling
                $(this).siblings(".subs_edit_list").toggleClass(activeClass);
                $(this).siblings(".subs_edit_list").slideToggle();


            });
         <?php } ?>
            // Hide all open menus when clicking outside
            // $(document).on("click", function () {
            //     $(".subs_edit_list").hide(); 
            // });
       });
</script>
