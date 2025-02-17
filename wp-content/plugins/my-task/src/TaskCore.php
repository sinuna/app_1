<?php

namespace MyTask;

use MyTask\TaskPostType;
use MyTask\TaxonomyHandler;
use MyTask\MetaBoxHandler;
use MyTask\RestApiHandler;

class TaskCore {
    public function __construct() {
        $this->init(); // Initialize all plugin components
    }

    private function init() {
        // Instantiate and initialize components
        new TaskPostType();
        new TaxonomyHandler();
        new MetaBoxHandler();
        new RestApiHandler();
    }
}
