<?php
add_filter('wpjam_posts_page_enable', '__return_true');

add_filter('wpjam_posts_tabs', function($tabs){
	$tabs['posts-per-page']	= [
		'title'			=>'文章数量',
		'function'		=>'option', 
		'option_name'	=>'wpjam-posts-per-page', 
		'tab_file'		=>WPJAM_BASIC_PLUGIN_DIR.'admin/pages/wpjam-posts-per-page.php'
	];
	
	return $tabs;
});

add_action('wpjam_term_list_page_file', function($taxonomy){
	if(is_taxonomy_hierarchical($taxonomy) && wpjam_get_posts_per_page($taxonomy.'_individual')){
		require WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/term-posts-per-page.php';	
	}
});