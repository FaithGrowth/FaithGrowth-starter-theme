<?php /* Unregister Unused WordPress and Genesis Widgets */
add_action( 'widgets_init', 'remove_genesis_widgets', 20 );
function remove_genesis_widgets() {
    unregister_widget( 'Genesis_eNews_Updates' );
    unregister_widget( 'Genesis_Featured_Page' );
    unregister_widget( 'Genesis_Featured_Post' );
    unregister_widget( 'Genesis_User_Profile_Widget' );
    unregister_widget( 'Genesis_Menu_Pages_Widget' );
    unregister_widget( 'Genesis_Widget_Menu_Categories' );
    unregister_widget( 'Genesis_Latest_Tweets_Widget' );
} 

/** Unregister unused Genesis layout settings */
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

/** Unregister unused sidebars */
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'header-right' );

add_filter( 'genesis_breadcrumb_args', 'child_breadcrumb_args' );
/**
 * Amend breadcrumb arguments.
 * 
 * @author Gary Jones
 *
 * @param array $args Default breadcrumb arguments
 * @return array Amended breadcrumb arguments
 */
function child_breadcrumb_args( $args ) {
    $args['home']                    = 'Home';
    $args['sep']                     = '  >  ';
    $args['list_sep']                = ', '; // Genesis 1.5 and later
    $args['prefix']                  = '<div class="breadcrumb">';
    $args['suffix']                  = '</div>';
    $args['heirarchial_attachments'] = true; // Genesis 1.5 and later
    $args['heirarchial_categories']  = true; // Genesis 1.5 and later
    $args['display']                 = true;
    $args['labels']['prefix']        = '';
    $args['labels']['author']        = 'Archives for ';
    $args['labels']['category']      = 'Archives for '; // Genesis 1.6 and later
    $args['labels']['tag']           = 'Archives for ';
    $args['labels']['date']          = 'Archives for ';
    $args['labels']['search']        = 'Search for ';
    $args['labels']['tax']           = 'Archives for ';
    $args['labels']['post_type']     = 'Archives for ';
    $args['labels']['404']           = 'Not found: '; // Genesis 1.5 and later
    return $args;
}

/** Add support for post formats */
add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
add_theme_support( 'genesis-post-format-images' );

add_action( 'genesis_before_post', 'mindstream_remove_elements' );
/**
 * If post has post format, remove the title, post info, and post meta.
 * If post does not have post format, then it is a default post. Add
 * title, post info, and post meta back.
 *
 * @since 1.0
 */
function mindstream_remove_elements() {
    
    // Remove if post has format
    if ( get_post_format() ) {
        remove_action( 'genesis_post_title', 'genesis_do_post_title' );
        remove_action( 'genesis_before_post_content', 'genesis_post_info' );
        remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
    }
    // Add back, as post has no format
    else {
        add_action( 'genesis_post_title', 'genesis_do_post_title' );
        add_action( 'genesis_before_post_content', 'genesis_post_info' );
        add_action( 'genesis_after_post_content', 'genesis_post_meta' );
    }
    
}

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
if (!is_page()) {
    $post_info = 'Posted on [post_date] &middot; [post_comments] [post_edit]';
    return $post_info;
}}

/** Customize the post meta function */
add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
if (!is_page()) {
    $post_meta = '[post_categories before="Filed Under: "] &middot; [post_tags before="Tagged: "]';
    return $post_meta;
}}

// Modify the size of the Gravatar in author box
add_filter( 'genesis_author_box_gravatar_size', 'mp_author_box_gravatar_size' );
function mp_author_box_gravatar_size( $size ) {
    return 80;
}

// Customize the entire footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'fg_custom_footer' );
function fg_custom_footer() {
    ?>
    <p>&copy; Copyright <?echo date("Y")?> <a href="http://faithgrowth.com/">Faith Growth, Inc</a> &middot; All Rights Reserved &middot; Powered by <a href="http://wordpress.org/" target="_blank ">WordPress</a></p>
    <?php
}

//* Modify the Genesis content limit read more link
add_filter( 'get_the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
    return '...<p><a class="more-link" href="' . get_permalink() . '">Read More</a></p>';
}

/** Customize the comment submit button text */
add_filter( 'genesis_comment_form_args', 'custom_comment_submit_button' );
function custom_comment_submit_button( $args ) {
    $args['label_submit'] = __( 'submit', 'apparition' );
    return $args;
}

/** Modify the speak your mind text */
add_filter( 'genesis_comment_form_args', 'custom_comment_form_args' );
function custom_comment_form_args($args) {
    $args['title_reply'] = 'Join the Discussion';
    return $args;
}

/** Change the default comment callback */
add_filter( 'genesis_comment_list_args', 'executive_comment_list_args' );
function executive_comment_list_args( $args ) {
    $args['callback'] = 'executive_comment_callback';
    
    return $args;
}

/** Customize the comment section */
function executive_comment_callback( $comment, $args, $depth ) {

    $GLOBALS['comment'] = $comment; ?>

    <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">

        <?php do_action( 'genesis_before_comment' ); ?>
        
        <div class="comment-header">
            <div class="comment-author vcard">
                <?php echo get_avatar( $comment, $size = $args['avatar_size'] ); ?>
                <?php printf( '<cite class="fn">%s</cite> <span class="says">%s:</span>', get_comment_author_link(), apply_filters( 'comment_author_says_text', __( 'says', 'executive' ) ) ); ?>
                <div class="comment-meta commentmetadata">
                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( '%1$s ' . __('at', 'executive' ) . ' %2$s', get_comment_date(), get_comment_time() ); ?></a>
                <?php edit_comment_link( __( 'Edit', 'executive' ), g_ent( '&bull; ' ), '' ); ?>
                </div><!-- end .comment-meta -->
            </div><!-- end .comment-author -->          
        </div><!-- end .comment-header -->  

        <div class="comment-content">
            <?php if ($comment->comment_approved == '0') : ?>
                <p class="alert"><?php echo apply_filters( 'genesis_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'executive' ) ); ?></p>
            <?php endif; ?>

            <?php comment_text(); ?>
        </div><!-- end .comment-content -->

        <div class="reply">
            <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div>

        <?php do_action( 'genesis_after_comment' );

    /** No ending </li> tag because of comment threading */

}

/** Add div in #content-sidebar-wrap and widget area */

add_action( 'genesis_meta', 'ch_content_header' );
    /**
    * Add widget support below subnav. If no widget active, display the default.
    *
    */
function ch_content_header() {

    if ( is_active_sidebar( 'ch-content-header-widget-area' ) ) {
        add_action( 'genesis_before_content', 'add_ch_content_header' );
    }
}

function add_ch_content_header() {

    echo '<div id="ch_content_header">';
    
    if ( is_active_sidebar( 'ch-content-header-widget-area' ) ) {
        echo '<div class="ch-content-header-widget-area">';
        dynamic_sidebar( 'ch-content-header-widget-area' );
        echo '</div><!-- end .ch-content-header-widget-area -->';
    }
    
    echo '</div><!-- end ch_content_header -->';
}

function single_post_featured_image() {
    if ( ! is_singular( 'post' ) )
        return;
    $img = genesis_get_image( array( 'format' => 'html', 'size' => genesis_get_option( 'image_size' ), 'attr' => array( 'class' => 'post-image' ) ) );
    printf( '<a href="%s" title="%s" style="float:right;">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), $img );    
}

?>