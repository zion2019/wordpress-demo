<?php
/**
 * The sidebar containing the Shop widget area.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Startapp
 */

if ( ! is_active_sidebar( 'sidebar-shop' ) ) {
	return;
}

?>

<aside class="sidebar" role="complementary">
	<?php dynamic_sidebar( 'sidebar-shop' ); ?>
</aside>
