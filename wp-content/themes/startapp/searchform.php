<?php
/**
 * Search Form
 *
 * @package StartApp
 */
?>
<form method="get" class="search-box" action="<?php echo esc_url( home_url( '/' ) ); ?>" autocomplete="off">
	<input type="text" name="s"
	       placeholder="<?php echo esc_attr_x( 'Search', 'search form placeholder', 'startapp' ); ?>"
	       value="<?php echo esc_attr( trim( get_search_query( false ) ) ); ?>">
	<button type="submit"><i class="material-icons search"></i></button>
</form>
