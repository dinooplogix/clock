<?php

class DLS_Members {

    private $url_param_alias_user_identifier = "";
    private $url_param_alias_salt = "";
    private $message = "";
    private $html_display_path;

    function __construct() {
        $this->url_param_alias_user_identifier = "bin";
        $this->url_param_alias_salt = "mblo";
        $this->reset_link = site_url() . "/reset-password";
        $this->html_display_path = DLS_PLUGIN_PATH . 'display/';
        $this->redirect_after_register = site_url() . "/account";
    }

    /**
     * 
     * Display form to submit username or email
     */
    function display_username_enquery() {
        $form_action = 'forgot_password_action';
        $form_nonce_field = 'forgot_password_nonce_field';

        if (isset($_POST[$form_nonce_field]) && wp_verify_nonce($_POST[$form_nonce_field], $form_action)) {
            $this->identify_user();
        }

        include $this->html_display_path . 'form-rpue.php';
    }

    /**
     * 
     * Identy the user before sending mail containing reset link
     */
    function identify_user() {

        if ($_POST['user_login'] != '') {
            $user = get_user_by("login", $_POST['user_login']);
            if ($user == false) {
                $this->message = "User does not exist";
                cf_display_front_messages('warning', $this->message);
            } else {
                $this->send_reset_link($user);
            }
        } elseif ($_POST['user_email'] != '') {
            $user = get_user_by("email", $_POST['user_email']);
            if ($user == false) {
                $this->message = "User does not exist";
                cf_display_front_messages('warning', $this->message);
            } else {
                $this->send_reset_link($user);
            }
        }
    }

    function send_reset_link($user) {

        $blogname = get_option("blogname");
        $admin_email = get_option("admin_email");

        $p_salt = time();
        update_user_meta($user->ID, "p_salt", $p_salt);

        $encrypted_user_login = $this->encrypt($user->user_login);
        $encrypted_p_salt = $this->encrypt($p_salt);

        $reset_link = $this->reset_link . "?{$this->url_param_alias_user_identifier}={$encrypted_user_login}&{$this->url_param_alias_salt}={$encrypted_p_salt}";

        $to = $user->user_email;

        $subject = $blogname . " account - password reset";

        $message = "Hello $user->display_name \n\n\r";
        $message .= "The password for your $blogname account $user->user_login was changed.\n";
        $message .= "Please use the following link to reset your password. \n\n\r";
        $message .= $reset_link . "\n\n";
        $message .= "For the security reasons this link is active only for one hour. \n\r";
        $message .= "If clicking the link doesn't work you can copy the link into your browser window or type it there directly. \n\n\r";
        $message .= "Regards,\n";
        $message .= $blogname . " team";

        $headers = "From: {$blogname} <{$admin_email}> \r\n";

        if (wp_mail($to, $subject, $message, $headers)) {
            $this->message = "Please check your mail to reset password.";
            cf_display_front_messages('warning', $this->message);
        } else {
            $this->message = "Found an error in sending mail";
            cf_display_front_messages('warning', $this->message);
        }
    }

    function verify_reset_link_user() {

        if (!isset($_GET[$this->url_param_alias_user_identifier]) || !isset($_GET[$this->url_param_alias_salt])) {
            return false;
        }

        $user_login = urlencode(trim($_GET[$this->url_param_alias_user_identifier]));
        $salt = urlencode(trim($_GET[$this->url_param_alias_salt]));

        $user_login = $this->decript($user_login);
        $salt = $this->decript($salt);

        if (strpos($user_login, '@') !== false) {
            // submitted field is email
            $user = get_user_by("email", $user_login);
        } else {
            $user = get_user_by("login", $user_login);
        }

        if (!isset($user)) {
            $this->message .= "User does not exists";
            cf_display_front_messages('warning', $this->message);
            return false;
        }

        if (!isset($user->p_salt) && $salt != $user->p_salt) {
            // user not exists
            $this->message .= "Sorry for the inconvenience the system couldn't change your password. ";
            cf_display_front_messages('warning', $this->message);
            return false;
        }


        $saved_salt = $user->p_salt;

        $expiry_time = $saved_salt + 3600;
        $current_time = time();

        if ($expiry_time < $current_time) {
            // time expired
            $this->message .= "The link is expired. ";
            cf_display_front_messages('warning', $this->message);
            return false;
        }

        return $user->ID;
    }

    function encrypt($enc) {
        $enc = $enc . 'FghTy56klMnq3thum5nimgj';
        $enc = base64_encode($enc);
        $enc = urldecode($enc);
        return $enc;
    }

    function decript($dec) {
        $dec = urldecode($dec);
        $dec = base64_decode($dec);
        $dec = str_replace('FghTy56klMnq3thum5nimgj', '', $dec);
        return $dec;
    }

    function display_password_reset_form() {

        $user_id = $this->verify_reset_link_user();

        if ($user_id) {

            $user_id = $this->encrypt($user_id);

            $form_action = 'reset_password_action';
            $form_nonce_field = 'reset_password_nonce_field';

            if (isset($_POST[$form_nonce_field]) && wp_verify_nonce($_POST[$form_nonce_field], $form_action)) {
                $this->update_user_pass();
            }

            include $this->html_display_path . 'form-rp.php';
            
        } else {
            $this->message .= "Error detected, try again";
            cf_display_front_messages('danger', $this->message, true);
        }
    }

