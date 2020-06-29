<?php
add_action('wpjam_post_list_page_file', function($post_type){
	if($post_type != 'attachment'){
		include WPJAM_BASIC_PLUGIN_DIR.'admin/hooks/quick-excerpt.php';	
	}
});