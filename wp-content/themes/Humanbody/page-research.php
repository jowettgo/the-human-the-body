<?php
/*
 Template Name: Research
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



$featuredImage = featured_image($post, 'spinal-full-featured');
$postMeta = get_post_meta($post->ID);
$featuredAlign = $postMeta['align-featured'][0];
$pageTemplates = get_all_page_templates();
global $post;
$post = get_page_by_path('research');
$title = get_the_title($post->ID);
$content = apply_filters('the_content', $post->post_content);
$postMeta = get_post_meta($post->ID);
$short = $postMeta['short-description'][0];
?>

 <?php get_header(); ?>
 <div id="content">

	<!-- start research section  -->
	<div class="research-section">
		<!-- start research top inner -->
		<div class="research-top-inner">
			<div class="container">
				<!-- Start Bredcrumb -->
				<div class="breadcrumb-wrapper">

					<ol vocab="http://schema.org/" typeof="BreadcrumbList">
						<?php spinal_breadcrumb(); ?>
					</ol>

				</div>
				<!-- End Bredcrumb -->

				<div class="row">
					<!-- Start description wrapper -->
					<div class="description-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h1><?php echo $title ?></h1>
						<p>
						    <?php echo $content; ?>
						</p>

					</div>
					<!-- End description wrapper -->
				</div>
			</div>
		</div>
		<!-- end research top inner -->

		<!-- start research top inner -->
		<div class="research-bottom-inner">

			<div class="container">
				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 research-right-wrap">
						<p>
							<?php echo $short ?>
						</p>
					</div>
				</div>
			</div>

		</div>
		<!-- end research top inner -->
	</div>
	<!-- end research section  -->

	<!-- start category section  -->
	<div class="category-section clearfix">
        <?php
        $args = array(
            'post_type' => 'research',
            'posts_per_page' => -1,
            'order_by' => 'date',
            'order' => 'DESC'
        );
        $researches = new WP_Query($args);
        if($researches->have_posts()) :
            while($researches->have_posts()) :
                $researches->the_post();
                $meta = get_post_meta($post->ID);

                $image = featured_image($post, 'spinal-thumb-blog-featured')
        ?>
        		<div class="col-20-percent" style="background-image: url(<?php echo $image ?>)">

        			<div class="section-inner">
        				<h2><?php the_title() ?></h2>
        				<p style="height: 72px;">
        					<?php echo $meta['short-description'][0] ?>
        				</p>

        				<a href="<?php echo get_the_permalink($post->ID) ?>" class="view-area">View Area</a>
        			</div>
        		</div>
        <?php
            endwhile;
        endif;
        ?>

	</div>
	<!-- end category section  -->
</div>

<?php get_footer(); ?>
