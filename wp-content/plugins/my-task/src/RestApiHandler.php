<?php

namespace MyTask;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class RestApiHandler {
    public function __construct() {
        add_action('rest_api_init', array( $this, 'register_custom_task_route_in_rest_api' ));
        add_action('rest_api_init', array( $this, 'expose_task_in_restapi' ));
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

        register_rest_route('custom/v1', '/task', array(
            'methods' => 'GET',
            'callback' => array( $this, 'get_filtered_tasks' ),
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

    public function get_filtered_tasks( WP_REST_Request $request ) { //to return filtered tasks
        $args = array(
            'post_type' => 'task',
            'posts_per_page' => -1,
            'tax_query' => $this->build_tax_query( $request ), // Add tax_query to filter tasks
        );

        $query = new \WP_Query( $args );
        $tasks = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $task_id = get_the_ID();
                $tasks[] = array(
                    'id' => $task_id,
                    'title' => get_the_title(),
                    'my_meta' => $this->get_task_meta( $task_id ),
                    'my_taxonomies' => $this->get_task_taxonomies_terms( $task_id ),
                );
            }
            wp_reset_postdata();
            return new WP_REST_Response( $tasks, 200 );
        }

        return new WP_REST_Response( array( 'message' => 'No tasks found' ), 404 );
    }

    public function get_task_taxonomies_terms( $task_id ) {
        $taxonomies = get_object_taxonomies( 'task' );
        $my_taxonomies = array();

        foreach ( $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $task_id, $taxonomy );

            if ( !is_wp_error( $terms ) && !empty( $terms ) ) {
                $my_taxonomies[$taxonomy] = [];
                foreach ( $terms as $term ) {
                    $my_taxonomies[$taxonomy][] = array(
                        'term_id'   => $term->term_id,
                        'term_name' => $term->name,
                        'term_slug' => $term->slug,
                    );
                }
            } else {
                $my_taxonomies[$taxonomy] = array(); // Ensure empty taxonomy keys exist
            }
        }

        return $my_taxonomies;
    }

    public function get_task_meta( $task_id ) {
        return array(
            'deadline' => get_post_meta( $task_id, '_deadline', true ) ?: null,
            'highlight_post' => get_post_meta( $task_id, '_is_highlight', true ) ?: null,
        );
    }

    public function build_tax_query( WP_REST_Request $request ) {
        $taxonomies = get_object_taxonomies('task');
        $tax_query = [];

        foreach ( $taxonomies as $taxonomy ) {
            $terms = $request->get_param($taxonomy);

            if (!empty($terms)) {
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => explode(',', $terms), // Multiple terms
                    'operator' => 'IN',
                ];
            }
        }

        return $tax_query;
    }

    // exposing custom taxonomies and meta in default restapi i.e wp/v2/task
    public function expose_task_in_restapi() {
        register_rest_field( 'task', 'my_taxonomies', array(
            'get_callback' => array( $this, 'display_task_taxonomy_term_in_restapi' ),
        ) );

        register_rest_field( 'task', 'my_meta', array(
            'get_callback' => array( $this, 'display_task_meta_in_restapi' ),
        ) );
    }

    public function display_task_taxonomy_term_in_restapi( $object ) {
        return $this->get_task_taxonomies_terms( $object['id'] );
    }

    public function display_task_meta_in_restapi( $object ) {
        return $this->get_task_meta( $object['id'] );
    }
}
