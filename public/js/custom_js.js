//======================= For Login Page
$(document).ready(function () {
    $('.forgot_link').click(function () {
        $('#login_div').hide();
        $('#forgot_div').show();
    });
    $('.back_sign_btn').click(function () {
        $('#login_div').show();
        $('#forgot_div').hide();
    });
});
//======================= For Login Page Password Field
$("#eye_btn,#pw_btn,#npw_btn,#cnp_btn,#new_pass_btn,#con_pass_btn").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});
//======================= For User Dropdown
$('.user_sec').click(function () {
    $('.user_sec > ul').toggleClass('active_box')
});
//======================= For Log OUt Popup
$(document).ready(function () {
    $('#log_out').click(function () {
        $('#logout_popup').fadeIn();
    });
    $('.cancel_btn,.cross_btn').click(function () {
        $('#logout_popup').fadeOut();
    });
    $(window).click(function (event) {
        if ($(event.target).is('#logout_popup')) {
            $('#logout_popup').fadeOut();
        }
    });
});
//======================= For Header Menu
$('.hamburger').click(function () {
    $(this).toggleClass('open');
    $('.navigation').toggleClass('open-nav')
});

function subMenu() {
    $('.navigation ul > li > ul').parent().append('<i class="fa-solid fa-angle-down arw-nav"></i>');
    $('.navigation ul > li > .arw-nav').on('click', function () {
        $(this).parent('li').find('> ul').stop(true, true).slideToggle();
        $(this).parents('li').siblings().find('ul').stop(true, true).slideUp();
        $(this).toggleClass('actv');
        $(this).parent().siblings().find('.arw-nav').removeClass('actv');
    });
} subMenu();
//======================= For Subscription Edit Dropdown
// $('.subs_edit,.default_edit').click(function () {
//     $('.subs_edit_list,.default_edit_list').toggleClass('active_box')
// });
//======================= For Account Tab
function openTab(event, tabName) {
    const allTabs = document.querySelectorAll(".tab_content");
    allTabs.forEach(tab => tab.classList.remove("active_tab"));
    const allButtons = document.querySelectorAll(".tab_button");
    allButtons.forEach(button => button.classList.remove("active_tab"));
    const selectedTab = document.getElementById(tabName);
    selectedTab.classList.add("active_tab");
    event.currentTarget.classList.add("active_tab");
}
//======================= For Edit Profile Button
$('#edit_profile').click(function () {
    $('.profile_btn_sec').show();
});
$('.cancel_btn').click(function () {
    $('.profile_btn_sec').hide();
});
//======================= For Order View Details
// $('.view_details').click(function () {
//     $('.view_details_sec').show();
//     $('.order_history_sec').hide();
// });
// $('.return_btn').click(function () {
//     $('.view_details_sec').hide();
//     $('.order_history_sec').show();
// });
//======================= For Drawer
$('#account_edit_btn,#manage_subs').click(function () {
    $('.account_edit_drawer').addClass('active_drawer');
});
$('.account_edit_cross').click(function () {
    $('.account_edit_drawer').removeClass('active_drawer');
});
//======================= For CVV Popup
$(document).ready(function () {
    $('#cvv_click').click(function () {
        $('#cvv_popup').fadeIn();
    });
    $('.cross_btn').click(function () {
        $('#cvv_popup').fadeOut();
    });
    $(window).click(function (event) {
        if ($(event.target).is('#cvv_popup')) {
            $('#cvv_popup').fadeOut();
        }
    });
});
//======================= For Frequency Edit
// $('#frequency_edit').click(function () {
//     $('#frequency_form').toggleClass('active_frequency');
//     $('.show_text').toggleClass('hide_text');
// });
// $(document).ready(function () {
//     var isSelected = false;
//     $('#frequency_edit').click(function () {
//         if (!isSelected) {
//             $('.text_change').text('Cancel');
//             isSelected = true;
//         } else {
//             $('.text_change').text('Edit');
//             isSelected = false;
//         }
//     });
// });


//======================= For Delivery Edit
// $('#delivery_edit').click(function () {
//     $('#delivery_form').toggleClass('active_delivery');
//     $('.address_info').toggleClass('hide_text');
// });
// $(document).ready(function () {
//     var isSelected = false;
//     $('#delivery_edit').click(function () {
//         if (!isSelected) {
//             $('.text_change2').text('Cancel');
//             isSelected = true;
//         } else {
//             $('.text_change2').text('Edit');
//             isSelected = false;
//         }
//     });
// });


//======================= For Billing Edit
// $('#billing_edit').click(function () {
//     $('#billing_form').toggleClass('active_billing');
//     $('.billing_info').toggleClass('hide_text');
// });
// $(document).ready(function () {
//     var isSelected = false;
//     $('#billing_edit').click(function () {
//         if (!isSelected) {
//             $('.text_change3').text('Cancel');
//             isSelected = true;
//         } else {
//             $('.text_change3').text('Edit');
//             isSelected = false;
//         }
//     });
// });

//======================= For Payment Edit
// $('#payment_edit').click(function () {
//     $('#payment_form').toggleClass('active_payment');
//     $('.payment_info').toggleClass('hide_text');
// });
// $(document).ready(function () {
//     var isSelected = false;
//     $('#payment_edit').click(function () {
//         if (!isSelected) {
//             $('.text_change4').text('Cancel');
//             isSelected = true;
//         } else {
//             $('.text_change4').text('Edit');
//             isSelected = false;
//         }
//     });
// });

//======================= For Order Page Change Recurring Drawer
$('#change_recurring').click(function () {
    $('.change_rec_drawer').addClass('active_drawer');
});
$('.account_edit_cross,.rec_cancel_btn').click(function () {
    $('.change_rec_drawer').removeClass('active_drawer');
});
//======================= For Order Page Pause Subscription Drawer
$('#pause_subs').click(function () {
    $('.pause_subs_drawer').addClass('active_drawer');
});
$('.account_edit_cross,.pause_subs_btn').click(function () {
    $('.pause_subs_drawer').removeClass('active_drawer');
});