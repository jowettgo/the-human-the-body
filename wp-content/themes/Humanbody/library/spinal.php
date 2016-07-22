<?php
/* Welcome to spinal :)
This is the core spinal file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/spinal/

  - head cleanup (remove rsd, uri links, junk css, ect)
  - enqueueing scripts & styles
  - theme support functions
  - custom menu output & fallbacks
  - related post function
  - page-navi function
  - removing <p> from around images
  - customizing the post excerpt

*/

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function spinal_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	add_filter( 'style_loader_src', 'spinal_remove_wp_ver_css_js', 9999 );
	// remove Wp version from scripts
	add_filter( 'script_loader_src', 'spinal_remove_wp_ver_css_js', 9999 );

} /* end spinal head cleanup */

// A better title
// http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
function rw_title( $title, $sep, $seplocation ) {
  global $page, $paged;

  // Don't affect in feeds.
  if ( is_feed() ) return $title;

  // Add the blog's name
  if ( 'right' == $seplocation ) {
    $title .= get_bloginfo( 'name' );
  } else {
    $title = get_bloginfo( 'name' ) . $title;
  }

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );

  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title .= " {$sep} {$site_description}";
  }

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ) {
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
  }

  return $title;

} // end better title

// remove WP version from RSS
function spinal_rss_version() { return ''; }

// remove WP version from scripts
function spinal_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

// remove injected CSS for recent comments widget
function spinal_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

// remove injected CSS from recent comments widget
function spinal_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

// remove injected CSS from gallery
function spinal_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}


/*********************
SCRIPTS & ENQUEUEING
*********************/
/* disable emojy */
function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
/* disable wordpress emojicons */
// add_action( 'init', 'disable_wp_emojicons' );
function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) :
    	return array_diff( $plugins, array( 'wpemoji' ) );
	else :
    	return array();
	endif;
}

// loading modernizr and jquery, and reply script
function spinal_scripts_and_styles() {

  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

  if (!is_admin()) {


		/* CSS FILES
		-------------------------------------------------------------------------------------------------------------- */
		// register main stylesheet
		wp_register_style( 'spinal-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );
		wp_register_style( 'patch', get_stylesheet_directory_uri() . '/library/css/patch.css', array(), '', 'all' );
		// register fancybox
		wp_register_style( 'spinal-fancybox', get_stylesheet_directory_uri() . '/library/js/vendor/fb/jquery.fancybox.css', array(), '', 'all' );
		// register google fonts
		wp_register_style( 'lato', 'https://fonts.googleapis.com/css?family=Lato:400,300,700,400italic,900', array(), '', 'all' );
		// register
		wp_register_style( 'fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '', 'all' );


		// wp_enqueue_style( 'spinal-font-awesome' );
		/* add main stylesheet */
		wp_enqueue_style( 'spinal-stylesheet' );
		wp_enqueue_style( 'patch' );
		/* add fancybox style */
		wp_enqueue_style( 'spinal-fancybox' );
		/* add google fonts */
		wp_enqueue_style( 'lato' );
		/* add font awesome */
		wp_enqueue_style( 'fa' );

		/* add responsive */
		// wp_enqueue_style( 'spinal-media' );

		// unregister jquery auto load
		wp_deregister_script( 'jquery' );
		/* JS FILES
		-------------------------------------------------------------------------------------------------------------- */
		/* add modernizer */
		wp_register_script( 'spinal-modernizr', get_stylesheet_directory_uri() . '/library/js/vendor/modernizr.custom-2.6.2.min.js', array(), '2.6.2', false );

		/* add minified javascript */
		wp_register_script( 'jquery', get_stylesheet_directory_uri() . '/library/js/script.js', array(), '', true );
		wp_register_script( 'patch', get_stylesheet_directory_uri() . '/library/js/patch.js', array(), '', true );
		/* add fancybox */
		wp_register_script( 'spinal-fancybox', get_stylesheet_directory_uri() . '/library/js/vendor/fb/jquery.fancybox.pack.js', array('spinal-js'), '', true );


	    // comment reply script for threaded comments
	    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
			  wp_enqueue_script( 'comment-reply' );
	    }

		// add main javascript file
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'patch' );
		// add fancybox
		wp_enqueue_script( 'spinal-fancybox' );
		// wp_enqueue_script( 'spinal-bootstrap' );

	}
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function spinal_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	// wp custom background (thx to @bransonwerner for update)
	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',    // background image default
	    'default-color' => '',    // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	// add_theme_support( 'post-formats',
	// 	array(
	// 		'aside',             // title less blurb
	// 		'gallery',           // gallery of images
	// 		'link',              // quick link to other site
	// 		'image',             // an image
	// 		'quote',             // a quick quote
	// 		'status',            // a Facebook like status update
	// 		'video',             // video
	// 		'audio',             // audio
	// 		'chat'               // chat transcript
	// 	)
	// );

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-nav' => __( 'The Main Menu', 'spinaltheme' ),   // main nav in header
			'footer-nav' => __( 'Footer Menu', 'spinaltheme' ), // nav in footer
		)
	);

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form'
	) );
	/* add editor style support */
	add_editor_style(array(
		get_stylesheet_directory_uri() . '/library/css/src/1.bootstrap.min.css', /* load bootstrap */
		'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', /* load font awesome */
		get_stylesheet_directory_uri() . '/library/css/editor-style.css', /* load editor style */
	));
} /* end spinal theme support */





