<?php

namespace MyTask;

use MyTask\TaskPostType;

class TaskCore {
    public function __construct() {
        $this->init(); // Initialize all plugin components
    }

    private function init() {
        // Instantiate and initialize components
        new TaskPostType();
    }
}
