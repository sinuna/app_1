<?php

namespace MyTask;

class MetaBoxHandler {
    public function __construct() {
        add_action('add_meta_boxes', array( $this, 'add_mymeta_boxes'));
        add_action('save_post', array( $this, 'save_highlight_meta_box'), 10, 2);
    }

    public function add_mymeta_boxes() {
        add_meta_box(
            'highlight_meta_box',
            'Highlight Post',
            array($this, 'render_highlight_meta_box'), 
            'task',
            'side',
            'high'
        );
    }

    public function render_highlight_meta_box( $post ) {
        $is_highlight = get_post_meta($post->ID, '_is_highlight', true);
        $is_highlight = ( empty($is_highlight) ) ? '0' : $is_highlight;
        ?>
        <label for="highlight">
            <input type="checkbox" id="highlight-checkbox" name="highlight-checkbox" value="1" <?php checked($is_highlight, '1');?>>
            Highlight
        </label>
        <?php
    }

    public function save_highlight_meta_box( $post_id ) {
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;

        if ( isset( $_POST['highlight-checkbox']) ) {
            update_post_meta( $post_id, '_is_highlight', '1'); // Set to '1' if checked
        } else {
            update_post_meta( $post_id, '_is_highlight', '0' ); // Set to '0' if unchecked
        }
    }
}
