<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Startapp
 */

/**
 * Fires right before the footer
 *
 * @see startapp_scroll_to_top() 10
 * @see startapp_footer_backdrop() 100
 */
do_action( 'startapp_footer_before' );

?>
<footer class="<?php startapp_footer_class(); ?>" <?php startapp_footer_attr(); ?>>
	<?php get_template_part( 'template-parts/footers/footer', startapp_footer_layout() ); ?>
	<div class="<?php startapp_copyright_class(); ?>" <?php startapp_copyright_attr(); ?>>
		<div class="<?php startapp_footer_fullwidth_class(); ?>">
			<div class="row">
				<div class="col-sm-6">
					<?php startapp_footer_copyright(); ?>
				</div>
				<div class="col-sm-6 text-right">
					<?php startapp_footer_menu(); ?>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php

/**
 * Fires right after the closing <footer>
 *
 * @see startapp_photoswipe() 10
 * @see startapp_close_page_wrap() 999
 */
do_action( 'startapp_footer_after' );

wp_footer(); ?>
<script>
(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https') {
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
    }
    else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();
</script>

</body>
</html>
