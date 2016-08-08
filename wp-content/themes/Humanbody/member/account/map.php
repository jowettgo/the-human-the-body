<?php
function renderInterests()
{
	global $wpdb;
	$wpdb->interests = $wpdb->prefix . 'interests_types';
	$wpdb->interest_cat = $wpdb->prefix . 'interests_categories';
	$items = $wpdb->get_results("SELECT * FROM $wpdb->interests as t LEFT JOIN $wpdb->interest_cat as c ON t.category_id=c.ID ORDER BY t.category_id ASC");
	echo "<select class='select2' multiple='multiple' style='width:100%;'>";
	$lastCat = '';
	foreach ($items as $item) {
		if($item->category != $lastCat) {
			if ($lastCat!='') echo "</optgroup>";
			$newCat = true; $lastCat = $item->category;
		} else { $newCat = false; }
		if($newCat) echo "<optgroup label='$lastCat'>";
		echo "<option value='$item->id'>$item->interest_type</option>";
	}
	echo "</select>";
}
?>
<div id="content" class="secondary-subpage map">

	<!-- cont-utilizator-section -->
	<div class="cont-utilizator-section">
		<!-- Start top-banner-wrapper -->
		<div class="top-banner-wrapper">

			<div class="container">

				<!-- Start cont-wrapper -->
				<div class="cont-wrapper">
					<div class="row">
						<?php
							

						?>
						<!-- start profile -->
						<div class="col-md-10 col-md-offset-1">
							<?php include_once('account-topbar.php') ?>
						</div>
						<!-- start profile -->
						<div class="col-md-12 messages-table">
							<div class="title-wrapper">
								<h1>Map</h1>
								<p>You can search for other users by means of 1, 2 or all of the 3 filters: medical conditions, interests and geographical location. For results to be displayed, a user must meet at least one criteria from any of the used filters.</p>
							</div>
							<div class="row search-profile map-search-affections">
								<div class="col-md-3 col-sm-4 col-xs-12">
									<div class="dropdown-title">
										I. Conditions
										<p>Select any conditions of interest</p>
									</div>
									<div class="dropdown-outer-wrapper">
										<div class="ui fluid multiple search selection dropdown">
											<div class="selected-title">Selected conditions</div>
											<input type="hidden" name="affections" id="main-affections-map"  value="">

											<div class="default text">Search for conditions</div>
											<div class="menu transition visible general-list">
												<?php

												$affections = new csv_import_affections();
												$affections = $affections->get_affections();
											   //$ilist = csv_import_interests::get_interests();

												foreach ($affections as $affection) :

													$value = $affection->affection;
												$id = $affection->ID;
												?>
												<div class="item" data-value="<?php echo $value ?>"><?php echo $value ?></div>
												<?php
												endforeach;
												?>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-9 col-sm-12 col-xs-12">
									<div class="dropdown-title">
										INTERESTS
										<p>Select any interests or hobbies</p>
									</div>
									<?php renderInterests(); ?>
									<div class="row" style="display: none;">
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="dropdown-title mobile-too">
												II. Categories
												<!-- <?php echo $int['interest_1_title'] ?> -->
											</div>
										</div>

										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="dropdown-title">
												SUBCATEGORIES
											</div>
										</div>

										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="dropdown-title">
												PARTICULAR
											</div>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="dropdown-title-desc">
												<p>This filter applies only to premium members. Please select a category from the first box for each interest. Then, please choose a corresponding subcategory from the second box and a subsequent particular interest from the third box. Alternatively, you can search for a particular interest directly from the third box using the search bar.</p>
												<!-- <?php echo $int['interest_1_title'] ?> -->
											</div>
										</div>


										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="dropdown-outer-wrapper">
												<div class="ui fluid multiple search selection dropdown">
													<div class="selected-title">Selected categories</div>
													<input type="hidden" name="interests-1" id="main-interest"  value="">

													<div class="default text">Search for interests</div>
													<div class="menu transition visible general-list">
														<?php
														$ilist = csv_import_interests::get_interests();

														foreach ($ilist as $interestobject) :

															$value = $interestobject->interest;
														$id = $interestobject->ID;
														?>
														<div class="item" data-value="<?php echo $id ?>"><?php echo $value ?></div>
														<?php
														endforeach;
														?>
													</div>
												</div>
											</div>
										</div>


										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="dropdown-title mobile-only">
												SUBCATEGORIES
											</div>
											<div class="dropdown-outer-wrapper">
												<div class="ui fluid multiple search selection dropdown">
													<div class="selected-title">Selected subcategories</div>
													<input type="hidden" name="interests-2" id="interest-categories" value="">

													<div class="default text">Search for interests</div>
													<div class="menu transition visible general-list">


													</div>
												</div>
											</div>
										</div>


										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="dropdown-title mobile-only">
												PARTICULAR
											</div>
											<div class="dropdown-outer-wrapper">
												<div class="ui fluid multiple search selection dropdown">
													<div class="selected-title">Selected particular interests</div>
													<input type="hidden" name="interests-3" id="interest-types"  value="">

													<div class="default text">Search for interests</div>
													<div class="menu transition visible general-list">


													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-12">
									<div class="dropdown-title mobile-too">
										III. Location
										<p>You can select either just a country or land or also any cities within their boundaries</p>
									</div>
								</div>
							</div>

							<div id="mapdiv" style="width: 100%; height: 740px;"></div>
							<input type="hidden" id="map-country">

							<?php
							/* MAP FILTERS
							---------------------------------------------------------------- */
							$int = get_option('interests-options');
							$co = new csv_import_cities();

							?>
							<form class="search-profile" method='post'>
								<div class="row ">
									<div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
										<div class="dropdown-outer-wrapper">
											<div class="dropdown-title ajax-country-name">
												CITIES FROM
											</div>
											<div class="ui fluid multiple search selection dropdown map-ajax-cities">
												<div class="selected-title">Selected cities</div>
												<input type="hidden" name="city" id="cities-selected">
												<div class="default text">Search for a city</div>
												<div class="menu transition visible general-list">

												</div>
											</div>
										</div>
									</div>




								</div>
								<div class="row">
									<input type="submit" value="apply filters" class="apply-filters">
								</div>
							</form>

							<?php
							/* END MAP FILTERS
							---------------------------------------------------------------- */
							?>
						</div>

						<div class="col-md-12">
							<div class="table-section messages-table map-list-table friend-list-table">
								<div id="no-more-tables" class="clearfix">
									<table class="table-condensed clearfix">
										<tbody id="users-found">

										</tbody>
									</table>
								</div>
							</div>

						</div>
					</div>
					<!-- End row -->
				</div>
				<!-- End cont-wrapper -->
			</div>
			<!-- End container -->
		</div>
		<!-- End top-banner-wrapper -->
	</div>
	<!-- cont-utilizator-section -->
