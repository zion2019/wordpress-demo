<?php
$comment_id	= wpjam_get_parameter('id',	['method'=>'POST', 'type'=>'int', 'required'=>true]);
$comment	= get_comment($comment_id);

if(empty($comment)){
	wpjam_send_json([
		'errcode'	=>'comment_not_exists', 
		'errmsg'	=>'评论不存在。'
	]);
}

$post_id	= $comment->comment_post_ID;
$action		= str_replace('.delete', '', $action);
$the_post	= wpjam_validate_post($post_id, $post_type, $action);
if(is_wp_error($the_post)){
	wpjam_send_json($the_post);
}

if(is_user_logged_in()){
	if($comment->user_id != get_current_user_id() && !current_user_can('manage_options')){
		wpjam_send_json([
			'errcode'	=>'bad_authentication', 
			'errmsg'	=>'你不能删除别人的评论。'
		]);
	}
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

	if($comment->comment_author_email != $wpjam_user['user_email']){
		wpjam_send_json([
			'errcode'	=>'bad_authentication', 
			'errmsg'	=>'你不能删除别人的评论。'
		]);
	}
}

$result	= WPAJAM_Comment::delete($comment_id);
if(is_wp_error($result)){
	wpjam_send_json($result);
}

$response['errmsg']	= '删除成功';