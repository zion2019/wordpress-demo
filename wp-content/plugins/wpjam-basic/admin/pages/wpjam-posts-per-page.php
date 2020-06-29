<?php
add_filter('wpjam_posts_per_page_setting', function(){
	$post_types = get_post_types(['public'=>true],'objects');

	unset($post_types['page']);
	unset($post_types['attachment']);

	$post_type_options	= wp_list_pluck($post_types, 'label');

	$fields	= [];

	$fields['posts_per_page']	= ['title'=>'全局数量',	'type'=>'number',	'class'=>'',	'value'=>get_option('posts_per_page'),	'description'=>'博客中全局设置的文章列表数量'];

	if(count($post_type_options) > 1){
		$fields['feed_set']	= ['title'=>'Feed页',	'type'=>'fieldset',	'fields'=>[
			'feed_post_types'	=> ['title'=>'文章类型',	'type'=>'checkbox',	'value'=>['post'],	'options'=>$post_type_options],
			'posts_per_rss'		=> ['title'=>'显示数量',	'type'=>'number',	'class'=>'',		'value'=>get_option('posts_per_rss'),'description'=>'Feed中显示最近文章列表数'],
		]];
	}else{
		$fields['posts_per_rss']= ['title'=>'Feed数量',	'type'=>'number',	'class'=>'',	'value'=>get_option('posts_per_rss'),	'description'=>'Feed中显示最近文章列表数'];
	}

	foreach(['home'=>'首页','author'=>'作者页','search'=>'搜索页','archive'=>'存档页'] as $page_key=>$page_name){
		if(count($post_type_options) > 1){
			$fields[$page_key.'_set']	= ['title'=>$page_name,	'type'=>'fieldset',	'fields'=>[
				$page_key.'_post_types'	=> ['title'=>'文章类型',	'type'=>'checkbox',	'value'=>['post'],	'options'=>$post_type_options],
				$page_key				=> ['title'=>'显示数量',	'type'=>'number',	'class'=>''],
			]];
		}else{
			$fields[$page_key]	= ['title'=>$page_name,	'type'=>'number',	'class'=>''];
		}
	}

	$taxonomies = get_taxonomies(['public'=>true,'show_ui'=>true],'objects');

	if(isset($taxonomies['series'])){
		unset($taxonomies['series']);	
	}
	
	if($taxonomies){
		$taxonomies	= wp_list_sort($taxonomies, 'hierarchical', 'DESC', true);
		foreach ($taxonomies as $taxonomy=>$taxonomy_obj) {
			$sub_fields	= [];

			$taxonomy_post_type_options	= $taxonomy_obj->object_type ? wp_array_slice_assoc($post_type_options, $taxonomy_obj->object_type) : [];

			if(count($taxonomy_post_type_options) > 1){
				$sub_fields[$taxonomy.'_post_types']	= ['title'=>'文章类型',	'type'=>'checkbox',	'value'=>['post'],	'options'=>$taxonomy_post_type_options];
				$sub_fields[$taxonomy]					= ['title'=>'文章数量',	'type'=>'number',	'class'=>''];
			}else{
				$sub_fields[$taxonomy]					= ['title'=>'',	'type'=>'number',	'class'=>''];
			}

			if($taxonomy_obj->hierarchical){
				$sub_fields[$taxonomy.'_individual']	= ['title'=>'',	'type'=>'checkbox',	'description'=>'每个'.$taxonomy_obj->label.'可独立设置数量'];
			}

			$fields[$taxonomy.'_set']	= ['title'=>$taxonomy_obj->label,	'type'=>'fieldset',	'fields'=>$sub_fields];
		}
	}
	
	$post_types = get_post_types(['public'=>true, 'has_archive'=>true],'objects');

	if($post_types){
		$sub_fields = [];
		foreach ($post_types as $post_type=>$pt_obj) {
			$sub_fields[$post_type]	= ['title'=>$pt_obj->label,	'type'=>'number',	'class'=>''];
		}

		if(count($post_types) == 1){
			$field	= $sub_fields[$post_type];
			$field['title']		.= '存档页';
			$fields[$post_type]	= $field;
		}else{
			$fields['post_type']	= ['title'=>'文章类型存档页',	'type'=>'fieldset',	'fields'=>$sub_fields];
		}
	}

	$summary	= '设置不同页面不同的文章列表数量，也可开启不同的分类不同文章列表数量，空或者为0则使用全局设置。';

	return compact('fields', 'summary');	
});

add_filter('pre_update_option_wpjam-posts-per-page', function($value){
	foreach (['posts_per_page', 'posts_per_rss'] as $option_name) {
		if(isset($value[$option_name])){
			if($value[$option_name]){
				update_option($option_name, $value[$option_name]);
			}
			
			unset($value[$option_name]);
		}
	}

	return $value;
});

add_filter('option_wpjam-posts-per-page', function($value){
	$value	= $value ?: [];

	$value['posts_per_page']	= get_option('posts_per_page');
	$value['posts_per_rss']		= get_option('posts_per_rss');

	return array_filter($value);
});