/* remove bbpress css */
add_action( 'wp_print_styles', 'deregister_bbpress_styles', 15 );
function deregister_bbpress_styles() {
 	wp_deregister_style( 'bbp-default' );
}



//remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );











/**
 * admin only debug function 2.0
 * @method deg
 * @param  $mixed [description]
 * @return debugging
 */
function deg($mixed, $adminonly = false) {
	$userloggedin = wp_get_current_user();
	$user = get_userdata($userloggedin->ID);
	if($user && $user->has_cap('administrator') || !$adminonly) :
        $function = debug_backtrace();
		echo "<pre style='word-wrap: break-word;
                white-space: pre!important;
                padding: 5%;border-radius:0px;box-shadow:0px;font-size:12px;
                background: #161616;
                color: #d9d9d9;margin:1% 5%;float:left;width:80%;    z-index: 99991;
    position: relative;'>";
            echo '<span style="font-size:10px;color:rgb(101, 101, 101)">';
            echo ''. $function[0]['file']."\r\n";
            echo 'line: '.$function[0]['line']."\r\n";
            echo '</span>';

			print_r($mixed);
		echo "</pre>";
	endif;
}

























/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using spinal_related_posts(); )
function spinal_related_posts() {
	echo '<ul id="spinal-related-posts">';
	global $post;
	$tags = wp_get_post_tags( $post->ID );
	if($tags) {
		foreach( $tags as $tag ) {
			$tag_arr .= $tag->slug . ',';
		}
		$args = array(
			'tag' => $tag_arr,
			'numberposts' => 5, /* you can change this to show more */
			'post__not_in' => array($post->ID)
		);
		$related_posts = get_posts( $args );
		if($related_posts) {
			foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; }
		else { ?>
			<?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'spinaltheme' ) . '</li>'; ?>
		<?php }
	}
	wp_reset_postdata();
	echo '</ul>';
} /* end spinal related posts function */



/*********************
RANDOM CLEANUP ITEMS
*********************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function spinal_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function spinal_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'spinaltheme' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'spinaltheme' ) .'</a>';
}

/*
PAGE NAVI
----------------------------------------------------------*/
// Numeric Page Navi (built into the theme by default)
function spinal_page_navi($blog) {
  global $wp_query;
  $bignum = 999999999;
  if ( $blog->max_num_pages <= 1 )
    return;


	$page_nr = get_query_var('paged');


	$nav = paginate_links( array(
    	'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    	'format'       => '',
	    'current'      => max( 1, $page_nr ),
	    'total'        => $blog->max_num_pages,
	    'prev_next'    => false,
	    'next_text'    => '',
		'prev_text'    => '',
	    'type'         => 'array',
	    'end_size'     => 1,
	    'mid_size'     => 2
	));
	if($nav) :


	/* previos link */
	$previous = get_previous_posts_link('<');
	$next = get_next_posts_link('>',$blog->max_num_pages );

	?>


  <?php
  	if($previous) :
		echo '<li>'.$previous.'</li>';
	endif;
	foreach ($nav as $key => $link) :
	  ?>
	  <li>
	  <?php echo $link; ?>
	  </li>
  <?php
	endforeach;
	if($next) :
 		echo '<li>'.$next.'</li>';
	endif;
  endif;
  ?>
	<?php
} /* end page navi */
/*
END PAGE NAVI
----------------------------------------------------------*/

function page($name) {
	$pageTemplates =  get_all_page_templates();

	return $pageTemplates['page-'.$name.'.php']['url'];
}







