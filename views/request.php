<!--  Including footer-->
<?php include 'common/header.php' ; ?>     

    <!-- Main Sec -->
    <section class="main_sec order_history_page request_page my-5">
        <div class="container">
            <div class="cmn_box order_history_sec py-5 px-4">
                <div class="top_sec d-flex justify-content-between align-items-center">
                    <div class="date_picker_sec position-relative">
                        <i class="fa-solid fa-calendar-alt calender_icon position-absolute"></i>
                        <i class="fa-solid fa-caret-down down_icon position-absolute"></i>
                        <input type="text" name="daterange" value="11/30/2024 - 02/28/2025" class="date_range" />
                    </div>
                    <div class="search_sec w-100 position-relative">
                        <input type="text" id="search" placeholder="Search by Order ID"
                            class="search_field w-100 bg-transparent border-0">
                        <button type="submit" class="search_btn bg-transparent border-0 position-absolute end-0"><i
                                class="fa-solid fa-search"></i></button>
                    </div>
                </div>
                <div class="table_sec w-100 my-4">
                    <table class="order_table w-100 bg-white">
                        <tr>
                            <th>Order ID #</th>
                            <th>Order Date</th>
                            <th>Request</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>282523</td>
                            <td>January 22, 2025</td>
                            <td>Cancel Subscription</td>
                            <td class="text-success">Processed</td>
                            <td class="text-end"><a href="javascript:void(0);" class="text-dark view_details">View
                                    Details</a></td>
                        </tr>
                    </table>
                </div>
                <div class="breadcrumb_sec mt-5 d-flex justify-content-between">
                    <p class="text-dark m-0">Showing 1 - 1 of 1</p>
                    <ul class="d-flex align-items-center">
                        <li><a href="javascript:void(0);">Prev</a></li>
                        <li><a href="javascript:void(0);" class="active">1</a></li>
                        <li><a href="javascript:void(0);">Next</a></li>
                    </ul>
                </div>
            </div>
            <div class="cmn_box view_details_sec py-5 px-4" style="display: none;">
                <p><a href="javascript:void(0);" class="return_btn text-dark">← Return to Order History</a></p>
                <h2 class="order_title mb-0">Request Details - Order #282523</h2>
                <div class="row cmn_box pt-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="left_sec bg-white mb-4 mb-md-0">
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h6>Status</h6>
                                    <p class="text-success mb-0">Processed</p>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </div>
                            </div>
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h6>Request Type</h6>
                                    <p class="text-dark mb-0">Cancel Subscription</p>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-ban"></i>
                                </div>
                            </div>
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h6>Order ID</h6>
                                    <p class="text-dark mb-0">282523</p>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="each_list bg-white p-4 d-flex justify-content-between align-items-center">
                                <div class="content_sec">
                                    <h6>Date Created</h6>
                                    <p class="text-dark mb-0">January 22, 2025 1:22PM | EST</p>
                                </div>
                                <div class="icon_sec">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6">
                        <div class="right_sec bg-white">
                            <div class="top_sec p-4 d-flex justify-content-between align-items-center">
                                <p><i class="fa-solid fa-headset me-2"></i>Support Chat</p>
                                <a href="javascript:void(0);" class="resolve_btn text-white" disabled><i
                                        class="fas fa-check me-2"></i>Resolved</a>
                            </div>
                            <div class="middle_sec border-0">
                                <div class="support_agent d-flex justify-content-between align-items-center">
                                    <p class="position-relative"><img src="public/images/default_user.png" alt=""
                                            class="position-absolute start-0">Support Agent</p>
                                    <h6>1 month ago</h6>
                                </div>
                                <p class="mt-3">Your request has been approved</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!--  Including footer-->
<?php include 'common/footer.php' ; ?>         


    