<?php
/*
  Plugin Name: DLS Configuration
  Plugin URI:
  Description: A plugin that creates a separate set of users that are displayed in front-end
  Author: Dinoop
  Author URI: http://www.neolinktechnologies.com/
  Terms and Conditions:
  Version: 1.0
 */

define('DLS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('DLS_PLUGIN_URL', plugin_dir_url(__FILE__));

include_once DLS_PLUGIN_PATH . 'inc/cf-function.php';
include_once DLS_PLUGIN_PATH . 'inc/dls-members.php';
include_once DLS_PLUGIN_PATH . 'inc/page-redirect.php';
include_once DLS_PLUGIN_PATH . 'inc/actions-common.php';
include_once DLS_PLUGIN_PATH . 'inc/dls-virtual-servers.php';
include_once DLS_PLUGIN_PATH . 'inc/dls-check-admin-logged-in.php';
include_once DLS_PLUGIN_PATH . 'plans/plans.php';


register_activation_hook(__FILE__, 'dls_plugin_activate');

function dls_plugin_activate() {

    //add role
    add_role(
            'client', __('Client'), array(
        'read' => true, 
        'edit_posts' => true,
        'delete_posts' => false,
            )
    );

    add_role(
            'partner', __('Partner'), array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false,
            )
    );
}

add_action('wp_enqueue_scripts', 'add_user_scripts', 20);

function add_user_scripts() {
    wp_enqueue_style('add-user-styles', DLS_PLUGIN_URL . 'css/styles.css');

    wp_enqueue_script('validate-form', DLS_PLUGIN_URL . 'js/validate-form.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('delete-user', DLS_PLUGIN_URL . 'js/delete-user.js', array('jquery'), '1.0.0', true);

    //wp_enqueue_style('bootstrap-datetimepicker', DLS_PLUGIN_URL . 'css/bootstrap-datetimepicker.min.css');
    //wp_enqueue_script('moment', DLS_PLUGIN_URL . 'js/moment.js', array('jquery'), '1.0.0', true);
    //wp_enqueue_script('bootstrap-datetime', DLS_PLUGIN_URL . 'js/bootstrap-datetime.js', array('jquery'), '1.0.0', true);
    // Virtualserver scripts
    wp_enqueue_style('style-jquery-ui', DLS_PLUGIN_URL . 'pips/jquery-ui.min.css');
    wp_enqueue_style('style-jquery-ui-slider-pips', DLS_PLUGIN_URL . 'pips/jquery-ui-slider-pips.css');
    wp_enqueue_style('style-bootstrap-switch', DLS_PLUGIN_URL . 'pips/bootstrap-switch.min.css');
    wp_enqueue_style('style-calc-custom-style', DLS_PLUGIN_URL . 'pips/calc-custom-style.css');
    wp_enqueue_script('jquery-ui', DLS_PLUGIN_URL . 'pips/jquery-ui.min.js', array('jquery'), '1.2.0', true);
    wp_enqueue_script('jquery-ui-pips', DLS_PLUGIN_URL . 'pips/jquery-ui-slider-pips.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('jquery-bootstrap-switch', DLS_PLUGIN_URL . 'pips/bootstrap-switch.min.js', array('jquery'), '1.0.0', true);
}

add_action('wp_head', 'load_ajax_url');

function load_ajax_url() {
    ?>
    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <?php
}

add_shortcode('login-form', 'login_form');

function login_form() {

    if ($_POST['action'] == 'login') {
        $creds = array();
        $creds['user_login'] = $_POST['user_name'];
        $creds['user_password'] = $_POST['user_pass'];
        $creds['remember'] = true;
        custom_login($creds);
    }
    ?>
    <form action="" method="POST">
        <div class="form-group">
            <label for="vb_name" class="sr-only">Username</label>
            <input type="text" name="user_name" id="vb_name" value="" placeholder="Userame" class="form-control" />
        </div>

        <div class="form-group">
            <label for="vb_email" class="sr-only">Password</label>
            <input type="password" name="user_pass" value="" placeholder="Password" class="form-control" />
            <input type="hidden" name="action" value="register">
        </div>

        <input type="hidden" name="action" value="login">
        <input type="submit" value="submit">
    </form>
    <?php
}

function custom_login($creds) {

    $user = wp_signon($creds, false);
    if (is_wp_error($user)) {
        echo $user->get_error_message();
    } else {
        wp_redirect(home_url());
    }
}

function show_register_button() {
    if (!is_user_logged_in()) {
        ?><a href="<?php echo site_url(); ?>/register">Register</a><?php
    } else {
        ?><a href="<?php echo site_url(); ?>/account">Account</a><?php
    }
}
