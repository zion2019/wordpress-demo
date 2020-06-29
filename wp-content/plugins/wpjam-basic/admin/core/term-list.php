<?php
add_filter('term_updated_messages', function($messages){
	global $taxonomy;

	if($taxonomy == 'post_tag' || $taxonomy == 'category'){
		return $messages;
	}

	$labels		= get_taxonomy_labels(get_taxonomy($taxonomy));
	$label_name	= $labels->name;

	$messages[$taxonomy]	= array_map(function($message) use ($label_name){
		if($message == $label_name) return $message;

		return str_replace(
			['项目', 'Item'], 
			[$label_name, ucfirst($label_name)], 
			$message
		);
	}, $messages['_item']);

	return $messages;
});

add_filter('taxonomy_parent_dropdown_args', function($args, $taxonomy, $action_type){
	$tax_obj	= get_taxonomy($taxonomy);
	$levels		= $tax_obj->levels ?? 0;

	if($levels > 1){
		$args['depth']	= $levels - 1;

		if($action_type == 'edit'){
			$term_id		= $args['exclude_tree'];
			$term_levels	= count(get_ancestors($term_id, $taxonomy, 'taxonomy'));
			$child_levels	= $term_levels;

			$children	= get_term_children($term_id, $taxonomy);
			if($children){
				$child_levels = 0;

				foreach($children as $child){
					$new_child_levels	= count(get_ancestors($child, $taxonomy, 'taxonomy'));
					if($child_levels	< $new_child_levels){
						$child_levels	= $new_child_levels;
					}
				}
			}

			$redueced	= $child_levels - $term_levels;

			if($redueced < $args['depth']){
				$args['depth']	-= $redueced;
			}else{
				$args['parent']	= -1;
			}
		}
	}

	return $args;
}, 10, 3);

global $wpjam_list_table;

do_action('wpjam_term_list_page_file', $taxonomy);

if(empty($wpjam_list_table)){
	$wpjam_list_table	= new WPJAM_Terms_List_Table([
		'taxonomy'	=> $taxonomy
	]);
}