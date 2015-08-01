// Live Chat sliding
jQuery(function ($) {

    // Collapsible Pannel
    $(document).on('click', '.sidebar-widgets h3', function (e) {
        var $this = $(this);
        if (!$this.hasClass('panel-collapsed')) {
            $this.next().slideUp();
            $this.addClass('panel-collapsed');
            //$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.next().slideDown();
            $this.removeClass('panel-collapsed');
            //$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });


    //$('.sidebar-widgets h3').next().slideUp();
    //$('.sidebar-widgets h3').addClass('panel-collapsed');
    $('.woof_container_maxnetworkspeed h3').trigger('click');
    $('.woof_container_inner_processormodel h3').trigger('click');
    $('.woof_container_inner_other h3').trigger('click');
    $('.widget_ram_filter h3').trigger('click');
    $('.widget_max_internal_drives_filter h3').trigger('click');
    $('.widget_mhc_processor_speed_filter h3').trigger('click');


    // Hide and unhide Offline Chat

    var stickBox = $(".offline-chat");

    var msgWidth = stickBox.outerHeight(true);

    var stickBoxSwitch = false;
    var offset = 40;
    stickBox.css({bottom: -msgWidth + offset});

    $(document).mouseup(function (e) {

        /*
         * if the target of the click isn't the container...
         * nor a descendant of the container
         */
        if (!stickBox.is(e.target) && stickBox.has(e.target).length === 0) {
            if (stickBoxSwitch) {
                stickBox.animate({bottom: -msgWidth + offset});
                stickBoxSwitch = false;
            }
        }

    });

    $(".offline-chat h3").click(function () {
        if (stickBoxSwitch) {
            stickBox.animate({bottom: -msgWidth + offset});
            stickBoxSwitch = false;
        } else {
            stickBox.animate({bottom: '0px'});
            stickBoxSwitch = true;
        }
    });
});





jQuery("[name='register_form']").submit(function (e) {



    // Fom validation register form

    var error = false;

    var requiredFields = ["first_name", "last_name", "user_name", "user_email", "role", "user_pass", "confirm_password"];

    for (var i = 0; i < requiredFields.length; i++) {

        var $field = jQuery("[name='" + requiredFields[i] + "']");
        var fieldVal = getVal($field);
        var type = $field.attr("type");

        switch (type) {

            case "radio" :
                if (!$field.is(":checked")) {
                    $field.parent().css("color", "#f00");
                    $field.attr("style", "");
                } else {
                    $field.parent().attr("style", "");
                }
                break;


            default :
                if (fieldVal == '' || fieldVal == 0) {
                    $field.css("borderColor", "#f00");
                    error = true;
                } else {
                    $field.css("borderColor", "#CCC");
                }
                break;
        }

    }

    $pass1 = jQuery("[name='user_pass']");
    $pass2 = jQuery("[name='confirm_password']");
    $pass1_val = $pass1.val();
    $pass2_val = $pass2.val();


    if ($pass1_val != $pass2_val) {
        $pass1.css("borderColor", "#f00");
        $pass2.css("borderColor", "#f00");
        error = true;
    }

    if (error == true) {
        e.preventDefault();
    }
});


$pass1 = jQuery("[name='user_pass']");
$pass2 = jQuery("[name='confirm_password']");

$pass1.keyup(function () {
    $pass1_val = $pass1.val().length;
    if ($pass1_val < 5) {
        jQuery(".strength").html('<div class="strength-red">Very Weak</div>');
    }
    if ($pass1_val > 8) {
        jQuery(".strength").html('<div class="strength-green">Strong</div>');
    }


});


$pass2.keyup(function () {

    $pass1_val = $pass1.val();
    $pass2_val = $pass2.val();

    if ($pass1_val != $pass2_val) {
        jQuery(".strength").html('<div class="strength-red">Password Mismatch</div>');
    } else {
        jQuery(".strength").html('');
    }
});


//Replace the place holder with null string
function getVal($this) {
    val = $this.val();
    if (val == $this.attr('placeholder'))
        return '';
    else
        return val;
}


