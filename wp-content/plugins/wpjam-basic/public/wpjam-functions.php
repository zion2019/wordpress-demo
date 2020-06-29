<?php
// 获取设置
function wpjam_get_setting($option, $setting_name, $blog_id=0){
	if(is_string($option)) {
		$option = wpjam_get_option($option, $blog_id);
	}

	if($option && isset($option[$setting_name])){
		$value	= $option[$setting_name];
	}else{
		return null;
	}

	if($value && is_string($value)){
		return  str_replace("\r\n", "\n", trim($value));
	}else{
		return $value;
	}
}

// 更新设置
function wpjam_update_setting($option_name, $setting_name, $setting_value, $blog_id=0){
	$option	= wpjam_get_option($option_name, $blog_id);
	$option[$setting_name]	= $setting_value;

	if($blog_id && is_multisite()){
		return update_blog_option($blog_id, $option_name, $option);
	}else{
		return update_option($option_name, $option);
	}
}

function wpjam_delete_setting($option_name, $setting_name, $blog_id=0){
	$option	= wpjam_get_option($option_name, $blog_id);

	if(isset($option[$setting_name])){
		unset($option[$setting_name]);
	}

	if($blog_id && is_multisite()){
		return update_blog_option($blog_id, $option_name, $option);
	}else{
		return update_option($option_name, $option);
	}
}

// 获取选项
function wpjam_get_option($option_name, $blog_id=0){
	if(is_multisite()){
		if(is_network_admin()){
			return get_site_option($option_name);
		}else{
			
			if($blog_id){
				$option	= get_blog_option($blog_id, $option_name) ?: [];
			}else{
				$option	= get_option($option_name) ?: [];	
			}

			if(apply_filters('wpjam_option_use_site_default', false, $option_name)){
				$site_option	= get_site_option($option_name) ?: [];
				$option			= $option + $site_option;
			}

			return $option;
		}
	}else{
		return get_option($option_name) ?: [];
	}
}

// 1. $img_url 
// 2. $img_url, array('width'=>100, 'height'=>100)	// 这个为最标准版本
// 3. $img_url, 100x100
// 4. $img_url, 100
// 5. $img_url, array(100,100)
// 6. $img_url, array(100,100), $crop=1, $retina=1
// 7. $img_url, 100, 100, $crop=1, $retina=1
function wpjam_get_thumbnail(){
	$args_num	= func_num_args();
	$args		= func_get_args();

	$img_url	= $args[0];

	if(strpos($img_url, '?') === false){
		$img_url	= str_replace(['%3A','%2F'], [':','/'], urlencode(urldecode($img_url)));	// 中文名
	}

	if($args_num == 1){	
		// 1. $img_url 简单替换一下 CDN 域名

		$thumb_args = [];
	}elseif($args_num == 2){		
		// 2. $img_url, ['width'=>100, 'height'=>100]	// 这个为最标准版本
		// 3. $img_url, [100,100]
		// 4. $img_url, 100x100
		// 5. $img_url, 100		

		$thumb_args = wpjam_parse_size($args[1]);
	}else{
		if(is_numeric($args[1])){
			// 6. $img_url, 100, 100, $crop=1, $retina=1

			$width	= $args[1] ?? 0;
			$height	= $args[2] ?? 0;
			$crop	= $args[3] ?? 1;
			// $retina	= $args[4] ?? 1;
		}else{
			// 7. $img_url, array(100,100), $crop=1, $retina=1

			$size	= wpjam_parse_size($args[1]);
			$width	= $size['width'];
			$height	= $size['height'];
			$crop	= $args[2]??1;
			// $retina	= $args[3]??1;
		}

		// $width		= intval($width)*$retina;
		// $height		= intval($height)*$retina;

		$thumb_args = compact('width','height','crop');
	}

	return apply_filters('wpjam_thumbnail', $img_url, $thumb_args);
}

