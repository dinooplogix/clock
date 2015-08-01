jQuery('.removeUser').click(function(e) {
    e.preventDefault();
    var msg = 'Confirm the user deleting';
    var $this = jQuery(this);
    if (confirm(msg)) {
        ajaxWork($this);
    } else {
        false;
    }

});

function ajaxWork($this) {

    $this.parent().parent().css('display', 'none');

    var userId = $this.attr('data-user-id');

    var data = {
        action: 'delete_user_action',
        userId: userId
    }
    
    jQuery.post(ajaxurl, data, function(resp) {
        jQuery("#feedback").html(resp);
    });

    return false;
}

