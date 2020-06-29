<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

/**
 * Fires at the most top of the comments page
 *
 * @see startapp_comments_open_wrapper() 5
 */
do_action( 'startapp_comments_before' );

if ( have_comments() ) :

	printf( '<h4 class="text-gray margin-bottom-2x">%1$s <sup>%2$d</sup></h4>',
		esc_html_x( 'Comments', 'comments title', 'startapp' ),
		number_format_i18n( get_comments_number() )
	);

	// Display the list of comments
	wp_list_comments( array(
		'style'        => 'div',
		'callback'     => 'startapp_comment',
		'end-callback' => 'startapp_comment_end',
		'max_depth'    => startapp_comments_nesting_level(),
		'type'         => 'comment',
		'reply_text'   => esc_html_x( 'Reply', 'comment reply', 'startapp' ),
		'avatar_size'  => 60,
		'short_ping'   => true,
	) );

	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'startapp' ); ?></h2>
			<div class="nav-links">
				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'startapp' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'startapp' ) ); ?></div>
			</div>
		</nav>
		<?php
	endif;

endif;

// If comments are closed and there are comments, let's leave a little note, shall we?
if ( ! comments_open()
     && get_comments_number()
     && post_type_supports( get_post_type(), 'comments' )
) {
	printf( '<p class="no-comments">%s</p>', esc_html__( 'Comments are closed.', 'startapp' ) );
}

comment_form();

/**
 * Fires at the most bottom of the comments page
 *
 * @see startapp_comments_close_wrapper() 5
 */
do_action( 'startapp_comments_after' );
