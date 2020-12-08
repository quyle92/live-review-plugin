<?php
/**
 * Plugin Name: A Live Review
 * Description: A Live Review.
 * Version: 1.0.4
 */
if (! defined( 'ABSPATH' )) die;

define( 'LIVE_REVIEW_VERSION', '1.0.0' );
define( 'LIVE_REVIEW_FILE', __FILE__ );
define( 'LIVE_REVIEW_NAME', basename(LIVE_REVIEW_FILE) );
define( 'LIVE_REVIEW_BASE_NAME', plugin_basename( LIVE_REVIEW_FILE ));
define( 'LIVE_REVIEW_PATH' , plugin_dir_path( LIVE_REVIEW_FILE ));
define( 'LIVE_REVIEW_URL', plugin_dir_url( LIVE_REVIEW_FILE ));
define( 'LIVE_REVIEW_ASSETS_URL', LIVE_REVIEW_URL . 'assets/' );

require_once LIVE_REVIEW_PATH . '/inc/class-live-review.php';
LiveReview::instance();