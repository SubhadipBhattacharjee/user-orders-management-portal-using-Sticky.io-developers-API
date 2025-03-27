<?php 
require_once __DIR__.'/controllers/Account.php';
require_once __DIR__.'/controllers/Auth.php';

    $auth = new Auth();

    if(!$auth->isLoggedIn()){
        header("Location: index.php");
        exit();
    }else{

        $account = new Account();
        $email = $_SESSION['user']['email'];

        //----Fetching all Products and States details------//
        $jsonData = file_get_contents('public/js/data.json'); 
        $dataArr = json_decode($jsonData, true);
        
        //--------Fetching customer details by enail------------//
        $cust_details = $account->customer_details($email); 
        $cust_data = json_decode($cust_details,true);

        $order_count = $cust_data['order_count'];
        $order_id = $cust_data['order_list'];

        //----------Fetching all unfiltered Orders----------------//
        $order_details = $account->order_details($order_id,$order_count);
        $order_data = json_decode($order_details ,true);

        //-----Fetching all(Subscription & One time Purchase) filtered orders------//
        $orderRecords = $account->filtered_all_orders($order_data);
       
    } 
?>

<!--  Including Header-->
<?php include 'common/header.php' ; ?>  

    <!-- Main Sec -->
    <section class="main_sec order_history_page my-5">
        <div class="container">
            <div class="cmn_box order_history_sec py-5 px-4">
                <div class="top_sec d-none justify-content-between align-items-center">
                    <!-- <div class="calender_sec">
                        Need to place calender
                    </div> -->
                    <div class="search_sec w-100 position-relative">
                        <input type="text" id="search" placeholder="Search by Order ID" class="search_field w-100 bg-transparent border-0">
                        <button type="submit" class="search_btn bg-transparent border-0 position-absolute end-0"><i class="fa-solid fa-search"></i></button>
                    </div>
                </div>
                <div class="table_sec w-100 my-4">
                    <!-- <table class="order_table w-100 bg-white">
                        <tr>
                            <th>Order Date</th>
                            <th>Order ID #</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        <?php foreach($orderRecords as $order){ 
                              if($order['key'] == "1"){?>
                        <tr>
                            <td><?php echo $order['order_date'];  ?></td>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo "$".number_format($order['total_price'],2); ?></td>
                            <td class="text-success"><?php echo $order['status'] ; ?></td>
                            <td class="text-end" id="<?= $order['order_id']; ?>_details"><a href="javascript:void(0);" class="text-dark view_details">View Details</a></td>
                        </tr>
                        <?php }} ?>   
                    </table> -->
                    <table id="myTable" class="display order_table w-100 bg-white">
                        <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Order ID #</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orderRecords as $order){ 
                              if($order['key'] == "1"){?>
                            <tr>
                                <td><?php echo $order['order_date'];  ?></td>
                                <td><?php echo $order['order_id']; ?></td>
                                <td><?php echo "$".number_format($order['total_price'],2); ?></td>
                                <td class="text-success"><?php echo $order['status'] ; ?></td>
                                <td class="text-end" id="<?= $order['order_id']; ?>_details"><a href="javascript:void(0);" class="text-dark view_details">View Details</a></td>
                            </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>
                <div class="breadcrumb_sec mt-5 d-none justify-content-between">
                    <p class="text-dark m-0">Showing 1 - 1 of 1</p>
                    <ul class="d-flex align-items-center">
                        <li><a href="javascript:void(0);">Prev</a></li>
                        <li><a href="javascript:void(0);" class="active">1</a></li>
                        <li><a href="javascript:void(0);">Next</a></li>
                    </ul>
                </div>
            </div>



            <?php 
                foreach($orderRecords as $order){ 
                    if($order['key'] == "1"){     
            ?>
            <div class="cmn_box view_details_sec py-5 px-4" id="<?= $order['order_id']; ?>dsec" style="display: none;">
                <p><a href="javascript:void(0);" class="return_btn text-dark">← Return to Order History</a></p>
                <h2 class="order_title mb-0">Order ID <?= $order['order_id']; ?></h2>
                <div class="row cmn_box pt-4">
                    <div class="col-lg-8 col-md-6">
                        <div class="left_sec">
                            <div class="table_sec w-100">
                                <table class="order_table w-100 bg-white">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                   
                                    <?php foreach($orderRecords as $odr){ 
                                            if($order['order_id'] == $odr['order_id']){?>
                                    <tr>
                                        <td class="d-flex align-items-center">
                                        <span><?= $odr['product_name']; ?></span>
                                        </td>
                                        <td>$<?= $odr['price']; ?></td>
                                        <!-- <img src="<?= $prod['image']; ?>" alt="" class="table_prod_img me-3"><span><?= $odr['name']; ?></span> -->
                                      
                                        <td><?= $odr['quantity']; ?></td>
                                        <td class="text-end">$<?= number_format(($odr['quantity'] * $odr['price']),2); ?></td>
                                    </tr>
                                    <?php }} ?>
                                    
                                </table>
                            </div>

                            
                            <div class="middle_sec bg-white">
                                <div class="each_list mb-2 d-flex justify-content-between align-items-center">
                                    <h6>Subtotal</h6>
                                    <p>$<?= number_format($order['sub_total'],2); ?></p>
                                </div>
                                <div class="each_list mb-2 d-flex justify-content-between align-items-center">
                                    <h6>Discount</h6>
                                    <p>$<?= $order['discount']; ?></p>
                                </div>
                                <div class="each_list mb-2 d-flex justify-content-between align-items-center">
                                    <h6>Tax</h6>
                                    <p>$<?= number_format($order['tax'],2); ?></p>
                                </div>
                                <div class="each_list d-flex justify-content-between align-items-center">
                                    <h6>Shipping</h6>
                                    <p>$<?= $order['ship_amount']; ?> </p>
                                </div>
                            </div>
                            <div class="bottom_sec bg-white d-flex justify-content-between align-items-center">
                                <h6>Total</h6>
                                <p>$<?= number_format($order['total_price'],2); ?></p>
                            </div>
                            
                        </div>
                    </div>

                   
                    <div class="col-lg-4 col-md-6 mt-4 mt-md-0">
                        <div class="right_sec for_height">
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h3>Order ID</h3>
                                    <h6><?= $order['order_id']; ?></h6>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </div>
                            </div>
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h3>Order Date</h3>
                                    <h6><?= $odr['order_date']; ?></h6>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </div>
                            </div>
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h3>Payment Status</h3>
                                    <h6>Paid</h6>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            </div>
                            <!-- <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h3>Tracking Number</h3>
                                    <h6>--</h6>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-truck"></i>
                                </div>
                            </div> -->
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h3>Email</h3>
                                    <h6><?= $odr['email']; ?></h6>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h3>Phone</h3>
                                    <h6><?= $odr['phone']; ?></h6>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                            </div>
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h3>Delivery Address | <span class="text-dark">Billing Address</span></h3>
                                    <h6><?= $odr['add1']; ?></h6>
                                    <h6><?= $odr['add2']; ?>,<?= $odr['state']; ?>, <?= $odr['pin']; ?></h6>
                                    <h6><?= $odr['country']; ?></h6>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                   

                </div>
            </div>
            <?php }} ?>
          


        </div>
    </section>

<!--  Including footer-->
<?php include 'common/footer.php' ; ?>    


<script>
    document.addEventListener("DOMContentLoaded", function () { 

        <?php foreach($orderRecords as $odr){ 
              if($odr['key'] == "1"){?>

            $(document).on("click", "#<?= $odr['order_id']; ?>_details" , function (event) { 
                $("#<?= $odr['order_id']; ?>dsec").show();
                $('.order_history_sec').hide();
            });

            $('.return_btn').click(function () {
                $("#<?= $odr['order_id']; ?>dsec").hide();
                $('.order_history_sec').show();
            });

        <?php }} ?>    

    });
</script>