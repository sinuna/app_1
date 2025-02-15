<?php

namespace MyTask;

class MetaBoxHandler {
    public function __construct() {
        add_action('add_meta_boxes', array( $this, 'add_mymeta_boxes'));
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
    ?>
        <label for="highlight">
            <input type="checkbox" id="highlight" name="highlight" value="1">
            Highlight
        </label>
    <?php
    }
}
