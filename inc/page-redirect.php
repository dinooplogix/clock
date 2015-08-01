<?php

$virtual_page = array(
    'forget-password' => 'Forget Password',
    'reset-password' => 'Reset Password',
    'register' => 'Register',
    'account' => 'My Account',
    'dedicated-servers' => 'Dedicated Servers',
);

add_action('init', 'dls_rewrite_rules');

function dls_rewrite_rules() {
    // add_rewrite_rule('^forget-password/([^/]*)/?$', 'index.php?pagename=dls-page-manager&tax_slug=$matches[1]&screen=assessment_single', 'top');
    add_rewrite_rule('^forget-password/?$', 'index.php?pagename=dls-page-manager&screen=forget-password', 'top');
    add_rewrite_rule('^reset-password/?$', 'index.php?pagename=dls-page-manager&screen=reset-password', 'top');
    add_rewrite_rule('^register/?$', 'index.php?pagename=dls-page-manager&screen=register-form', 'top');
    add_rewrite_rule('^account/?$', 'index.php?pagename=dls-page-manager&screen=profile-page', 'top');
    
    //show bare metals in dedicated page
    add_rewrite_rule('^hosting/dedicated-servers/?$', 'index.php?pagename=shop&product_cat=bare-metal-servers', 'top');
}

add_action('init', 'required_pages');

function required_pages() {
    ob_start();
    create_page_and_insert_shortcode('dls-page-manager');
}

add_filter('the_title', 'remove_dls_page_manager');

function remove_dls_page_manager($string) {
    
    global $virtual_page;

    if (is_admin()) {
        return $string;
    }

    if ($string == 'dls-page-manager') {

        $slug = (isset($_SERVER['REDIRECT_URL'])) ? basename($_SERVER['REDIRECT_URL']) : '';
        
        if (array_key_exists($slug, $virtual_page)) {
            $string = $virtual_page[$slug];
        } else {
            $string = '';
        }
    }
    
    if($string == 'Shop'){
        return 'DEDICATED SERVERS';
    }
    return $string;
}

add_shortcode('dls-page-manager', 'dls_page_manager_display');

function dls_page_manager_display() {

    $dlsmObj = new DLS_Members();

    $screen = get_query_var('screen');

    // example.com/forget-password
    if ($screen == 'forget-password') {
        $dlsmObj->display_username_enquery();
    }

    // example.com/reset-password
    if ($screen == 'reset-password') {
        $dlsmObj->display_password_reset_form();
    }

    // example.com/register
    if ($screen == 'register-form') {
        if (is_user_logged_in()) {
            ob_end_clean();
            wp_safe_redirect(site_url() . "/account");
            exit;
        }
        $dlsmObj->display_register_page();
    }

    // example.com/account
    if ($screen == 'profile-page') {
        $user_id = get_current_user_id();

        if ($user_id == 0) {
            ob_end_clean();
            wp_safe_redirect(site_url() . "/register");
            exit;
        } else {
            $dlsmObj->display_account_page($user_id);
        }
    }
}

add_filter('query_vars', 'dls_query_vars');

function dls_query_vars($qvars) {
    $qvars[] = 'screen';
    return $qvars;
}

//add_action('wp_title', 'rem');

function rem() {
    $slug = (isset($_SERVER['REDIRECT_URL'])) ? basename($_SERVER['REDIRECT_URL']) : '';
    echo "cm" . get_the_ID();

    $virtual_page = array(
        'forget-password' => 'Forget Password',
        'reset-password' => 'Reset Password',
        'register' => 'Register',
        'account' => 'My Account',
    );

    if (array_key_exists($slug, $virtual_page)) {
        $string = $virtual_page[$slug];
        $post = array(
            'ID' => get_the_ID(),
            'post_title' => $string,
        );

        wp_update_post($post);
    }
}

function dls_wp_title($title, $sep) {
    global $virtual_page;
    
    $slug = (isset($_SERVER['REDIRECT_URL'])) ? basename($_SERVER['REDIRECT_URL']) : '';
    
    if (array_key_exists($slug, $virtual_page)) {
        $string = $virtual_page[$slug];
        $title = $string . ' ' . $sep . ' ';
    }
    
    return $title;
}

add_filter('wp_title', 'dls_wp_title', 10, 2);
