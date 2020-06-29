<?php
add_filter('wpjam_basic_sub_pages',function($subs){
	$subs['wpjam-rewrites']	= [
		'menu_title'	=> 'Rewrites',
		'function'		=> 'tab',
		'page_file'		=> WPJAM_BASIC_PLUGIN_DIR.'admin/pages/wpjam-rewrites.php'
	];
	return $subs;
});


