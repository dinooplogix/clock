<?php
add_action('init', 'dls_admin_check_status');

function dls_admin_check_status() {
    //print_r($_SERVER);
    //update_option('dls_admin_logged', 0);
    //echo get_option('dls_admin_logged');
}

add_action('wp_login', 'dls_when_administrator_login', 10, 2);

function dls_when_administrator_login($user_login, $user) {
    $user_id = $user->ID;

    if ($user_id) {

        $cap = get_user_meta($user_id, 'wp_capabilities', true);

        if (array_key_exists('administrator', $cap)) {
            // administrator login
            $admin_count = get_option('dls_admin_logged');
            ++$admin_count;
            update_option('dls_admin_logged', $admin_count);
        }
    }
}

add_action('clear_auth_cookie', 'dls_when_administrator_logout');

function dls_when_administrator_logout() {
    $user_id = get_current_user_id();

    if ($user_id) {

        $cap = get_user_meta($user_id, 'wp_capabilities', true);

        if (array_key_exists('administrator', $cap)) {
            // administrator logout
            $admin_count = get_option('dls_admin_logged');
            --$admin_count;
            update_option('dls_admin_logged', $admin_count);
        }
    }
}

add_action('wp_head', 'dls_chat_hide');

function dls_chat_hide() {

    if (!get_option('dls_admin_logged')) {
        ?><style type="text/css"> #chat-block-site { display: none; } </style><?php
    }
}

add_action('wp_footer', 'dls_offline_caht');

function dls_offline_caht() {

    if (!get_option('dls_admin_logged')) {
        ?>
        <div class="offline-chat">
            <h3>Live Chat</h3>
            <p>Hello there. We are currently offline. Please leave us a message. Thanks.</p>
            <?php echo do_shortcode('[contact-form-7 id="648" title="offlinechat"]'); ?>
            <a href="<?php echo site_url() . '/company/request-a-quote/'; ?>" class="request-quote">Request a Quote</a>
        </div>
        <?php
    }
}

add_shortcode('reset-offline-chat', 'reset_offline_chat');

function reset_offline_chat() {
    $count = get_option('dls_admin_logged');
    if (isset($_POST['count'])) {
        $trim = trim($_POST['count']);
        update_option('dls_admin_logged', $trim);
    }
    ?>
    <div class="form-reset">
        <form action="" method="post" >
            <label>Number of currently logged in admin users:</label>
            <input type="number" name="count" value="<?php echo $count; ?>">
            <input type="submit" value="update">
        </form>
    </div>
    <?php
}
