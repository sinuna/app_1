<?php

namespace MyTask;

class MetaBoxHandler {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_mymeta_boxes'));
        add_action('save_post', array($this, 'save_highlight_meta_box'), 10, 2);
        add_action('save_post', array($this, 'save_deadline_meta_box'), 10, 2);
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

        add_meta_box(
            'deadline_metabox',
            'Deadline',
            array($this, 'render_deadline_meta_box'),
            'task',
            'side',
            'high'
        );
    }

    public function render_highlight_meta_box($post) {
        $is_highlight = get_post_meta($post->ID, '_is_highlight', true);
        wp_nonce_field('save_highlight', 'highlight_nonce'); // Nonce Field
        ?>
        <label for="highlight">
            <input type="checkbox" id="highlight-checkbox" name="highlight-checkbox" value="1" <?php checked($is_highlight, '1'); ?>>
            Highlight
        </label>
        <?php
    }

    public function save_highlight_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        if (!isset($_POST['highlight_nonce']) || !wp_verify_nonce($_POST['highlight_nonce'], 'save_highlight')) return;

        $is_highlighted = isset($_POST['highlight-checkbox']) ? '1' : '0'; //Set to '1' if checked otherwise '0'
        update_post_meta($post_id, '_is_highlight', $is_highlighted);
    }

    public function render_deadline_meta_box($post) {
        $deadline = get_post_meta($post->ID, '_deadline', true);
        wp_nonce_field('save_deadline', 'deadline_nonce'); // Nonce Field
        ?>
        <label for="deadline">Deadline</label>
        <input type="date" name="deadline" id="deadline" value="<?php echo esc_attr($deadline); ?>">
        <?php
    }

    public function save_deadline_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        if (!isset($_POST['deadline_nonce']) || !wp_verify_nonce($_POST['deadline_nonce'], 'save_deadline')) return;

        if (isset($_POST['deadline'])) {
            update_post_meta($post_id, '_deadline', sanitize_text_field($_POST['deadline']));
        }
    }
}