function spinal_get_attachment_id_from_src ($image_src) {

		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;

	}

function prepare_video($url) {
	/* add support for vimeo */
	if(strpos($url, 'vimeo') !== false) {
		$switch = 'vimeo';
	}
	/* add support for youtube */
	if(strpos($url, 'youtube') !== false) {
		$switch = 'youtube';
	}
	/* prepare video with a switch based on video support */
	switch ($switch) {
		case 'youtube':
			//HimXOB2pA6I
			$parsed_url = parse_url($url);
			$vID = str_replace('v=', '', $parsed_url['query']);

			$return = 'http://www.youtube.com/embed/'.$vID.'?autoplay=0';

			break;

		case 'vimeo':
			$parsed_url = parse_url($url);
			$vID = $parsed_url['path'];
			$return = 'https://player.vimeo.com/video'.$vID;

			break;

		default:
			# code...
			break;
	}
	return $return;
}
function prepare_video_array($array) {
	if(is_array($array) && count($array) > 0) :
		foreach ($array as $key => $url) {
			if(stripos($url, 'youtube' )) :
				$type = 'youtube';
			endif;
			if(stripos($url, 'vimeo' )) :
				$type = 'vimeo';
			endif;
			$embedUrl = prepare_video($url);
			$videos[$key]['type'] = $type;
			$videos[$key]['embed'] = $embedUrl;
			$videos[$key]['code'] = '<iframe width="93" height="63" src="'.$embedUrl.'" webkitallowfullscreen mozallowfullscreen allowfullscreen frameborder="0"></iframe>';
		}
		return $videos;
	endif;
	return array();
}


/* featured image url */
/**
 * [featured_image get image by post or attachment id]
 * @method featured_image
 * @param  [object/int]         $mixed [post object or attachemnt id]
 * @param  [string]         $size  [thumb, full, or custom size defined in wordpress image sizes]
 * @return [string]                [src of image size]
 */
function featured_image($mixed, $size='full') {

	if($mixed->ID) :
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($mixed->ID), $size);
	else :

		$image = wp_get_attachment_image_src($mixed, $size);
	endif;
	return $image[0];
}
/**
 * [get_image_size function that gets an atachment image on any size, based on either post (get featured image, or by url)]
 * @method get_image_size
 * @param  [object/string]         $mixed [post or image url]
 * @return [string]                [url to the image size]
 */
function get_image_size($mixed, $size='full') {

	/* get post featured image */
	if($mixed->ID) :
		$attachment = featured_image($mixed, $size) ;
	/* get atachemnt image size based from url */
	else :
		global $wpdb;
		$attachmentID = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $mixed ));
		$attachmentID = $attachmentID[0];
		$attachment = featured_image($attachmentID, $size) ;
	endif;

	return $attachment;
}
function get_image_id($url) {
	global $wpdb;
	$attachmentID = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ));
	$attachmentID = $attachmentID[0];
	return $attachmentID;
}




/**
 * [spinal_get_home_news_posts get posts marked as featured in highlight for home page in post settings]
 * @method spinal_get_home_news_posts
 * @return [array]                     [returns array list of posts to be  used on homepage]
 */
function spinal_get_home_news_posts($nr = 4) {
	/* slider loop slides */
	$theme_options = spinal_get_theme_option();
	/* get slides from theme options in home */


	/* get all featured news posts for inclussion in slider */
	$newsPostsArgs = array(
		'post_type' => 'post',
		'posts_per_page' => $nr,
		'order_by' => 'date',
		'order' => 'DESC',
		'meta_query' => array(
				array(
					'key' => 'post-featured-news',
					'value' => 'on',
				),
		),
	);
	$newsPosts = new WP_Query($newsPostsArgs);
	$newsPosts = $newsPosts->posts;
	/* setup our posts to be ready for slider inclussion */

	foreach ($newsPosts as $key => $postFeatured) {
		/* get post id */

		$featuredID = $postFeatured->ID;
		/* setup our array */

		$newsArray[$featuredID]['link'] =  get_permalink($featuredID);
		$newsArray[$featuredID]['name'] = $postFeatured->post_title;
		$newsArray[$featuredID]['date'] = get_the_time('d/m/Y', $postFeatured);
		$newsArray[$featuredID]['description'] = substr(strip_tags($postFeatured->post_content), 0, 100);
		$newsArray[$featuredID]['image'] = featured_image($postFeatured, 'spinal-thumb-blog');
	}
	/* end featured posts setup */

	/* merge slides from home slider options and featured news */
	//$slides = array_merge($slidesArray ,$slides);
	return $newsArray;
}
/**
 * [spinal_get_home_news_posts get posts marked as featured in highlight for home page in post settings]
 * @method spinal_get_home_news_posts
 * @return [array]                     [returns array list of posts to be  used on homepage]
 */
