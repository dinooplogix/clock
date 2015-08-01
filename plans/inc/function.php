<?php

function dlsplans_get_display_content($post_id) {
    $post_meta = get_post_meta($post_id);

    $other_values_count = $post_meta['values'][0];
    $other_values = array();
    for ($i = 0; $i < $other_values_count; $i++) {
        $other_values[] = $post_meta['values_' . $i . '_value'][0];
    }


    $return = array(
        'title' => $post_meta['title'][0],
        'price' => $post_meta['price'][0],
        'other_values' => $other_values,
    );
    return $return;
}

function dlsplans_get_list_class($key) {
    if ($key % 2 == 0) {
        return '';
    }
    return 'color';
}

function dlsplans_get_tabs($tabstrings) {
    $tabs = explode('-', $tabstrings);
    $tab_count = count($tabs);
    $return_tabs = array();

    foreach ($tabs as $key => $value) {
        $return_tabs['#tab-' . ++$key] = $value;
    }

    return $return_tabs;
}

function dlsplans_get_postid_bytab($post_ids_string) {
    //$post_ids_string = '1,2,3-4,5,6';
    $post_batch_strings = explode('-', $post_ids_string);
    $post_batch = array();
    foreach ($post_batch_strings as $key => $values) {
        $post_batch['tab-' . ++$key] = explode(',', $values);
    }
    return $post_batch;
}