</div>
<?php
/* INVITE TO GET TOGETHER
-------------------------------------------- */
?>
<!-- start avatar popup -->
<div id="fancybox-get-together" class="avatar-popup">
	<h3>Invite to get-together</h3>
	<!-- start form -->
	<form method="post" id="get-together-form" class="sing-up-general">
		<br>
		<div class="row">
			<div class="col-sm-12 col-md-8 col-md-offset-2">
				<label>Select meeting</label>
				<input type="hidden" name="u" id="get-together-input" value="">
				<div class="ui selection dropdown" id="meeting">
					<input type="hidden" name="meeting">
					<i class="dropdown icon"></i>
					<div class="default text">Select meeting</div>
					<div class="menu">
						<?php
						$meeting = get_page_by_path( 'meetings', 'OBJECT', 'forum' );
						$meetingID = $meeting->ID;

						$args = array(
							'post_type' => 'topic',
							'posts_per_page' => -1,
							'post_parent' =>$meetingID,
							'orderby' => array(
								'meta_key' => '_bbp_voice_count',
								'meta_value_num' => 'DESC'
								),
							);
						$topics = new WP_Query($args);
						$flo = new friends_list();
						if($topics && $topics->have_posts()) :
							while($topics->have_posts()) :
								$topics->the_post();
							?>

							<div class="item" data-value="<?php echo $flo->encrypt($post->ID) ?>" data-text="<?php the_title() ?>">
								<?php the_title() ?>
							</div>

							<?php
							endwhile;
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
			<br>
			<br>
			<div class="row">

				<div class="col-md-12">
					<input type="submit" value="Invite" />
				</div>

			</div>

		</form>
		<!-- end form -->
	</div>
	<!-- end avatar popup -->
	<?php
/* END INVITE TO GET TOGETHER
-------------------------------------------- */
?>
