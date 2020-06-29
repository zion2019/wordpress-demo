<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Startapp
 */

if ( ! is_active_sidebar( 'sidebar-blog' ) ) {
	return;
}

?>

<aside class="sidebar" role="complementary">
	<?php dynamic_sidebar( 'sidebar-blog' ); ?>
</aside>