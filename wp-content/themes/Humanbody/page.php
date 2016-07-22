<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

global $post;
$featuredImage = featured_image($post, 'spinal-full-featured');
$postMeta = get_post_meta($post->ID);
$pageTemplates = get_all_page_templates();
global $wp_query;

$vars = $wp_query->query_vars;
?>
<?php
/* BBPRESS TOPIC FORUM REPLY ACTION HOOK
------------------------------------------------------------*/
if($vars['post_type'] == 'forum' || $vars['post_type'] == 'topic' || $vars['post_type'] == 'community') :
    do_action('bbp-forums');
    /* get post meta */
    $postmeta = get_post_meta($post->ID);
    /* add hook to the meetings templates */
    if($vars['forum'] == 'meetings' || $postmeta['type'][0] == 'meeting') :

        do_action('bbp-meetings', $post);
    endif;
endif;

/* get the header */
get_header();

/* BBPRESS TOPIC FORUM REPLY
------------------------------------------------------------*/
if($vars['post_type'] == 'forum' || $vars['post_type'] == 'topic' || $vars['post_type'] == 'community') :
    the_content();

/* BBPRESS SEARCh
------------------------------------------------------------*/
elseif( $_POST['action'] == 'search-request') :
    the_content();

/* Default page no template
------------------------------------------------------------*/
else :
?>
            <div id="main">
 			    <!--Static Headline -->
 			    <section class="headline">
                     <div class="static-header" style="background-image: url(<?php echo $featuredImage ?>);">
                         <div class="container">
                             <div class="row">
                                 <h2><?php the_title() ?></h2>
                             </div>
                         </div>
                     </div>
 			    </section>
 			    <!-- End Static headline -->

 			    <!-- Breadcrumbs -->
 			    <div class="container">
                     <div class="row">
                         <div class="breadcrumbs">
                             <ul>
                                 <?php spinal_breadcrumb(); ?>
                             </ul>
                         </div>
                     </div>
                 </div>
                 <!-- End breadcrumbs -->


                 <div class="container">
                 	<div class="row">
                 		<div class="page-content cf">
                 			<div class="page-content-wrapper">
 		                		<!-- heading-section	 -->
 				                <div class="heading-section">

 				                	<?php the_content() ?>
 				                </div>
 				                <!-- end heading-section -->


                 			</div>
                 			<!-- end page-content-wrapper -->
                 		</div>
                 		<!-- end page-content -->
                 	</div> <!-- end row -->
                 </div> <!-- end container -->
 			</div>


<?php endif; ?>
 <?php get_footer(); ?>
