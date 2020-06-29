<?php
do_action('wpjam_comment_list_page_file');

add_filter('comment_row_actions',function ($actions, $comment){
	if(in_array($comment->comment_type,['like','fav'])){
		$actions	= [];
	}

	$actions['comment_id'] = 'ID：'.$comment->comment_ID;

	return $actions;
}, 10, 2);

add_filter('comment_text', function($comment_text, $comment=null){
	$label	= '';

	if($comment){
		if($comment->comment_type == 'like'){
			$label	= '点赞';
		}elseif($comment->comment_type == 'fav'){
			$label	= '收藏';
		}
	}

	$label	= $label ? '<strong>'.$label.'</strong>' : '';

	return $label . $comment_text;	
}, 10, 2);

add_filter('comment_author', function($author, $comment_id){
	global $pagenow;

	if($pagenow == 'edit-comments.php'){
		$comment	= get_comment($comment_id);

		if($comment->user_id){
			return	'<a href="'.admin_url('edit-comments.php?user_id='.$comment->user_id).'">'.$author.'</a>';
		}else{
			return	'<a href="'.admin_url('edit-comments.php?author_email='.urlencode($comment->comment_author_email)).'">'.$author.'</a>';
		}
	}

	return $author;
}, 99, 2);

add_filter('comments_list_table_query_args', function($args){
	if(!empty($_REQUEST['author_email'])){
		$args['author_email']	= $_REQUEST['author_email'];
	}

	return $args;
});



