<?php
/**
 * A list of actions and filters that affected the frontend
 *
 * For callbacks {@see inc/template-tags.php}
 *
 * @author 8guild
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Before the <header> */

add_action( 'startapp_header_before', 'startapp_the_offcanvas' );
add_action( 'startapp_header_before', 'startapp_the_search' );
add_action( 'startapp_header_before', 'startapp_the_scroller' );
add_action( 'startapp_header_before', 'startapp_offcanvas_menu' );


/* Topbar */

add_action( 'startapp_topbar_left', 'startapp_topbar_lang_switcher', 5 );
add_action( 'startapp_topbar_right', 'startapp_topbar_lang_switcher', 5 );
add_action( 'startapp_topbar_left', 'startapp_topbar_add_info', 5 );
add_action( 'startapp_topbar_right', 'startapp_topbar_add_info', 5 );
add_action( 'startapp_topbar_left', 'startapp_topbar_menu', 5 );
add_action( 'startapp_topbar_right', 'startapp_topbar_menu', 5 );
add_action( 'startapp_topbar_left', 'startapp_topbar_socials', 5 );
add_action( 'startapp_topbar_right', 'startapp_topbar_socials', 5 );
add_action( 'startapp_topbar_right', 'startapp_topbar_toolbar', 100 );


/* Site Info */

add_action( 'startapp_site_info_left', 'startapp_the_logo', - 1 );
add_action( 'startapp_site_info_left', 'startapp_mobile_logo', - 1 );
add_action( 'startapp_site_info_right', 'startapp_site_info_contacts' );
add_action( 'startapp_site_info_right', 'startapp_site_info_toolbar', 100 );


/* Navbar */

add_action( 'startapp_navbar_tools', 'startapp_navbar_toolbar', 100 );


/* After the <header> */

add_action( 'startapp_header_after', 'startapp_open_page_wrap', -1 );
add_action( 'startapp_header_after', 'startapp_page_title' );


/* Blog (Home) */

add_action( 'startapp_blog_before', 'startapp_blog_open_wrapper', 5 );
add_action( 'startapp_blog_after', 'startapp_blog_close_wrapper', 5 );
add_action( 'startapp_loop_after', 'startapp_blog_pagination' );


/* Entry Tile */

add_action( 'startapp_post_body_before', 'startapp_entry_sticky' );
add_action( 'startapp_post_body_before', 'startapp_entry_thumbnail', 20 );
add_action( 'startapp_post_body_after', 'startapp_entry_thumbnail', 20 );
add_filter( 'comments_number', 'startapp_entry_comments_icon', 10, 2 );
add_filter( 'edit_post_link', 'startapp_edit_post_link', 10, 3 );
add_filter( 'excerpt_more', 'startapp_excerpt_more' );
add_filter( 'the_excerpt', 'startapp_the_excerpt', 20 );


/* Single Post */

add_action( 'startapp_single_before', 'startapp_single_cover' );


/* Archive */

add_action( 'startapp_archive_before', 'startapp_archive_open_wrapper', 5 );
add_action( 'startapp_archive_after', 'startapp_archive_close_wrapper', 5 );

/* Author */

add_action( 'startapp_loop_after', 'startapp_author_coauthor', 100 );
add_filter( 'startapp_page_title', 'startapp_author_title' );


/* Search Page */

add_action( 'startapp_search_before', 'startapp_search_open_wrapper', 5 );
add_action( 'startapp_search_after', 'startapp_search_close_wrapper', 5 );


/* Comments */

add_action( 'startapp_comments_before', 'startapp_comments_open_wrapper', 5 );
add_filter( 'comment_form_defaults', 'startapp_comment_form_defaults' );
add_action( 'comment_form_top', 'startapp_comment_form_top' );
add_filter( 'comment_form_logged_in', 'startapp_comment_form_logged_in' );
add_filter( 'comment_form_field_comment', 'startapp_comment_form_field_comment' );
add_action( 'comment_form_before_fields', 'startapp_comment_form_before_fields' );
add_filter( 'comment_form_default_fields', 'startapp_comment_form_default_fields' );
add_action( 'comment_form_after_fields', 'startapp_comment_form_after_fields' );
add_filter( 'comment_form_submit_button', 'startapp_comment_form_submit_button', 10, 2 );
add_action( 'comment_form', 'startapp_comment_form' );
add_action( 'startapp_comments_after', 'startapp_comments_close_wrapper', 5 );


/* After the <footer> */

add_action( 'startapp_footer_after', 'startapp_close_page_wrap', 999 );
add_action( 'startapp_footer_after', 'startapp_scroll_to_top', 1000 );
add_action( 'startapp_footer_after', 'startapp_footer_backdrop', 1000 );