function spinal_get_testimonials($nr = 4) {
	/* get all featured news posts for inclussion in slider */
	$testimonialsPostsArgs = array(
		'post_type' => 'testimonials',
		'posts_per_page' => $nr,
		'order_by' => 'date',
		'order' => 'DESC'
	);
	$testimonialsPosts = new WP_Query($testimonialsPostsArgs);
	$testimonialsPosts = $testimonialsPosts->posts;
	/* setup our posts to be ready for slider inclussion */

	foreach ($testimonialsPosts as $key => $postFeatured) {
		/* get post id */

		$featuredID = $postFeatured->ID;
		/* get post meta */
		$postMeta = get_post_meta($featuredID);
		/* setup our array */

		$testimonialsArray[$featuredID]['link'] =  get_permalink($featuredID);
		$testimonialsArray[$featuredID]['name'] = $postMeta['name'][0];
		$testimonialsArray[$featuredID]['description'] = $postMeta['description'][0];
		$testimonialsArray[$featuredID]['image'] = featured_image($postFeatured, 'spinal-thumb-100');
	}
	/* end featured posts setup */

	/* merge slides from home slider options and featured news */
	//$slides = array_merge($slidesArray ,$slides);
	return $testimonialsArray;
}

/**
 * [spinal_get_home_news_posts get posts marked as featured in highlight for home page in post settings]
 * @method spinal_get_home_news_posts
 * @return [array]                     [returns array list of posts to be  used on homepage]
 */
function spinal_get_gallery($nr = 4) {
 	/* get all featured news posts for inclussion in slider */
 	$galleryPostsArgs = array(
 		'post_type' => 'gallery',
 		'posts_per_page' => $nr,
 		'order_by' => 'date',
 		'order' => 'DESC'
 	);
 	$galleryPosts = new WP_Query($galleryPostsArgs);
 	$galleryPosts = $galleryPosts->posts;
 	/* setup our posts to be ready for slider inclussion */

 	foreach ($galleryPosts as $key => $post) {
 		/* get post id */

 		$postID = $post->ID;
 		/* get post meta */
 		$postMeta = get_post_meta($postID);
 		/* setup our array */
		$galleryArray[$postID]['title'] =  get_the_title($postID);
 		$galleryArray[$postID]['link'] =  get_permalink($postID);
 		$galleryArray[$postID]['gallery'] = prepare_video_array(unserialize($postMeta['videos'][0]));
 		$galleryArray[$postID]['description'] = $postMeta['description'][0];

 	}
 	/* end featured posts setup */

 	/* merge slides from home slider options and featured news */
 	//$slides = array_merge($slidesArray ,$slides);
 	return $galleryArray;
 }


function get_all_pages() {
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    $pages = new WP_Query($args);
    wp_reset_postdata();
    return $pages->posts;
}
function get_all_page_templates() {
    $allPages = get_all_pages();
    foreach ($allPages as $key => $page) {
        $arr[get_page_template_slug($page->ID)]['url'] = get_permalink($page->ID);
        $arr[get_page_template_slug($page->ID)]['id'] = $page->ID;
        $arr[get_page_template_slug($page->ID)]['name'] = $page->post_title;
    }
    unset($arr['']);
    return $arr;
}


