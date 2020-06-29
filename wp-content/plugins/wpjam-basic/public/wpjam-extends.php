<?php
if(wp_installing()){
	return;
}

include WPJAM_BASIC_PLUGIN_DIR.'public/wpjam-hooks.php';	// 基本优化
include WPJAM_BASIC_PLUGIN_DIR.'public/wpjam-cdn.php';		// CDN
include WPJAM_BASIC_PLUGIN_DIR.'public/wpjam-compat.php';	// 兼容代码 

if(is_admin()){
	include WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/admin-menus.php';
	include WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/hooks.php';

	add_action('wpjam_post_list_page_file', function($post_type){
		include WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/post-list.php';
	});

	add_action('wpjam_post_page_file', function($post_type){
		include WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/post.php';
	});

	add_action('wpjam_term_list_page_file', function($taxonomy){
		include WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/term-list.php';
	});
}

$wpjam_extends	= get_option('wpjam-extends');
$wpjam_extends	= $wpjam_extends ? array_filter($wpjam_extends) : [];

if(is_multisite()){
	$wpjam_sitewide_extends	= get_site_option('wpjam-extends');
	$wpjam_sitewide_extends	= $wpjam_sitewide_extends ? array_filter($wpjam_sitewide_extends) : [];
	
	if($wpjam_sitewide_extends){
		$wpjam_extends		= array_merge($wpjam_extends, $wpjam_sitewide_extends);
	}
}

if($wpjam_extends){
	if(isset($wpjam_extends['plugin_page'])){
		unset($wpjam_extends['plugin_page']);	
	}

	foreach (array_keys($wpjam_extends) as $wpjam_extend_file) {
		if(is_file(WPJAM_BASIC_PLUGIN_DIR.'extends/'.$wpjam_extend_file)){
			include WPJAM_BASIC_PLUGIN_DIR.'extends/'.$wpjam_extend_file;

			if(is_admin() && is_file(WPJAM_BASIC_PLUGIN_DIR.'admin/extends/'.$wpjam_extend_file)){
				include WPJAM_BASIC_PLUGIN_DIR.'admin/extends/'.$wpjam_extend_file;
			}
		}
	}
}

$template_extend_dir	= get_template_directory().'/extends';

if(is_dir($template_extend_dir)){
	if($extend_handle = opendir($template_extend_dir)) {   
		while (($extend = readdir($extend_handle)) !== false) {
			if ($extend == '.' || $extend == '..' || is_file($template_extend_dir.'/'.$extend)) {
				continue;
			}
			
			if(is_file($template_extend_dir.'/'.$extend.'/'.$extend.'.php')){
				include $template_extend_dir.'/'.$extend.'/'.$extend.'.php';
			}
		}   
		closedir($extend_handle);   
	}
}
	