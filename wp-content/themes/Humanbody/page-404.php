<?php
/*
 Template Name: 404
*/
global $wp_query;
/* get header */
get_header();
 ?>
 <div id="main">
 	 <div class="container">
 		<div class="row">
 			<div class="page-content cf"   style="margin-top:120px;">
 				<div class="page-content-wrapper">
 					<!-- heading-section	 -->
 					<div class="heading-section">

					
						<h1 class="text-center">Page not found</h1>
						<hr>
						<div class="return text-center">
						   <a href="<?php echo get_site_url() ?>" class="return-to-blog">Return Home</a>
						</div>
 					</div>
 					<!-- end heading-section -->
 				</div>
 				<!-- end page-content-wrapper -->
 			</div>
 			<!-- end page-content -->
 		</div> <!-- end row -->
 	 </div> <!-- end container -->
 </div>

<?php
/* meine footer */
get_footer();

?>
