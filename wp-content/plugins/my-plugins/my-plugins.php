<?php 
/**
* Plugin Name: My Plugin
* Plugin URI: my_site_url
* Description: my first plugin for wordpress
* Author: my_name
* Author URI: my_name_url
* Version: 1.0.0
* License: GPLv2
*/



/**
* ----------------------------------------------------------------------------------
* add tab to admin menu start
* ----------------------------------------------------------------------------------
*/
function sp_admin_menu() {
add_menu_page(
'页面名称',// 页面名称
'我的插件按钮名称', //menu
'manage_options',//管理操作，这里固定
'my-plugin-manger',//访问页面slug
'br_admin_page',//回调函数
'',//icon_url
4 //在菜单中显示的顺序
);
}
add_action( 'admin_menu', 'sp_admin_menu' );
function br_admin_page(){
require_once dirname(__FILE__) . "/pages/manager.php";
}
/**
* ----------------------------------------------------------------------------------
* add tab to admin menu end
* ----------------------------------------------------------------------------------
*/