<?php
/**
 * Template part for displaying plugins listing
 */

do_action( 'cherry_site_plugins_before_listing', 'list' );

foreach ( $plugins as $plugin ) {
	include $template_item;
}

do_action( 'cherry_site_plugins_after_listing', 'list' );
