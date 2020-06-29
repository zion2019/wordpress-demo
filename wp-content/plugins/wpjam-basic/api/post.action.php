<?php
$comment_data	= [];

$post_id	= wpjam_get_parameter('post_id', ['method'=>'POST', 'type'=>'int', 'required'=>true]);
$the_post	= wpjam_validate_post($post_id, $post_type, $action);
if(is_wp_error($the_post)){
	wpjam_send_json($the_post);
}

$comment_data['post_id']	= $post_id;

if(is_user_logged_in()){
	$comment_data['user_id']	= get_current_user_id();
}else{
	if(get_option('comment_registration')){
		wpjam_send_json([
			'errcode'	=>'not_logged_in', 
			'errmsg'	=>'必须要登录之后才能操作'
		]);
	}

	$wpjam_user	= wpjam_get_current_user();

	if(empty($wpjam_user['user_email'])){
		wpjam_send_json([
			'errcode'	=>'bad_authentication', 
			'errmsg'	=>'无权限'
		]);
	}

	$comment_data['user_email']	= $wpjam_user['user_email'];
	$comment_data['nickname']	= $wpjam_user['nickname'] ?? '';
}

if($action == 'comment'){
	$comment_data['comment']	= wpjam_get_parameter('comment',	['method'=>'POST', 'required'=>true]);
	$comment_data['parent']		= wpjam_get_parameter('parent',		['method'=>'POST', 'default'=>0]);

	$comment_id	= WPJAM_Comment::insert($comment_data);
}else{
	$comment_id	= WPJAM_Comment::action($comment_data, $action);
}

if(is_wp_error($comment_id)){
	wpjam_send_json($comment_id);
}

$response['user']	= WPJAM_Comment::get_author($comment_id);