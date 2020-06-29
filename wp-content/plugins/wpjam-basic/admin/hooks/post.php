<?php
if(wpjam_basic_get_setting('diable_block_editor')){
	add_filter('use_block_editor_for_post_type', '__return_false');
}

if(wpjam_basic_get_setting('disable_trackbacks')){
	add_action('post_comment_status_meta_box-options', function($post){
		?>
		<style type="text/css">
			label[for='ping_status']{display:none}
		</style>
		<?php
	});
}

// if(wpjam_basic_get_setting('diable_revision')){
//	add_action('wp_print_scripts',function() {
//		wp_deregister_script('autosave');
//	});
// }

add_filter('content_save_pre', function ($content){
	if(wpjam_image_remote_method() != 'download'){
		return $content;
	}

	if(!preg_match_all('/<img.*?src=\\\\[\'"](.*?)\\\\[\'"].*?>/i', $content, $matches)){
		return $content;
	}

	$update		= false;
	$search		= $replace	= [];
	$img_urls	= array_unique($matches[1]);

	$img_tags	= $matches[0];

	foreach($img_urls as $i => $img_url){
		if(empty($img_url)){
			continue;
		}

		if(preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $img_url, $img_match)){
			$file_name	= $img_match[0];
		}elseif(preg_match('/data-type=\\\\[\'"](jpe?g|jpe|gif|png)\\\\[\'"]/i', $img_tags[$i], $type_match)){
			$file_name = md5($img_url).'.'.$type_match[1];
		}else{
			continue;
		}

		if(!wpjam_is_remote_image($img_url)){
			continue;
		}

		// 例外
		if(!wpjam_image_remote_method($img_url)){
			continue;
		}

		$file_arr	= [
			'name'		=>$file_name,
			'tmp_name'	=>download_url($img_url)
		];

		if(!is_wp_error($file_arr['tmp_name'])){
			
			$upload_file	= wp_handle_sideload($file_arr, ['test_form' => false]);

			if(!isset($upload_file['error'])){
				$search[]	= $img_url;
				$replace[]	= $upload_file['url'];
				$update		= true;
			}
		}
	}

	if($update){
		if(is_multisite()){
			setcookie('wp-saving-post', $_POST['post_ID'].'-saved', time()+DAY_IN_SECONDS, ADMIN_COOKIE_PATH, false, is_ssl());	
		}

		$content	= str_replace($search, $replace, $content);
	}

	return $content;
});