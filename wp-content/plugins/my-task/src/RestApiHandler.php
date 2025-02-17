<?php

namespace MyTask;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class RestApiHandler {
    public function __construct() {
        add_action('rest_api_init', array( $this, 'register_custom_task_route_in_rest_api'));
        add_action('rest_api_init', array( $this, 'expose_task_in_restapi'));
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

    public function expose_task_in_restapi() {
        register_rest_field('task', 'my_taxonomies', [
            'get_callback' => array($this, 'display_task_taxonomy_term_in_restapi')
        ]);
    }

    public function display_task_taxonomy_term_in_restapi( $object ) {
        $taxonomies = get_object_taxonomies('task');
        $result = [];

        foreach( $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms($object['id'], $taxonomy);
            $result[$taxonomy] = [];

            if ( !is_wp_error($terms) && !empty($terms) ) {
                foreach ($terms as $term) {
                    $result[$taxonomy][] = [
                        'term_id' => $term->term_id,
                        'term_name' => $term->name,
                        'term_slug' => $term->slug
                    ];
                }
            }
        }

        return $result;
    }
}
