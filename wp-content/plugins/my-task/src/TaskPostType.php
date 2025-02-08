<?php

namespace MyTask;

class TaskPostType {
    public function __construct() {
        add_action( 'init', array( $this, 'create_task_post_type' ) );
    }

    public function create_task_post_type() {
        $args = array(
            'labels' => array(
                'name' => 'Task',
                'singular_name' => 'task'
            ),
            'public' => true,
            'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'has_archive' => true,
            'show_in_rest' => true,
        );

        register_post_type('task', $args);
    }
}
