<?php
add_action('wpjam_post_list_page_file', function($post_type){
	if($post_type != 'attachment' && is_post_type_viewable($post_type)){
		include WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/post-duplicate.php';
	}
},1);