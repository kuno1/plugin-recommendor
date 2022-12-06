<?php
/*
Plugin Name: Plugin Recommender
Plugin URI: https://kunoichiwp.com
Description: Plugin recommendation.
Version: 1.0.0
Author: Kunoichi INC
Author URI: https://tarosky.co.jp
Text Domain: tsmap
Domain Path: /languages
License: GPL3 or Later
*/

/**
 * @package tsmap
 */

// Do not load directory.
defined( 'ABSPATH' ) || die();


require_once __DIR__ . '/vendor/autoload.php';

\Kunoichi\PluginRecommender::load( __DIR__ . '/tests/recommendations.json' );
