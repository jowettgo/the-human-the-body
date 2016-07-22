<?php
global $post;
setPostViews($post->ID);
$featuredImage = featured_image($post, 'spinal-full-featured');

$postMeta = get_post_meta($post->ID);
$featuredAlign = $postMeta['align-featured'][0];
$pageTemplates = get_all_page_templates();
$url = $_GET['s'] ? get_site_url().'?s='.$_GET['s'] : $pageTemplates['page-blog.php']['url'];


$category = get_the_category();
if(is_array($category)) :

endif;

$catID =$category[0]->cat_ID;
$meta = get_term_meta($catID);

$cat_image = $meta['image'][0];
$link = get_the_permalink($post->ID);
$featured_image = featured_image($post, 'large');


$custom_fields = get_post_custom($post->ID);
$banner1_link = $custom_fields['ba_banner1_link'];
$banner1_img = unserialize($custom_fields['ba_banner1_image'][0]);
$banner2_link = $custom_fields['ba_banner2_link'];
$banner2_img = unserialize($custom_fields['ba_banner2_image'][0]);


$description = apply_filters('the_content', $postMeta['description'][0]);

$advice = apply_filters('the_content', $postMeta['Advice'][0]);
$causes = apply_filters('the_content', $postMeta['causes'][0]);
$signs = apply_filters('the_content', $postMeta['signs'][0]);
$myths = unserialize($postMeta['myths'][0]);

$statistics = apply_filters('the_content', $postMeta['statistics'][0]);
$know = apply_filters('the_content', $postMeta['know'][0]);

