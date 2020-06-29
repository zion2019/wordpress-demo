<?php
do_action('wpjam_user_list_page_file');

add_filter('user_row_actions',	function($actions, $user){
	$actions['user_id'] = 'ID: '.$user->ID;	
	return $actions;
}, 999, 2);

// 后台可以根据显示的名字来搜索用户 
add_filter('user_search_columns',function($search_columns){
	return ['ID', 'user_login', 'user_email', 'user_url', 'user_nicename', 'display_name'];
});