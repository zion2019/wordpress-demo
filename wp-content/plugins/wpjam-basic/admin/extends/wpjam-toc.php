<?php
add_filter('wpjam_posts_page_enable', '__return_true');

add_filter('wpjam_posts_tabs', function($tabs){
	$tabs['toc']	= [
		'title'			=>'文章目录',
		'function'		=>'option', 
		'option_name'	=>'wpjam-basic', 
		'tab_file'		=>WPJAM_BASIC_PLUGIN_DIR.'admin/pages/wpjam-toc.php'
	];
	
	return $tabs;
});

add_action('wpjam_post_page_file', 	function($post_type){
	include WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/post-toc.php';
});