$color1 = 'rgb(126, 126, 126)';
$color2 = 'rgb(66, 66, 66)';
 ?>
 <!-- start content -->
 <div id="content">

 	<!-- start what-can section  -->
 	<div class="secondary-subpage diseases" style="background-image:url('<?php echo featured_image($post, 'large') ?>');position:relative;">
        <!-- <div class="category-color" style="background: <?php echo $color1 ?>;
            background: -moz-linear-gradient(-45deg, <?php echo $color1 ?> 0%, <?php echo $color2 ?> 100%);
            background: -webkit-linear-gradient(-45deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
            background: linear-gradient(135deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color1 ?>', endColorstr='<?php echo $color2 ?>',GradientType=1 );">
        </div> -->
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
 						<br class="hidden-xs">
 						<br class="hidden-xs">
 					</div>
 					<!-- End description wrapper -->
 				</div>
 			</div>
 		</div>
 		<!-- end what-can top inner -->
        <?php if($description) : ?>
 		<!-- start what-can top inner -->
 		<div class="what-can-bottom-inner">
 			<div class="container">
 				<div class="row">
 					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
 						<?php echo $description ?>
 					</div>
 				</div>
 			</div>

 		</div>
 		<!-- end what-can top inner -->
        <?php endif; ?>
 	</div>
 	<!-- end what-can section  -->
 	<!-- start body section -->
 	<div class="body-section">
 		<div class="container">
 			<div class="row">
 				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

 					<!-- start banner -->
 					<div class="article-banner cf">
                        <?php 
                        if(!empty($banner1_img['url'])){ ?>
                        <a target="_blank" style="background: url(<?php echo $banner1_img['url'] ?>);" href="<?php echo $banner1_link[0] ?>"></a>
                        <?php }
                        ?>
                        <?php 
                        if(!empty($banner2_img['url'])){ ?>
                        <a target="_blank" style="background: url(<?php echo $banner2_img['url'] ?>);" href="<?php echo $banner2_link[0] ?>"></a>
                        <?php }
                        ?>
                    </div>
 					<!-- end banner -->

                    <?php if($causes) : ?>
 					<!-- start advice -->
 					<div class="advice-wrapper causes-override general-content-wrapper">
 						<div class="article-separator brown">
 							<h5 class="divider">Causes</h5>
 						</div>
 						<div class="paragraph-wrapper">
 							<?php echo $causes ?>
 						</div>
 					</div>
 					<!-- end advice -->
                    <?php endif; ?>

                    <?php if($signs) : ?>
 					<!-- start advice -->
 					<div class="advice-wrapper signs-override general-content-wrapper">
 						<div class="article-separator brown">
 							<h5 class="divider">Symptoms and signs</h5>
 						</div>
 						<div class="paragraph-wrapper">
 							<?php echo $signs ?>
 						</div>
 					</div>
 					<!-- end advice -->
                    <?php endif; ?>


                    <?php if($advice) : ?>
 					<!-- start advice -->
 					<div class="advice-wrapper advice-override general-content-wrapper">
 						<div class="article-separator brown">
 							<h5 class="divider">Advice</h5>
 						</div>
 						<div class="paragraph-wrapper">
 							<?php echo $advice ?>
 						</div>
 					</div>
 					<!-- end advice -->
                    <?php endif; ?>


                    <?php

                    if(is_array($myths) && count($myths) > 0) : ?>
 					<!-- start myth -->
 					<div class="myths-wrapper general-content-wrapper">
 						<div class="article-separator blue">
 							<h5 class="divider">MISCONCEPTIONS</h5>
 						</div>
 						<ul class="myths-list">

                            <?php

                            foreach ($myths as $key => $myth) {
                                $thumb = featured_image($myth['url_id'], 'spinal-thumb-200');
                                $content = apply_filters( 'the_content', $myth['content'] );
                                if($thumb) :
                                ?>
                                <li class="clearfix">
     								<div class="myth-pic col-lg-2 col-md-2 col-sm-3 col-xs-12 ">
     									<img src="<?php echo $thumb  ?>" alt="myth1">
     								</div>
     								<div class="myth-text col-lg-10 col-md-10 col-sm-9 col-xs-12">
     									<?php echo $content ?>
     								</div>
     							</li>

                                <?php
                                else :
                                ?>
                                <li class="clearfix">

     								<div class="myth-text col-lg-12 col-md-12 col-sm-12 col-xs-12">
     									<?php echo $content ?>
     								</div>
     							</li>

                                <?php
                            endif;
                            }
                        ?>
 						</ul>
 					</div>
 					<!-- end myth -->
                    <?php endif; ?>


                    <?php if($statistics) : ?>
 					<!-- start statistics -->
 					<div class="statistics-wrapper general-content-wrapper">
 						<div class="article-separator">
 							<h5 class="divider">Statistics</h5>
 						</div>
 						<div class="left-text">
 						     <?php echo $statistics ?>
 						</div>
 					</div>
 					<!-- end statistics -->
                    <?php endif; ?>
                    <?php if($know) : ?>
 					<!-- start awarness wrapper -->
 					<div class="awareness-wrapper col-lg-offset-1 col-lg-11 col-md-11 col-md-offset-1 col-sm-12">
 						<div class="awareness-inner-wrapper">
 							<span class="did-you-know">Did you know?</span>
 							<div class="awareness-text">
                                <?php echo $know ?>
 							</div>

 						</div>

 					</div>
                <?php endif; ?>
 					<!-- end awarness wrapper -->
 					<div class="support-group-wrapper clear">
                        <?php $supportID = get_page_by_path('support-group', 'OBJECT', 'forum')->ID;
                        $supportpage = get_the_permalink($supportID);
                        ?>
 						<a href="<?php echo $postMeta['support-forum'][0] ?>" class="support-group-link pull-right">See Support Group <i class="fa fa-angle-right"></i></a>
 					</div>
                    <?php $links = unserialize($postMeta['links'][0]);
                    if(is_array($links) && count($links) > 0) : ?>
 					<!-- start links wrapper -->
 					<div class="useful-links">
 						<div class="article-separator">
 							<h5 class="divider">Useful links</h5>
 						</div>
 						<ul class="preview-container clearfix row">
                            <?php

                            foreach ($links as $link) {
                                $image = featured_image($link['image_id'], 'spinal-thumb-200');
                                $title = $link['text'];
                                $link = $link['url'];
                             ?>
 							<li class="col-md-6 col-sm-6">
 								<a href="<?php echo $link ?>" class="clearfix row" target="_blank">
 									<div class="col-md-4 col-sm-4 col-xs-6 align-middle">
 										<div class="inner">
 											<img src="<?php echo $image  ?>" alt="img">
 										</div>
 									</div>
 									<div class="col-md-8 col-sm-8 col-xs-6 align-middle">
 										<span class="title"><?php echo $title ?></span>
 									</div>
 								</a>
 							</li>
                            <?php }

                             ?>
 						</ul>

 					</div>
 					<!-- end links wrapper -->
                    <?php endif; ?>

 				</div>
 			</div>
 		</div>

 	</div>
 	<!-- start body section -->

 </div>
 <!-- end content -->
