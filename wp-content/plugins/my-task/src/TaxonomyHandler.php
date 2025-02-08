<?php

namespace MyTask;

class TaxonomyHandler {
    public function __construct() {
        add_action( 'init', array( $this, 'create_task_taxonomies' ));
    }

    public function create_task_taxonomies() {
        $taxonomies = [
            'task_category' => 'Category',
            'task_priority' => 'Priority',
            'task_status' => 'Status',
        ];

        foreach ($taxonomies as $taxonomy => $label) {
            register_taxonomy($taxonomy, 'task', [
                'labels' => ['name' => $label],
                'public' => true,
                'hierarchical' => true,
                'show_in_rest' => true,
            ]);
        }
    }
}