function slider($template, $nr = 4) {
	$slides = spinal_get_slides();

    /* check if we have slides */
    if($slides) :
        /* counter limit */
        $n = 0;
        /* loop through the slides */
        foreach ($slides as $key => $slide) :
            /* limit slides */
            if($n < $nr) :

                $image = $slide['url'];
                $link = $slide['link'];
                $title = $slide['title'];
                $caption = $slide['caption'];

                /* simplified slider use of dinamic template */
                $search = array(
                    '[src]', '[link]', '[title]', '[caption]'
                );
                $replace = array(
                    $image, $link, $title, $caption
                );
                $slidesString .= str_replace($search, $replace, $template);
            endif;
        $n++;
        endforeach;
		return $slidesString;
    endif;
	return false;
}
function spinal_get_slides_options() {
	/* slider loop slides*/
	if(function_exists('spinal_get_theme_option')) :
		$theme_options = spinal_get_theme_option();
	else :
		return false;
	endif;
	/* get slides from theme options in home */
	$slides = $theme_options['slider'];

	if(is_array($slides)) :
		/* loop slides */
		foreach ($slides as $key => $slide) :
			$slideKey = $key*100;
			$slideTitle = $slide['title'];
			$slideCaption = $slide['caption'];
			$slideImage = $slide['url'];
			$linkName = $slide['link-name'];
			$link = $slide['link'];

			$slideImageResize = get_image_size($slideImage, 'spinal-full-slide');

			$slidesArray[$slideKey]['url'] = $slideImageResize;
			$slidesArray[$slideKey]['link'] =  $link;
			$slidesArray[$slideKey]['linkname'] =  $linkName;
			$slidesArray[$slideKey]['title'] = nl2br($slideTitle);
			$slidesArray[$slideKey]['caption'] = $slideCaption;
		/* end loop */
		endforeach;

		return $slidesArray;
	/* end slides check */

	endif;
	/* return empty array if no slides are set */
	return array();
}
function spinal_get_slides() {
	/* get slides from theme options */
	$slidesOptions = spinal_get_slides_options();

	/* get all featured news posts for inclussion in slider */
	$newsPostsArgs = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'meta_query' => array(
				array(
					'key' => 'post-featured',
					'value' => 'on',
				),
		),
	);
	$newsPosts = new WP_Query($newsPostsArgs);
	$slidesPosts = $newsPosts->posts;
	/* setup our posts to be ready for slider inclussion */
	$slidesArray = array();
	foreach ($slidesPosts as $key => $postFeatured) {
		/* get post id */

		$featuredID = $postFeatured->ID;
		/* setup our array */
		$slidesArray[$featuredID]['url'] = featured_image($postFeatured, 'spinal-full-slide');
		$slidesArray[$featuredID]['link'] =  get_permalink($featuredID);
		$slidesArray[$featuredID]['title'] = $postFeatured->post_title;
		$slidesArray[$featuredID]['caption'] = substr( strip_tags($postFeatured->post_content), 0, 120);
	}
	/* end featured posts setup */

	/* merge slides from home slider options and featured news */
	//$slides = array_merge($slidesArray ,$slides);
	return array_merge($slidesArray, $slidesOptions);
}

/**
 * [loop_posts reusable loop of posts, providing just a wordpress query object]
 * @method loop_posts
 * @param  [wp_query]     $query [wp_query set up with all the filters one could want]
 * @return [string]            [returns a string of the loop as a variable]
 */
function loop_posts($query) {
	ob_start();
	if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
		global $post;
		/* post title */
		$title = get_the_title();
		/* post url */
		$url = get_the_permalink();
		/* post image */
		$image =  featured_image($post, 'spinal-thumb-blog');
		//wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

		$image = $image ? $image : false;
		/* post excerpt */
		$excerpt = substr(get_the_excerpt(), 0, 84);
		/* post date */
		$date = get_the_time('d/m/y');

		/* setup our link,, option in admin for custom linked article support */
		$postMeta = get_post_meta($post->ID);
		$customMetaLink = $postMeta['linked-post'][0];
		$link = $customMetaLink ? $customMetaLink : get_permalink($post->ID);
	?>

			<div class="news-item cf">
				<div class="img-holder col-lg-3 col-md-3 col-sm-4 col-xs-5">
					<?php if($image) :  ?>
					<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" />
					<!-- / featured image -->
					<?php endif; ?>
				</div>
				<div class="summary col-lg-9 col-md-9 col-sm-8 col-xs-7">
					<a href="<?php echo $link; ?>">
						<h6 class="title"><?php the_title(); ?></h6>
					</a>
					<p><?php the_excerpt(); ?></p>
					<a href="<?php echo $url ?>" class="btn-plus"></a>
				</div>
			</div>

	<?php
	/* if we cant find anything inform the user */
	endwhile; else :
	?>


	<?php endif;
	wp_reset_postdata();
	 ?>
	<!-- pagination -->
	 <div class="pagination cf">
			<ul class="list-inline">
			<?php
			/* add pagination to custom wp_query */
			spinal_page_navi($query);
			?>
		</ul>
	</div>
	<!-- / pagination -->
<?php
	/* return captured data in buffer */
	return ob_get_clean();
}




/* add custom capabilities to the bbpress users so we can make custom roles */


?>
