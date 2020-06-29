<?php
add_filter('wpjam_posts_page_enable', '__return_true');

add_filter('wpjam_posts_tabs', function($tabs){
	$tabs['related-posts']	= [
		'title'			=>'相关文章',
		'function'		=>'option', 
		'option_name'	=>'wpjam-basic', 
		'tab_file'		=>WPJAM_BASIC_PLUGIN_DIR.'admin/pages/related-posts.php'
	];
	return $tabs;
});