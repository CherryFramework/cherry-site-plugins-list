<?php
/**
 * Template part for displaying plugins listing
 */

/**
 * Hook fires before item wrapper started.
 */
do_action( 'cherry_site_plugins_before_item', 'list-item' );
?>
<div class="plugins-list-item">
	<h4 class="plugins-list-item__title"><?php echo $plugin->name; ?></h4>
	<div class="plugins-list-item__thumb"><?php
		echo $this->plugin_thumb( $plugin, 'banner-772x250', 'plugins-list-item__thumb-image' );
	?></div>
	<div class="plugins-list-item__ver"><?php echo $plugin->version; ?></div>
	<div class="plugins-list-item__content"><?php echo $plugin->short_description; ?></div>
	<div class="plugins-list-item__rating" data-rating="<?php echo $plugin->rating; ?>"></div>
	<div class="plugins-list-item__downloads"><?php echo $plugin->downloaded; ?></div>
	<div class="plugins-list-item__actions">
		<a href=""<?php echo $plugin->download_link; ?> class="plugins-list-item__link"><?php
			echo esc_html( $atts['download_text'] );
		?></a>
	</div>
</div>
<?php
/**
 * Hook fires after item wrapper ended.
 */
do_action( 'cherry_site_plugins_after_item', 'list-item' );
