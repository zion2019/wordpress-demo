<?php 
if(!class_exists('WPJAM_List_Table')){
	include WPJAM_BASIC_PLUGIN_DIR.'admin/includes/class-wpjam-list-table.php';
}

class WPJAM_Posts_List_Table extends WPJAM_List_Table{
	public function __construct($args = []){
		$args	= wp_parse_args($args, [
			'model'			=> '',
			'post_type'		=> '',
			'search_metas'	=> []
		]);

		$post_type	= $args['post_type'];
		$model		= $args['model'];
		$actions	= $model ? $model::get_actions() : [];
		$actions	= apply_filters('wpjam_'.$post_type.'_posts_actions', $actions, $post_type);

		$pt_obj		= get_post_type_object($post_type);

		$args['capability']		= $args['capability'] ?? 'edit_post';
		$args['bulk_capability']= $args['bulk_capability'] ?? $pt_obj->cap->edit_others_posts;

		$args['bulk_actions']	= [];
		if($actions){
			foreach ($actions as $action_key => $action) {
				if(empty($action['bulk'])) {
					continue;
				}

				$capability	= $action['capability'] ?? $args['bulk_capability'];

				if(current_user_can($capability)){
					$args['bulk_actions'][$action_key]	= $action['title'];
				}
			}
		}
		
		$args['title']			= $args['title'] ?? $pt_obj->label;
		$args['actions']		= $actions;
		
		$this->_args	= $args;

		$this->_args['columns']				= [];
		$this->_args['sortable_columns']	= [];

		$fields	= $this->get_fields();

		if($fields){
			foreach ($fields as $key => $field) {
				$this->_args['columns'][$key] = $field['column_title'] ?? $field['title'];
				
				if(!empty($field['sortable_column'])){
					$this->_args['sortable_columns'][$key] = [$key, true];
				}

				if(!empty($field['searchable_column'])){
					$this->_args['search_metas'][]	= $key;
				}
			}
		}

		if(wp_doing_ajax()){
			add_action('wp_ajax_wpjam-list-table-action',	[$this, 'ajax_response']);

			$screen_id	= $_POST['screen_id'] ?? ($_POST['screen'] ?? '');
		}else{
			add_action('admin_head',	[$this, 'admin_head']);

			$screen_id	= get_current_screen()->id;
		}

		add_action('wpjam_html_replace',		[$this, 'html_replace']);
		add_filter('views_'.$screen_id,			[$this, 'posts_views'],1,2);

		add_action('pre_get_posts', 			[$this, 'pre_get_posts']);
		add_filter('posts_clauses', 			[$this, 'posts_clauses'],1,2);

		add_filter('bulk_actions-'.$screen_id,	[$this, 'posts_bulk_actions']);
		add_action('restrict_manage_posts',		[$this, 'restrict_manage_posts']);
		
		if($post_type == 'attachment'){
			add_filter('media_row_actions',		[$this, 'post_row_actions'],1,2);

			add_filter('manage_media_columns',			[$this, 'manage_posts_columns']);
			add_filter('manage_media_custom_column',	[$this, 'manage_posts_custom_column', 10, 2]);
		}else{
			if(is_post_type_hierarchical($post_type)){
				add_filter('page_row_actions',	[$this, 'post_row_actions'],1,2);
			}else{
				add_filter('post_row_actions',	[$this, 'post_row_actions'],1,2);
			}

			add_filter('manage_'.$post_type.'_posts_columns',		[$this, 'manage_posts_columns']);
			add_action('manage_'.$post_type.'_posts_custom_column',	[$this, 'manage_posts_custom_column'], 10, 2);
		}

		add_filter('manage_'.$screen_id.'_sortable_columns',	[$this, 'manage_posts_sortable_columns']);

		
	}

	public function posts_views($views){
		$model	= $this->get_model();

		if($model && method_exists($model, 'views')){
			return $model::views($views);
		}else{
			return $views;
		}
	}

	public function post_row_actions($row_actions, $post){
		$id			= $post->ID;
		$actions	= $this->_args['actions'];

		if($post->post_status == 'trash'){
			$row_actions['post_id'] = 'ID: '.$post->ID;
			return $row_actions;
		}

		if($actions){
			$row_actions	= array_merge($row_actions, $this->get_row_actions($actions, $id, $post));
		}

		if($model	= $this->get_model()){
			$method	= $this->_args['post_type'] == 'attachment' ? 'media_row_actions' : 'post_row_actions';

			if(method_exists($model, $method)){
				$row_actions	= $model::$method($row_actions, $post);	
			}
		}

		if(isset($row_actions['trash'])){
			$trash	= $row_actions['trash'];
			unset($row_actions['trash']);

			$row_actions['trash']	= $trash;
		}

		$row_actions['post_id'] = 'ID: '.$post->ID;

		return $row_actions;
	}

