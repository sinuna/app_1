<?php

namespace MyTheme;

class MyThemeSetup {

    public function __construct() {
        add_action('after_setup_theme', array($this, 'default_setup_for_mytheme'));
        add_action('wp_enqueue_scripts', array($this, 'mytheme_enqueue_styles'));    
    }

    public function default_setup_for_mytheme() {
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'mytheme'),
        ));

        add_theme_support( 'post-thumbnails' );
    }

    public function mytheme_enqueue_styles() {
        wp_enqueue_style('font-awesome-style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css', array(), '1.0.0');
        wp_enqueue_style('bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '1.0.0');
        wp_enqueue_style('mytheme-style', get_template_directory_uri() . '/assets/scss/style.css', array(), '1.0.0');

        wp_enqueue_script('jquery-script', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('popper-script', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('bootstrap-script', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('vue-script', 'https://cdnjs.cloudflare.com/ajax/libs/vue/3.5.13/vue.global.js', array('jquery'), '1.0', true);
        wp_enqueue_script('mytheme-script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), '1.0', true);

        wp_localize_script('mytheme-script', 'ajax_object', array(
            'rest_url' => esc_url(rest_url()),
        ));
    }
}
