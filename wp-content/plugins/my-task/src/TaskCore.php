<?php

namespace MyTask;

use MyTask\TaskPostType;
use MyTask\TaxonomyHandler;
use MyTask\MetaBoxHandler;

class TaskCore {
    public function __construct() {
        $this->init(); // Initialize all plugin components
    }

    private function init() {
        // Instantiate and initialize components
        new TaskPostType();
        new TaxonomyHandler();
        new MetaBoxHandler();
    }
}
