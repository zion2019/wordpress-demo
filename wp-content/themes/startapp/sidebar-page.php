<?php
/**
 * The sidebar containing the page widget area.
 *
 * @author 8Guild
 */

if ( ! is_active_sidebar( 'sidebar-page' ) ) {
	return;
}

?>

<aside class="sidebar" role="complementary">
	<?php dynamic_sidebar( 'sidebar-page' ); ?>
</aside>
