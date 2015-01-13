<?php /** Remove Admin Menu Items **/
add_action( 'admin_menu', 'union_remove_menu_pages', 999 );
function union_remove_menu_pages() {
	remove_menu_page('link-manager.php');
	remove_menu_page('tools.php');
	remove_submenu_page( 'themes.php', 'theme-editor.php' );	
}

/* Unregister Unused WordPress and Genesis Widgets */
add_action( 'widgets_init', 'remove_wordpress_widgets', 20 );
function remove_wordpress_widgets() {
    unregister_widget( 'WP_Widget_Archives' );
    unregister_widget( 'WP_Widget_Pages' );
    unregister_widget( 'WP_Widget_Calendar' );
    unregister_widget( 'WP_Widget_Links' );
    unregister_widget( 'WP_Widget_Meta' );
    unregister_widget( 'WP_Widget_Categories' );
    unregister_widget( 'WP_Widget_Recent_Posts' );
    unregister_widget( 'WP_Widget_Recent_Comments' );
    unregister_widget( 'WP_Widget_Tag_Cloud' );
}

//* Modify the WordPress read more link
add_filter( 'the_content_more_link', 'ch_read_more_link' );
function ch_read_more_link() {
	return '...<p><a class="more-link" href="' . get_permalink() . '">Read More</a></p>';
}

add_filter( 'body_class', 'stb_fixed_nav_class' );
function stb_fixed_nav_class( $classes ) {
	$classes[] = 'fix-that-nav';
	return $classes;
}

//* Change the search form
function st_barnabas_search_form( $form ) {
	$form = '<form role="search" method="get" id="search-form" class="search-form" action="' . home_url( '/' ) . '" >
	<input type="text" value="' . get_search_query() . '" name="s" id="search-box" class="search-box" />
	<label for="search-box"><span class="glyphicon glyphicon-search search-icon"></span></label>
	<input type="submit" id="search-submit" />
	</form>';

	return $form;
}

add_filter( 'get_search_form', 'st_barnabas_search_form' );

/**
 * Social Media Icons
 *
 * @author Greg Rickaby
 * @since 1.0.0
 */
function child_social_media_icons() {
	if ( is_single() ) { ?>

	<div class="social-media-icons">
	
		<div class="gplus-button">
		<g:plusone size="medium" href="<?php the_permalink(); ?>"></g:plusone>
			<script type="text/javascript">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
 				})();
			</script>
		</div>
		
		<div class="twitter-button">
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-via="TYCWP">Tweet</a>
			
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		
		<div class="facebook-button">
		<div class="fb-like" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false" ></div>
		</div>

	</div>

<?php } }

/* add styles dropdown to TinyMCE */
add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

function my_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}

add_filter( 'tiny_mce_before_init', 'my_mce_before_init' );

function my_mce_before_init( $settings ) {

    $style_formats = array(
    	array(
    		'title' => 'Author Bio',
    		'selector' => 'p',
    		'classes' => 'author-bio',
    	),
        array(
        	'title' => 'Editors Note',
        	'selector' => 'p',
        	'classes' => 'editors-note',
        ),
		array(
			'title' => 'Photo Credit',
			'selector' => 'p',
			'classes' => 'photo-credit',
		),
    );

    $settings['style_formats'] = json_encode( $style_formats );

    return $settings;
}
?>