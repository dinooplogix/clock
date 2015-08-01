<?php
/**
 * 
 * @param string $type This can be success, warning, danger, info, or primary 
 * @param string $message The content to display as a message
 * @param boolean $echothis
 * @return string A HTML content
 */
function cf_display_front_messages($type = null, $message = null, $echothis = null) {
    if ($echothis != null) {
        //Rapid message display
        ?>
        <div class="alert bg-<?php echo $type; ?>">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <?php echo $message; ?>
        </div>
        <?php
        return;
    }
    //$type can be success, warning, danger, info, primary
    if ($type == null && $message == null) {
        // Show last saved message
        $message = get_option('cf_message');
        $type = get_option('cf_message_type');
        if ($message !== '') {
            ?>
            <div class="alert bg-<?php echo $type; ?>">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <?php echo $message; ?>
            </div>
            <?php
        }
        //Erase message after show it
        update_option('cf_message', '');
        update_option('cf_message_type', '');
    } else {
        //You have new message, save it...
        update_option('cf_message_type', $type);
        update_option('cf_message', $message);
    }
}


function create_page_and_insert_shortcode($page_title, $shortcode = NULL) {
    
    $page = get_page_by_title($page_title);
    
    if ($page->post_status != null && $page->post_status == 'trash') {
        $update_post = array(
            'ID' => $page->ID,
            'post_status' => 'publish',
        );
        wp_update_post($update_post);
        return true;
    }
    
    if($shortcode == NULL){
        $shortcode = $page_title;
    }
    
    if ($page->ID == NULL) {
        $postarr = array(
            'post_content' => '[' . $shortcode . ']',
            'post_title' => $page_title,
            'post_status' => 'publish',
            'post_type' => 'page'
        );
        wp_insert_post($postarr);
    }
    
}