function wpjam_parse_size($size, $retina=1){
	global $content_width;	

	$_wp_additional_image_sizes = wp_get_additional_image_sizes();

	if(is_array($size)){
		if(wpjam_is_assoc_array($size)){
			$size['width']	= $size['width'] ?? 0;
			$size['height']	= $size['height'] ?? 0;
			$size['width']	*= $retina;
			$size['height']	*= $retina;
			$size['crop']	= !empty($size['width']) && !empty($size['height']);
			return $size;
		}else{
			$width	= intval($size[0]??0);
			$height	= intval($size[1]??0);
			$crop	= $width && $height;
		}
	}else{
		if(strpos($size, 'x')){
			$size	= explode('x', $size);
			$width	= intval($size[0]);
			$height	= intval($size[1]);
			$crop	= $width && $height;
		}elseif(is_numeric($size)){
			$width	= $size;
			$height	= 0;
			$crop	= false;
		}elseif($size == 'thumb' || $size == 'thumbnail'){
			$width	= intval(get_option('thumbnail_size_w'));
			$height = intval(get_option('thumbnail_size_h'));
			$crop	= get_option('thumbnail_crop');

			if(!$width && !$height){
				$width	= 128;
				$height	= 96;
			}

		}elseif($size == 'medium'){

			$width	= intval(get_option('medium_size_w')) ?: 300;
			$height = intval(get_option('medium_size_h')) ?: 300;
			$crop	= get_option('medium_crop');

		}elseif( $size == 'medium_large' ) {

			$width	= intval(get_option('medium_large_size_w'));
			$height	= intval(get_option('medium_large_size_h'));
			$crop	= get_option('medium_large_crop');

			if(intval($content_width) > 0){
				$width	= min(intval($content_width), $width);
			}

		}elseif($size == 'large'){

			$width	= intval(get_option('large_size_w')) ?: 1024;
			$height	= intval(get_option('large_size_h')) ?: 1024;
			$crop	= get_option('large_crop');

			if (intval($content_width) > 0) {
				$width	= min(intval($content_width), $width);
			}
		}elseif(isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$size])){
			$width	= intval($_wp_additional_image_sizes[$size]['width']);
			$height	= intval($_wp_additional_image_sizes[$size]['height']);
			$crop	= $_wp_additional_image_sizes[$size]['crop'];

			if(intval($content_width) > 0){
				$width	= min(intval($content_width), $width);
			}
		}else{
			$width	= 0;
			$height	= 0;
			$crop	= 0;
		}
	}

	$width	= $width * $retina;
	$height	= $height * $retina;

	return compact('width','height', 'crop');
}

function wpjam_basic_get_setting($setting_name){
	$setting_value	= wpjam_get_setting('wpjam-basic', $setting_name);

	if($setting_value){
		if($setting_name == 'disable_rest_api'){
			return wpjam_basic_get_setting('disable_post_embed') && wpjam_basic_get_setting('diable_block_editor');
		}elseif($setting_name == 'disable_xml_rpc'){
			return wpjam_basic_get_setting('diable_block_editor');
		}
	}

	return $setting_value;
}

function wpjam_basic_update_setting($setting, $value){
	return wpjam_update_setting('wpjam-basic', $setting, $value);
}


function wpjam_add_admin_notice($notice){
	return WPJAM_Notice::add($notice);
}

function wpjam_add_user_notice($user_id, $notice){
	$user_id	= $user_id ?: get_current_user_id();

	return WPJAM_Notice::add($notice, $user_id);
}

function wpjam_send_user_message($message){
	return WPJAM_Message::insert($message);
}

function wpjam_generate_random_string($length){
	return WPJAM_OPENSSL_Crypt::generate_random_string($length);
}

// 显示字段
function wpjam_fields($fieds, $args=[]){
	return WPJAM_Field::fields_callback($fieds, $args);
}

function wpjam_get_field_value($field, $args=[]){
	return WPJAM_Field::get_field_value($field, $args);
}

// 验证一组字段的值
function wpjam_validate_fields_value($fields, $values=[]){
	return WPJAM_Field::validate_fields_value($fields, $values);
}

// 获取表单 HTML
function wpjam_get_field_html($field){
	return WPJAM_Field::get_field_html($field);
}

function wpjam_form_field_tmpls($echo=true){
}





function wpjam_parse_shortcode_attr($str,  $tagnames=null){
	return 	WPJAM_API::parse_shortcode_attr($str,  $tagnames);
}

// 去掉非 utf8mb4 字符
function wpjam_strip_invalid_text($str){
	return WPJAM_API::strip_invalid_text($str);
}

// 去掉 4字节 字符
function wpjam_strip_4_byte_chars($chars){
	return WPJAM_API::strip_4_byte_chars($chars);
}

// 去掉控制字符
function wpjam_strip_control_characters($text){
	return WPJAM_API::strip_control_characters($text);
}

//获取纯文本
function wpjam_get_plain_text($text){
	return WPJAM_API::get_plain_text($text);
}

//获取第一段
function wpjam_get_first_p($text){
	return WPJAM_API::get_first_p($text);
}

//中文截取方式
function wpjam_mb_strimwidth($text, $start=0, $width=40){
	return WPJAM_API::mb_strimwidth($text, $start, $width);
}

