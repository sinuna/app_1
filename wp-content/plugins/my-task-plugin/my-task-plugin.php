<?php
/**
 * Plugin Name:       My Task Plugin
 * Description:       To track the listed task
 * Version:           1.0.0
 * Author:            Cnuna
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once __DIR__ . '/vendor/autoload.php';
 
use MyTaskPlugin\MyTask;
 
new MyTask(); // Instantiate to trigger the message display
