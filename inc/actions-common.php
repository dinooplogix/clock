<?php

add_filter("user_added_successfully", "send_mail_registered_user");

function send_mail_registered_user($user_id) {

    $user = get_user_by('id', $user_id);
    $first_name = $user->first_name;
    $last_name = $user->last_name;
    $blogname = get_option("blogname");
    $user_name = $user->user_login;
    $user_pass = $user->confirm_password;
    $admin_email = get_option("admin_email");

    $user_email = $user->user_email;

    $subject = "Welcome to $blogname";

    $message = "Hello $first_name $last_name \n";
    $message .= "Welcome to $blogname \n";
    $message .= "Login credentials \n";
    $message .= "username: $user_name, pass: $user_pass";

    $headers = "From: {$blogname} <{$admin_email}> \r\n";

    if (wp_mail($user_email, $subject, $message, $headers)) {
        return 'Login with your password';
    } else {
        return 'Mail sending failed';
    }
}

add_shortcode('virtual-servers', 'dls_display_calc');

function dls_display_calc() {
    $dvs = new Dls_Virtual_Servers();
    ob_start();
    $dvs->display_calc();
    $contents = ob_get_clean();
    return $contents;
}

add_shortcode('testcode', 'execute_test_code');

function execute_test_code() {
    
}