// 检查非法字符
function wpjam_blacklist_check($text, $name='内容'){
	if(empty($text)){
		return false;
	}

	$pre	= apply_filters('wpjam_pre_blacklist_check', null, $text, $name);
	
	if(is_null($pre)){
		return  WPJAM_API::blacklist_check($text);	
	}else{
		return $pre;
	}
	
}

// 获取当前页面 url
function wpjam_get_current_page_url(){
	return WPJAM_API::get_current_page_url();
}

// 获取参数，
function wpjam_get_parameter($parameter, $args=[]){
	return WPJAM_API::get_parameter($parameter, $args);
}

function wpjam_human_time_diff($from, $to=0) {
	return WPJAM_API::human_time_diff($from, $to);
}

function wpjam_get_video_mp4($id_or_url){
	return WPJAM_API::get_video_mp4($id_or_url);
}

function wpjam_get_qqv_mp4($vid){
	return WPJAM_API::get_qqv_mp4($vid);
}

function wpjam_send_json($response=[], $status_code=null){
	WPJAM_API::send_json($response, $status_code);
}

function wpjam_json_encode( $data, $options = JSON_UNESCAPED_UNICODE, $depth = 512){
	return WPJAM_API::json_encode($data, $options, $depth);
}

function wpjam_json_decode($json, $assoc=true, $depth=512, $options=0){
	return WPJAM_API::json_decode($json, $assoc, $depth, $options);
}

function wpjam_remote_request($url, $args=[], $err_args=[]){
	return WPJAM_API::http_request($url, $args, $err_args);
}

function wpjam_get_ua(){
	return WPJAM_API::get_user_agent();
}

function wpjam_get_user_agent(){
	return WPJAM_API::get_user_agent();
}

function wpjam_get_ua_data($ua=''){
	return WPJAM_API::parse_user_agent($ua);
}

function wpjam_parse_user_agent($ua=''){
	return WPJAM_API::parse_user_agent($ua);
}

function wpjam_get_ipdata($ip=''){
	return WPJAM_API::parse_ip($ip);
}

function wpjam_parse_ip($ip=''){
	return WPJAM_API::parse_ip($ip);
}

function wpjam_get_ip(){
	return WPJAM_API::get_ip();
}

function is_ipad(){
	return WPJAM_API::is_ipad();
}

function is_iphone(){
	return WPJAM_API::is_iphone();
}

function is_ios(){
	return WPJAM_API::is_ios();
}

function is_mac(){
	return is_macintosh();
}

function is_macintosh(){
	return WPJAM_API::is_macintosh();
}

function is_android(){
	return WPJAM_API::is_android();
}

// 判断当前用户操作是否在微信内置浏览器中
function is_weixin(){ 
	return WPJAM_API::is_weixin();
}

// 判断当前用户操作是否在微信小程序中
function is_weapp(){ 
	return WPJAM_API::is_weapp();
}

if(!function_exists('is_login')){
	function is_login(){
		global $pagenow;
		return $pagenow == 'wp-login.php';
	}	
}

if(!function_exists('str_replace_deep')){
	function str_replace_deep($search, $replace, $value){
		return map_deep($value, function($value) use($search, $replace){
			return str_replace($search, $replace, $value);
		});
	}
}

function wpjam_get_current_user(){
	return apply_filters('wpjam_current_user', null);
}


function wpjam_get_data_parameter($key){
	if(isset($_GET[$key])){
		return $_GET[$key];
	}

	if(isset($_REQUEST['data'])){
		$data		= wp_parse_args($_REQUEST['data']);
		$defaults	= !empty($_REQUEST['defaults']) ? wp_parse_args($_REQUEST['defaults']) : [];

		$data		= wpjam_array_merge($defaults, $data);

		if(isset($data[$key])){
			return $data[$key];
		}
	}

	return null;
}

// 打印
function wpjam_print_r($value){
	$capability	= (is_multisite())?'manage_site':'manage_options';
	if(current_user_can($capability)){
		echo '<pre>';
		print_r($value);
		echo '</pre>'."\n";
	}
}

function wpjam_var_dump($value){
	$capability	= (is_multisite())?'manage_site':'manage_options';
	if(current_user_can($capability)){
		echo '<pre>';
		var_dump($value);
		echo '</pre>'."\n";
	}
}

function wpjam_pagenavi($total=0){
	$args = [
		'prev_text'	=> '&laquo;',
		'next_text'	=> '&raquo;'
	];

	if(!empty($total)){
		$args['total']	= $total;
	}

	echo '<div class="pagenavi">'.paginate_links($args).'</div>'; 
}

// 判断一个数组是关联数组，还是顺序数组
function wpjam_is_assoc_array(array $arr){
	if ([] === $arr) return false;
	return array_keys($arr) !== range(0, count($arr) - 1);
}

