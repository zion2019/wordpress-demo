<?php
add_filter('wpjam_basic_sub_pages',function($subs){
	$subs['wpjam-shortcodes']	= [
		'menu_title'	=> '短代码',
		'function'		=> 'list',
		'page_file'		=> WPJAM_BASIC_PLUGIN_DIR.'admin/pages/wpjam-shortcodes.php'
	];
	return $subs;
});


