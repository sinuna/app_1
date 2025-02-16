<?php

namespace MyTask;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class RestApiHandler {
    public function __construct() {
        add_action('rest_api_init', array( $this, 'register_custom_task_route_in_rest_api'));
    }

    public function register_custom_task_route_in_rest_api() {
        register_rest_route('custom/v1', '/(?P<cpt>[a-zA-Z0-9_-]+)/(?P<taxonomy>[a-zA-Z0-9_-]+)', array( // eg. websiteurl/wp-json/wp/v2/task/task_status
            'methods' => 'GET',
            'callback' => array($this, 'get_task_taxonomy_terms'),
            'args' => array(
                'cpt' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return is_string($param);
                    }
                ),
                'taxonomy' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return is_string($param);
                    }
                ),
            ),
        ));
    }

    public function get_task_taxonomy_terms( $data ) {
        $cpt = $data['cpt'];
        $taxonomy = $data['taxonomy'];

        if ( !post_type_exists($cpt) || !taxonomy_exists($taxonomy) ) {
            return new WP_Error('invalid_cpt_or_taxonomy', 'Invalid Custom Post Type or Taxonomy', array('status' => 404));
        }

        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false, // Get all terms, even empty ones
            'orderby' => 'term_id', // Order terms by term_id
            'order' => 'ASC',
        ));
        if (is_wp_error($terms)) {
            return new WP_Error('no_terms', 'No terms found', array('status' => 404));
        }
        // Format the response
        $formatted_terms = array_map(function ($term) {
            return array(
                'term_id'   => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }, $terms);
        return new WP_REST_Response($formatted_terms, 200);
    }
}