// 向关联数组指定的 Key 之前插入数据
function wpjam_array_push(&$array, $data=null, $key=false){
	$data	= (array)$data;

	$offset	= ($key===false)?false:array_search($key, array_keys($array));
	$offset	= ($offset)?$offset:false;

	if($offset){
		$array = array_merge(
			array_slice($array, 0, $offset), 
			$data, 
			array_slice($array, $offset)
		);
	}else{	// 没指定 $key 或者找不到，就直接加到末尾
		$array = array_merge($array, $data);
	}
}

function wpjam_array_merge($arr1, $arr2){
	foreach($arr2 as $key => &$value){
		if(is_array($value) && isset($arr1[$key]) && is_array($arr1[$key])){
			$arr1[$key]	= wpjam_array_merge($arr1[$key], $value);
		}else{
			$arr1[$key]	= $value;
		}
	}

	return $arr1;
}

function wpjam_localize_script($handle, $object_name, $l10n ){
	wp_localize_script( $handle, $object_name, array('l10n_print_after' => $object_name.' = ' . wpjam_json_encode( $l10n )) );
}


function wpjam_is_mobile_number($number){
	return preg_match('/^0{0,1}(1[3,5,8][0-9]|14[5,7]|166|17[0,1,3,6,7,8]|19[8,9])[0-9]{8}$/', $number);
}


function wpjam_create_meta_table($meta_type, $table=''){
	if(empty($meta_type)){
		return;
	}

	global $wpdb;

	$table	= $table ?: $wpdb->prefix . $meta_type .'meta';
	$column	= sanitize_key($meta_type . '_id');

	if($wpdb->get_var("show tables like '{$table}'") != $table) {
		$sql	= "CREATE TABLE {$table} (
			meta_id bigint(20) unsigned NOT NULL auto_increment,
			{$column} bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY {$column} ({$column}),
			KEY meta_key (meta_key(191))
		)";


		$wpdb->query($sql);
	}
}

// function wpjam_is_400_number($number){
// 	return preg_match('/^400(\d{7})$/', $number);
// }

// function wpjam_is_800_number($number){
// 	return preg_match('/^800(\d{7})$/', $number);
// }

function wpjam_is_scheduled_event( $hook ) {	// 不用判断参数
	$crons = _get_cron_array();
	if (empty($crons)) return false;
	
	foreach ($crons as $timestamp => $cron) {
		if (isset($cron[$hook])) return true;
	}

	return false;
}

function wpjam_is_holiday($date=''){
	$date	= ($date)?$date:date('Y-m-d', current_time('timestamp'));
	$w		= date('w', strtotime($date));

	$is_holiday = ($w == 0 || $w == 6)?1:0;

	return apply_filters('wpjam_holiday', $is_holiday, $date);
}

function wpjam_set_cookie($key, $value, $expire){
	$expire	= ($expire < time())?$expire+time():$expire;

	$secure = ('https' === parse_url(get_option('home'), PHP_URL_SCHEME));

	setcookie($key, $value, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure);

    if ( COOKIEPATH != SITECOOKIEPATH ){
        setcookie($key, $value, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure);
    }
    $_COOKIE[$key] = $value;
}


function wpjam_get_additional_capabilities($user){
	global $wp_roles;

	$capabilities	= [];

	foreach ($user->caps as $cap => $value) {
		if($value && !$wp_roles->is_role($cap)){
			$capabilities[]	= $cap;
		}
	}

	return $capabilities;
}

function wpjam_set_additional_capabilities($user, $capabilities){
	$old_capabilities 	= wpjam_get_additional_capabilities($user);

	$remove_capabilities	= array_diff($old_capabilities, $capabilities);
	$add_capabilities		= array_diff($capabilities, $old_capabilities);

	if($remove_capabilities){
		foreach ($remove_capabilities as $cap) {
			$user->remove_cap($cap);
		}
	}

	if($add_capabilities){
		foreach ($add_capabilities as $cap) {
			$user->add_cap($cap);
		}
	}

	return $capabilities;
}


function wpjam_basic_get_default_settings(){
	return [
		'diable_revision'			=> 1,
		'disable_trackbacks'		=> 1,
		'disable_emoji'				=> 1,
		'disable_texturize'			=> 1,
		'disable_privacy'			=> 1,
		
		'remove_head_links'			=> 1,
		'remove_capital_P_dangit'	=> 1,

		'admin_footer'				=> '<span id="footer-thankyou">感谢使用<a href="https://cn.wordpress.org/" target="_blank">WordPress</a>进行创作。</span> | <a href="http://wpjam.com/" title="WordPress JAM" target="_blank">WordPress JAM</a>'
	];
}