    function update_user_pass() {

        $user_id = $_POST[$this->url_param_alias_user_identifier];
        $user_pass = trim($_POST['user_pass']);

        $user_id = $this->decript($user_id);

        $userdata = array(
            'ID' => $user_id,
            'user_pass' => $user_pass,
        );

        if (wp_update_user($userdata)) {
            $this->message = "Your password changed successfully";
            cf_display_front_messages('success', $this->message);
        } else {
            $this->message = "Error in changing password";
            cf_display_front_messages('danger', $this->message);
        }
    }

    /**
     * 
     * @param type $post
     * Submiting the register form
     */
    function submit_register_form($post) {



        extract($post, EXTR_OVERWRITE);

        //$user_pass = $first_name;
        //$user_name = $user_email
        //$user_pass = wp_generate_password();

        $user_id = username_exists($user_name);
        if (!$user_id && email_exists($user_email) == false) {



            $user_id = wp_create_user($user_name, $user_pass, $user_email);

            $userdata = array(
                'ID' => $user_id,
                'user_pass' => $user_pass,
                'user_email' => $user_email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'role' => $role,
            );

            wp_update_user($userdata);

            foreach ($post as $key => $value) {
                if (!array_key_exists($key, $userdata))
                    add_user_meta($user_id, $key, $value, true);
            }


            $this->message .= apply_filters("user_added_successfully", $user_id);

            cf_display_front_messages('success', $this->message);
        } else {

            cf_display_front_messages('warning', 'User already exists.');
        }
    }

    function edit_register_form($post, $user_id) {

        if (!is_user_logged_in()) {
            return false;
        }

        extract($post, EXTR_OVERWRITE);


        $user_info = get_userdata($user_id);

        if ($user_info->data->user_email == $user_email || email_exists($user_email) == false) {

            $userdata = array(
                'ID' => $user_id,
                'user_pass' => $user_pass,
                'user_email' => $user_email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'role' => $role,
            );

            wp_update_user($userdata);

            foreach ($post as $key => $value) {
                if (!array_key_exists($key, $userdata))
                    update_user_meta($user_id, $key, $value);
            }

            cf_display_front_messages('success', 'User editted successfully');
        }else {
            cf_display_front_messages('warning', 'Found error in updation');
        }
    }

    function set_register_form_values($user_id = null) {
        $user = get_user_by('id', $user_id);

        if ($user_id == null) {
            $return = array(
                "first_name" => '',
                "last_name" => '',
                "user_name" => '',
                "user_email" => '',
                "gender" => '',
                "dob" => '',
                "role" => '',
                "user_pass" => '',
                "confirm_password" => '',
            );
        } else {
            $return = array(
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "user_name" => $user->user_name,
                "user_email" => $user->user_email,
                "gender" => $user->gender,
                "dob" => $user->dob,
                "role" => $user->role,
                "user_pass" => $user->confirm_password,
                "confirm_password" => $user->confirm_password,
            );
        }
        return $return;
    }

    function display_register_page() {

        // Submit my account
        if (isset($_POST['add_new_user_nonce_field']) && wp_verify_nonce($_POST['add_new_user_nonce_field'], 'add_new_user')) {
            $this->submit_register_form($_POST);
            ob_end_clean();
            wp_safe_redirect($this->redirect_after_register);
            exit;
        }

        $form_values = $this->set_register_form_values();
        extract($form_values, EXTR_OVERWRITE);

        $form_heading = "Create a Client or Partner Account";
        $form_name = "register_form";
        $display_password = true;
        $disable_username = false;
        $form_action = 'add_new_user';
        $form_nonce_field = 'add_new_user_nonce_field';

        include $this->html_display_path . 'form-register.php';
    }

    function display_account_page($user_id) {

        // Submit my account
        if (isset($_POST['edit_profile_nonce_field']) && wp_verify_nonce($_POST['edit_profile_nonce_field'], 'edit_profile')) {
            $message = $this->edit_register_form($_POST, $user_id);
            echo $message;
        }

        $user_form_values = $this->set_register_form_values($user_id);
        extract($user_form_values, EXTR_OVERWRITE);

        $form_heading = "Profile";
        $form_name = "register_form";
        $display_password = true;
        $disable_username = true;
        $form_action = 'edit_profile';
        $form_nonce_field = 'edit_profile_nonce_field';

        include $this->html_display_path . 'form-register.php';
    }

    function form_changer() {
        $basename = basename($_SERVER['REDIRECT_URL']);
        if ($basename == 'register') {
            $this->form_name = "register_form";
            $this->display_password = true;
            $user_form_values = $this->set_register_form_values(null);
        }

        if ($basename == 'account') {
            
        }

        if ($basename == 'edit-user') {

            //Check user capebility to edit
            $user_form_values = $this->set_register_form_values($_GET['user_id']);
        }
    }

}
