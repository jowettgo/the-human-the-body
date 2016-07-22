<?php
global $post;
$featuredImage = featured_image($post, 'spinal-full-featured');

$postMeta = get_post_meta($post->ID);
$featuredAlign = $postMeta['align-featured'][0];
$pageTemplates = get_all_page_templates();
$url = $_GET['s'] ? get_site_url().'?s='.$_GET['s'] : $pageTemplates['page-blog.php']['url'];


$category = get_the_category();
$meta = get_post_meta($post->ID);
$color1 = $meta['color1'][0];
$color2 = $meta['color2'][0];
$cat_image = $meta['image'][0];

$featured_image = featured_image($post, 'large');
 ?>
 <div id="content" class="health research-subpage" style="background-image:url(<?php echo $featured_image ?>)">
     <div class="category-color" style="background: <?php echo $color1 ?>;
         background: -moz-linear-gradient(-45deg, <?php echo $color1 ?> 0%, <?php echo $color2 ?> 100%);
         background: -webkit-linear-gradient(-45deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
         background: linear-gradient(135deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
         filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color1 ?>', endColorstr='<?php echo $color2 ?>',GradientType=1 );">
     </div>
 	<!-- start research section  -->
 	<div class="health-section">
 		<!-- start research top inner -->
 		<div class="subpage-top-inner">
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

 						<h1>
 							<i class="h1-logo health-logo"></i>
 							<span><?php the_title() ?></span>
 						</h1>

 						<p>
 							<?php echo apply_filters('the_content', $meta['description'][0] )?>
 						</p>

 					</div>
 					<!-- End description wrapper -->
 				</div>
 			</div>
 		</div>
 		<!-- end research top inner -->

 		<!-- start research top inner -->
 		<div class="subpage-bottom-inner" style="position:relative;background: <?php echo $color1 ?>;
            background: -moz-linear-gradient(-45deg, <?php echo $color1 ?> 0%, <?php echo $color2 ?> 100%);
            background: -webkit-linear-gradient(-45deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
            background: linear-gradient(135deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color1 ?>', endColorstr='<?php echo $color2 ?>',GradientType=1 );">

 			<div class="container">
 				<div class="row">
 					<h3>Useful Links</h3>
 					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-left">
 						<a href="<?php echo $meta['link'][0] ?>" class="pull-right visit-website"><i></i><span>Visit Website</span></a>
 					</div>

 					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right">
 						<a href="<?php echo $meta['donate'][0] ?>" class="pull-left make-donation"><i></i><span>Make a donation</span></a>
 					</div>
 					<div class="col-md-12 col-sm-12 col-xs-12">
 						<p>
 							<?php echo $meta['note-description'][0] ?>
 						</p>
 					</div>
 				</div>
 			</div>

 		</div>
 		<!-- end research top inner -->
 	</div>
 	<!-- end research section  -->

 </div>
