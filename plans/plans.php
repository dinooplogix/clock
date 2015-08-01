<?php
// revert 2:47 if any error
define('DLSPLANS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('DLSPLANS_PLUGIN_URL', plugin_dir_url(__FILE__));

include_once DLSPLANS_PLUGIN_PATH . 'inc/function.php';


/**
 * 
 * Addd the external script files
 */
add_action('wp_enqueue_scripts', 'dlsplans_scripts', 20);

function dlsplans_scripts() {
    wp_enqueue_style('dlsplans-style-responsive-tabs', DLSPLANS_PLUGIN_URL . 'css/responsive-tabs.css');
    wp_enqueue_style('dlsplans-style-theme', DLSPLANS_PLUGIN_URL . 'css/style.css');

    wp_enqueue_script('dlsplans-script-responsivetabs', DLSPLANS_PLUGIN_URL . 'js/jquery.responsiveTabs.js', array('jquery'), '1.0.0', true);
}

/**
 * 
 * Shortcode to display tab
 */
add_shortcode('dlsplans-tab', 'dlsplans_display_tab');

function dlsplans_display_tab($atts) {

    $atts = shortcode_atts(array(
        'tab' => 'Tab 1-Tab 2',
        'posts' => ''
            ), $atts, 'dlsplans-tab');

    extract($atts);
    if($posts == ''){
        return false;
    }
    ob_start();
    $horizontalTabs = dlsplans_get_tabs($tab);
    $postid_bytab = dlsplans_get_postid_bytab($posts);
    ?>

    <div class="plan_shortcode">
        <section class="info_content ">
            <div class="borders paddings">
                <div class=""> 

                    <div id="horizontalTab">
                        <ul>
                            <?php
                            foreach ($horizontalTabs as $key => $value) {
                                echo '<li><a href="' . $key . '">' . $value . '</a></li>' . PHP_EOL;
                            }
                            ?>
                        </ul>

                        <?php foreach ($postid_bytab as $key => $batches): ?>
                            <div id="<?php echo $key; ?>">
                                <div class="row">
                                    <?php
                                    $post_ids = explode('-', $posts);

                                    foreach ($batches as $post_id):
                                        $plans = dlsplans_get_display_content($post_id);
                                        ?>                

                                        <div class="col-sm-6 col-md-3 col-lg-3">
                                            <div class="item_table promotion_table">
                                                <div class="head_table">
                                                    <h1><?php echo $plans['price']; ?></h1>
                                                    <h2><?php echo $plans['title']; ?> <span></span></h2>
                                                    <h5></h5>
                                                </div>
                                                <ul>
                                                    <?php foreach ($plans['other_values'] as $key => $value): ?>
                                                        <li class="<?php echo dlsplans_get_list_class($key); ?>"><?php echo $value; ?></li>
                                                    <?php endforeach; ?>
                                                </ul> 
                                                <a class="button" href="http://webfacility.com/order">Order Now</a>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>




                    </div>

                </div>
            </div>
        </section>
    </div>
    <?php
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
}

add_action('wp_footer', 'dlsplans_print_footer_scripts');

function dlsplans_print_footer_scripts() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var $tabs = $('#horizontalTab');
            $tabs.responsiveTabs({
                rotate: false,
                startCollapsed: 'accordion',
                collapsible: 'accordion',
                setHash: true,
                disabled: [3, 4],
                activate: function (e, tab) {
                    $('.info').html('Tab <strong>' + tab.id + '</strong> activated!');
                },
                activateState: function (e, state) {
                    //console.log(state);
                    $('.info').html('Switched from <strong>' + state.oldState + '</strong> state to <strong>' + state.newState + '</strong> state!');
                }
            });



        });
    </script>
    <?php
}
