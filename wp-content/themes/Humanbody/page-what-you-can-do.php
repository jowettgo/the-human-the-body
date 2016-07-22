<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/*
 Template Name: What You can do
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/

global $post;
$featuredImage = featured_image($post, 'spinal-full-featured');
$postMeta = get_post_meta($post->ID);
$featuredAlign = $postMeta['align-featured'][0];
$pageTemplates = get_all_page_templates();

$forumID = get_page_by_path('forum','OBJECT', 'forum')->ID;
$supportID = get_page_by_path('support-group', 'OBJECT', 'forum')->ID;
$meetingsID = get_page_by_path('meetings', 'OBJECT', 'forum')->ID;
$catdone = get_category_by_slug( 'what-had-been-done' );
$catdo = get_category_by_slug( 'what-you-can-do' );
$catdone_url = get_term_link($catdone );
$catdo_url = get_term_link($catdo );



if($_POST['add-idea']) :
    global $current_user;
    if($current_user->has_cap('administrator')) {
        $post_status = 'publish';
    }
    else {
        $post_status = 'pending';
    }
    $idea = array(
        'post_type' => 'idea',
        'post_title'    => wp_strip_all_tags( $_POST['idea_title'] ),
        'post_content'  => $_POST['idea_content'],
        'post_status'   => $post_status,
    );

    // Insert the post into the database
    $id = wp_insert_post( $idea );
    if($id > 0 ) :
        /* succsess */
        update_post_meta( $id, 'idea-author', $_POST['idea_author']);
        update_post_meta( $id, 'description', $_POST['description']);
        $message = 'Thank you, your idea has been submited!';
    endif;
endif;







?>

 <?php get_header(); ?>
 <div id="content">

	<!-- start what-can section  -->
	<div class="what-can-section">
		<!-- start what-can top inner -->
		<div class="what-can-top-inner">
			<div class="container">
				<!-- Start Bredcrumb -->
				<div class="breadcrumb-wrapper">

					<ol vocab="http://schema.org/" typeof="BreadcrumbList">
    					<?php spinal_breadcrumb() ?>
					</ol>

				</div>
				<!-- End Bredcrumb -->

				<div class="row">
					<!-- Start description wrapper -->
					<div class="description-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h1><?php the_title() ?></h1>

						<p>
							<?php the_content() ?>
						</p>

					</div>
					<!-- End description wrapper -->
				</div>
			</div>
		</div>
		<!-- end what-can top inner -->

		<!-- start what-can top inner -->
		<div class="what-can-bottom-inner">

			<div class="container">
				<div class="row">
                    <div class="col-lg-12">
                        <div class="idea-submit-message">
                            <p>
                                <?php echo $message ?>
                            </p>
                        </div>
                    </div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-left">
						<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2">
							<a class="submit-idea" href="#fancybox-add-idea"><i class="fa fa-lightbulb-o"></i>submit your idea</a>

                            <div id="fancybox-add-idea" class="avatar-popup">
                            	<h3>Add new Idea</h3>
                            	<div class="avatar-inner">
                            		<form id="add-topic" class="sing-up-general clearfix" method="POST">
                                        <div class="col-md-6">
                            				<label for="topic">Fullname</label>
                            				<input type="text" id="topic" name="idea_author">
                            			</div>
                            			<div class="col-md-6">
                            				<label for="topic">Idea Title</label>
                            				<input type="text" id="topic" name="idea_title">
                            			</div>
                                        <div class="col-md-12">
                            				<label for="description">Idea Description</label>
                            				<textarea name="description" id="description" rows="6" style="min-height:80px"></textarea>
                            			</div>
                            			<div class="col-md-12">
                            				<label for="idea-full">Full Idea</label>
                            				<textarea name="idea_content" id="idea-full"></textarea>
                            			</div>
                            			<div class="col-md-12">
                            				<input type="submit" name="add-idea" value="Add Idea">
                            			</div>
                            		</form>
                            	</div>
                            </div>

						</div>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right">
						<!-- Start search wrapper -->
						<div class="search-wrapper col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
							<form class="searchbox" action="#">
								<input type="text" id="search-ideas" placeholder="Search for ideas...">
								<input type="submit">
							</form>
						</div>
						<!-- End search wrapper -->
					</div>
				</div>
			</div>

		</div>
		<!-- end what-can top inner -->
	</div>
	<!-- end what-can section  -->

	<!-- start ideas section  -->
	<div class="ideas-section clearfix">

		<?php
        $args = array(
            'post_type' => 'idea',
            'posts_per_page' => -1,
            'order_by' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        );
        $ideas = new WP_Query($args);

        if($ideas->have_posts()) :
            while ($ideas->have_posts()) {
                $ideas->the_post();
                $meta = get_post_meta($post->ID);
                $author = $meta['idea-author'][0];
                $description = get_the_description();
                $title = get_the_title($post->ID);
                $image = featured_image($post);
                ?>
                <div class="col-20-percent" <?php echo $image ? 'style="background-image:url('.$image.')"'  : 'style="background-image:url('._IMG_.'lights.png)"'; ?>>
        			<a href="<?php the_permalink() ?>"></a>

        			<div class="section-inner">
        				<h4><?php echo $title ?></h4>
        				<p>
        					<?php echo $description ?>
        				</p>
        				<div class="posted-by">
        					Posted by <strong><?php echo $author ?></strong>
        				</div>
        			</div>
        		</div>
                <?php
            }
        endif;
         ?>
         <div class="col-20-percent  empty filler" <?php echo 'style="background-image:url('._IMG_.'bolb.png)";'; ?>>
 			<a href="javascript:void(0)"></a>

 			<div class="section-inner future-idea">
 				<div class="light-wrapper">
 					<img src="<?php echo _IMG_ ?>lightbolb.png" alt="img">
 				</div>
 				<h2>Future Idea</h2>
 				<p>
 					Ideas that are not born yet
 				</p>
 			</div>
 		</div>

	</div>
	<!-- end ideas section  -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php  ?>
            </div>
        </div>
    </div>
</div>


        <?php wp_footer(); ?>
        </div>
    </body>
</html> <!-- end of site. what a ride! -->
