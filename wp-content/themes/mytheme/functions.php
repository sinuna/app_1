<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

use MyTheme\MyThemeSetup;

// Instantiate the class
new MythemeSetup();
