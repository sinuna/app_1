<?php

namespace MyTaskPlugin;

class MyTask {
    public function __construct() {
        add_action('wp_footer', array( $this, 'activate'));
    }

    public function activate() {
        echo '<p style="text-align:center;font-weight:bold;">Composer is installed and Plugin is activated!</p>';
    }
}
