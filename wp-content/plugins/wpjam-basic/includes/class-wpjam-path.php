<?php
class WPJAM_Path {
	private $page_key;
	private $page_type	= '';
	private $post_type	= '';
	private $taxonomy	= '';
	private $fields		= [];
	private $title		= '';
	private $paths		= [];
	private $callbacks	= [];
	private $queries	= [];
	private static $wpjam_paths	= [];

	public function __construct($page_key, $args=[]){
		$this->page_key		= $page_key;
		$this->page_type	= $args['page_type'] ?? '';
		$this->title		= $args['title'] ?? '';
		$this->fields		= $args['fields'] ?? [];

		if($this->page_type == 'post_type'){
			$this->post_type	= $args['post_type'] ?? $this->page_key;
		}elseif($this->page_type == 'taxonomy'){
			$this->taxonomy		= $args['taxonomy'] ?? $this->page_key;
		}
	}

	public function get_title(){
		return $this->title;
	}

	public function get_page_type(){
		return $this->page_type;
	}

	public function get_post_type(){
		return $this->post_type;
	}

	public function get_taxonomy(){
		return $this->taxonomy;
	}

	public function get_fields(){
		if($this->fields){
			return $this->fields;
		}else{
			$fields	= [];

			if($this->page_type == 'post_type'){
				$post_type_obj	= get_post_type_object($this->post_type);

				$fields[$this->post_type.'_id']	= ['title'=>$post_type_obj->label,	'type'=>'text',	'class'=>'all-options',	'data_type'=>'post_type',	'post_type'=>$this->post_type, 'placeholder'=>'请输入ID或者输入关键字筛选'];
			}elseif($this->page_type == 'taxonomy'){
				$taxonomy_obj	= get_taxonomy($this->taxonomy);

				$levels		= $taxonomy_obj->levels ?? 0;
				$terms		= wpjam_get_terms(['taxonomy'=>$this->taxonomy,	'hide_empty'=>0], $levels);
				$terms		= wpjam_flatten_terms($terms);
				$options	= $terms ? wp_list_pluck($terms, 'name', 'id') : [];
				
				$fields[$this->taxonomy.'_id']	= ['title'=>$taxonomy_obj->label,	'type'=>'select',	'options'=>$options];
			}

			return $fields;
		}
	}

	public function set_title($title){
		$this->title	= $title;
	}

	public function set_path($type, $path=''){
		$this->paths[$type]		= $path;
	}

	public function set_callback($type, $callback=''){
		$this->callbacks[$type]	= $callback;
	}

	public function set_query($type, $query=[]){
		$this->queries[$type]	= $query;
	}

	public function get_path($type, $args=[]){
		if($type == 'template'){

		}else{
			$callback	= $this->callbacks[$type] ?? '';

			if($callback){
				return call_user_func($callback, $args);
			}else{
				if($this->page_type == 'post_type'){
					return $this->get_post_type_path($args, $type);
				}elseif($this->page_type == 'taxonomy'){
					return $this->get_taxonomy_path($args, $type);
				}else{
					return $this->paths[$type] ?? '';
				}
			}
		}
	}

	public function get_post_type_path($args, $type){
		$post_id	= $args[$this->post_type.'_id'] ?? 0;

		if(empty($post_id)){
			$pt_object	= get_post_type_object($this->post_type);
			return new WP_Error('empty_'.$this->post_type.'_id', $pt_object->label.'ID不能为空。');
		}

		$path	= $this->paths[$type] ?? '';

		if(strpos($path, '%post_id%')){
			return str_replace('%post_id%', $post_id, $path);
		}else{
			if($query = $this->queries[$type] ?? []){
				$path	= add_query_arg([$query['post_id'] => $post_id], $path);
			}

			return $path;
		}
	}

	public function get_taxonomy_path($args, $type){
		$term_id	= $args[$this->taxonomy.'_id'] ?? 0;

		if(empty($term_id)){
			$tax_object	= get_taxonomy($this->taxonomy);
			return new WP_Error('empty_'.$this->taxonomy.'_id', $tax_object->label.'ID不能为空。');
		}

		$path	= $this->paths[$type] ?? '';

		if(strpos($path, '%term_id%')){
			$path	= str_replace('%term_id%', $term_id, $path);

			if(strpos($path, '%term_parent%')){
				$term	= get_term($term_id, $this->taxonomy);
				$parent	= ($term && $term->parent) ? $term->parent : $term_id;
				$path	= str_replace('%term_parent%', $parent, $path);
			}
		}else{
			$query = $this->queries[$type] ?? [];

			if($query && isset($query['term_id'])){
				$query_args	= [$query['term_id']=> $term_id];
				if(isset($query['term_parent'])){
					$term	= get_term($term_id, $this->taxonomy);
					$parent	= ($term && $term->parent) ? $term->parent : $term_id;

					$query_args[$query['term_parent']]	= $parent;
				}

				$path	= add_query_arg($query_args, $path);
			}
		}

		return $path;
	}

	public function get_raw_path($type){
		return $this->paths[$type] ?? '';
	}

	public function has($type){
		return isset($this->paths[$type]) || isset($this->callbacks[$type]);
	}

	public static function create($page_key, $args=[]){
		$path_obj	= self::get_instance($page_key);

		if(is_null($path_obj)){
			$path_obj	= new WPJAM_Path($page_key, $args);

			self::$wpjam_paths[$page_key]	= $path_obj;
		}

		if(!empty($args['path_type'])){
			if(!empty($args['callback'])){
				$path_obj->set_callback($args['path_type'], $args['callback']);
			}else{
				$path	= $args['path'] ?? '';
				$path_obj->set_path($args['path_type'], $path);
			}

			$query	= $args['query'] ?? [];
			$path_obj->set_query($args['path_type'], $query);
		}

		return $path_obj;
	}

	public static function get_instance($page_key){
		return self::$wpjam_paths[$page_key] ?? null;
	}

	public static function get_all(){
		return self::$wpjam_paths;
	}
}