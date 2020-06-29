<?php
add_filter($taxonomy.'_row_actions', function($actions, $term){
	$posts_per_page	= get_term_meta($term->term_id, 'posts_per_page', true);
	$posts_per_page	= $posts_per_page ? '（'.$posts_per_page.'）' : '';
	
	$actions['posts_per_page']	= str_replace('>文章数量<', '>文章数量'.$posts_per_page.'<', $actions['posts_per_page']);
	return $actions;
},10,2);

add_action('wpjam_'.$taxonomy.'_terms_actions', function($actions, $taxonomy){
	$actions['posts_per_page']	= ['title'=>'文章数量',	'page_title'=>'设置文章数量',	'submit_text'=>'设置',	'capability'=>get_taxonomy($taxonomy)->cap->edit_terms,	'tb_width'=>400];

	return $actions;
}, 9, 2);

add_filter('wpjam_'.$taxonomy.'_terms_list_action', function($result, $list_action, $term_id, $data){
	if($list_action != 'posts_per_page'){
		return $result;
	}

	$posts_per_page	= $data['posts_per_page'] ?? 0;

	if($posts_per_page){
		return update_term_meta($term_id, 'posts_per_page', $posts_per_page);
	}else{
		return delete_term_meta($term_id, 'posts_per_page');
	}
}, 10, 4);

add_filter('wpjam_'.$taxonomy.'_terms_fields', function($fields, $action_key, $term_id, $taxonomy){
	if($action_key == 'posts_per_page'){
		return [
			'default'			=> ['title'=>'默认数量',	'type'=>'view',		'value'=>wpjam_get_posts_per_page($taxonomy) ?: get_option('posts_per_page')],
			'posts_per_page'	=> ['title'=>'文章数量',	'type'=>'number',	'value'=>get_term_meta($term_id, 'posts_per_page', true),	'class'=>'']
		];
	}

	return $fields;
}, 10, 4);

add_filter('wpjam_term_options', function($term_options, $taxonomy){
	global $pagenow;

	if($pagenow == 'term.php'){
		$term_options['posts_per_page']	= ['title'=>'文章数量',	'type'=>'number',	'class'=>'',	'description'=>'页面显示文章数量'];
	}

	return $term_options;
},99,2);