	public function restrict_manage_posts($post_type){
		$model	= $this->get_model();

		if($model && method_exists($model, 'restrict_manage_posts')){
			$model::restrict_manage_posts($post_type);
		}
	}

	public function posts_bulk_actions($bulk_actions=[]){
		if($this->_args['bulk_actions']){
			$bulk_actions = array_merge($bulk_actions, $this->_args['bulk_actions']);
		}

		$model	= $this->get_model();

		if($model && method_exists($model, 'bulk_actions')){
			return $model::bulk_actions($bulk_actions);
		}else{
			return $bulk_actions;
		}
	}

	public function manage_posts_columns($columns){
		if($this->_args['columns']){
			wpjam_array_push($columns, $this->_args['columns'], 'date'); 
		}

		$model	= $this->get_model();
		$method	= $this->_args['post_type'] == 'attachment' ? 'manage_media_columns' : 'manage_posts_columns';
			
		if($model && method_exists($model, $method)){
			return $model::$method($columns);
		}else{
			return $columns;	
		}
	}

	public function manage_posts_custom_column($column_name, $post_id){
		echo $this->column_callback($column_name, $post_id, 'post_meta');
	}

	public function manage_posts_sortable_columns($columns){
		if($this->_args['sortable_columns']){
			return array_merge($columns, $this->_args['sortable_columns']);
		}else{
			return $columns;
		}
	}

	public function html_replace($html){
		if($action = self::get_action('add')){
			$add_button	= wpjam_get_list_table_row_action('add', ['class'=>'page-title-action']);
			$html		= preg_replace('/<a href=".*?" class="page-title-action">.*?<\/a>/i', $add_button, $html);
		}
		
		return $html;
	}

	public function pre_get_posts($wp_query){
		if($sortable_columns	= $this->_args['sortable_columns']){
			$orderby	= $wp_query->get('orderby');

			if($orderby && is_string($orderby) && isset($sortable_columns[$orderby])){
				$fields	= $this->get_fields();
				$field	= $fields[$orderby] ?? '';

				$orderby_type = $field['sortable_column'] == 'meta_value_num' ? 'meta_value_num' : 'meta_value';
				
				$wp_query->set('meta_key', $orderby);
				$wp_query->set('orderby', $orderby_type);
			}
		}

		$model	= $this->get_model();

		if($model && method_exists($model, 'pre_get_posts')){
			$model::pre_get_posts($wp_query);
		}
	}

	public function posts_clauses($clauses, $wp_query){
		if($this->_args['search_metas'] && $wp_query->is_main_query() && $wp_query->is_search()){	// 支持搜索 post meta
			global $wpdb;

			$clauses['where']	= preg_replace_callback('/\('.$wpdb->posts.'.post_title LIKE (.*?)\) OR/', function($matches){
				global $wpdb;
				$search_metas	= $this->_args['search_metas'];
				$search_metas	= "'".implode("', '", $search_metas)."'";

				return "EXISTS (SELECT * FROM {$wpdb->postmeta} WHERE {$wpdb->postmeta}.post_id={$wpdb->posts}.ID AND meta_key IN ({$search_metas}) AND meta_value LIKE ".$matches[1].") OR ".$matches[0];
			}, $clauses['where']);
		}

		$model	= $this->get_model();

		if($model && method_exists($model, 'posts_clauses')){
			$clauses = $model::posts_clauses($clauses, $wp_query);
		}

		return $clauses;
	}

	public function admin_head(){
		if($bulk_actions = $this->_args['bulk_actions']){	$actions = $this->_args['actions'];
		?>

		<script type="text/javascript">
		jQuery(function($){
			<?php foreach($bulk_actions as $action_key => $bulk_action) { 
				$bulk_action	= $actions[$action_key];

				$datas	= ['action'=>$action_key, 'bulk'=>true];

				$datas['page_title']	= $bulk_action['page_title']??$bulk_action['title']; 
				$datas['nonce']			= $this->create_nonce('bulk_'.$action_key); 

				if(!empty($bulk_action['direct'])){
					$datas['direct']	= true;
				}

				if(!empty($bulk_action['confirm'])){
					$datas['confirm']	= true;
				}

				echo '$(\'.bulkactions option[value='.$action_key.']\').data('.wpjam_json_encode($datas).')'."\n";
			}?>
		});
		</script>

		<?php } 

		$model	= $this->get_model();

		if($model && method_exists($model, 'admin_head')){
			$model::admin_head();
		}
	}
}

class WPJAM_Post_List_Table extends WPJAM_Posts_List_Table{
	
}