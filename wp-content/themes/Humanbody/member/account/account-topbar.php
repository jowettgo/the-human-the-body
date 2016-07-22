<?php
$userinfo = new user_info();
$info = $userinfo->get();

$messages = new user_messages();
$unreadglobal = $messages->global_unread();

$premium = $userinfo->premium;

if(!$premium) :
    $premium_notification = "premium feature";
    $premiumclass = "premium";
    $premiumURL = page('membership');
endif;
$totalPosts = $userinfo->get_post_count();

$enc_id = $userinfo->encrypt($userinfo->ID);
$profile = page('member').'?u='.$enc_id;
?>
 <?php if(!$premium) : ?>
 <div class="premium-notice">
    <a href="<?php echo page('membership') ?>">UPGRADE TO PREMIUM MEMBERSHIP AND ENJOY THE FULL BENEFITS OF THIS SECTION</a>
 </div>
 <?php else: ?>
 <div class="premium-notice">
		<?php 
			//check if trial
			$trial = get_user_meta($userinfo->ID, 'trial_premium', true); 
			
			if ($trial) {
				//days of the free trial is set in the admin panel
				$options = get_option('theme-options');
			    $trial_days = (isset($options['trial_member']) and !empty($options['trial_member']) )? intval($options['trial_member']) : 30;
				
				$date_premium = $trial + ($trial_days*24*60*60);
			} else {
				$date_premium = get_user_meta($userinfo->ID, 'date_when_became_premium', true); 
				$date_premium = $date_premium + (30*24*60*60);
			}
			
		?>
		<p>Your premium <?php echo ($trial) ? 'trial' : ''; ?> membership expires on the <strong> <?php echo date('dS', $date_premium); ?> of <?php echo date('F Y', $date_premium); ?></strong> at <strong><?php echo date('h:m A', $date_premium); ?></strong></p>
 </div> 	
 	
 <?php endif;?>	

<?php 
//if payment cancel url remove paypal_pending
if (isset($_GET['token'])) {
	delete_user_meta($user->ID, 'paypal_pending');
}
$paypal_pending = get_user_meta($user->ID, 'paypal_pending', true);
?>

<?php if(!empty($paypal_pending) and !$premium) : ?>
<div class="alert alert-danger">
    It may take a few moments before your account is being checked and updated. Please refresh the page to view upgrade.
</div>
<?php endif; ?>


<div class="user-sidebar-profile clearfix">
    <div class="user-sidebar-top col-sm-6 col-md-6">
        <div class="user-sidebar-img" style="height: 111px;">
            <div>
                <a href="<?php echo $profile ?>" title="view public profile"><img src="<?php echo $userinfo->avatar ?>" alt="avatar"></a>
            </div>
        </div>
        <a href="<?php echo $profile ?>" title="view public profile"><p><?php echo $info['name'] ?></p></a>
        <span><?php echo $info['location']['city'].', '.$info['location']['country'] ?></span>
        <?php
		$arr = array();
        $user_id = $userinfo->ID;

        $postscount = $userinfo->get_post_count($user_id);
        $postscount = (int)$postscount > 0 ? $postscount.' posts' : '';
		!empty($postscount)?array_push($arr,$postscount):'';

        $commentscount = $userinfo->get_comments_count($user_id);
        $commentscount = (int)$commentscount > 0 ?  ' ' . $commentscount.' comments' : '';
		!empty($commentscount)?array_push($arr,$commentscount):'';

        $imagescount = $userinfo->get_images_count($user_id);
        $imagescount = (int)$imagescount > 0 ? ' ' . $imagescount . ' pictures' : '';
		!empty($imagescount)?array_push($arr,$imagescount):'';
        ?>
        <span><?php echo implode(',', $arr); ?></span>
        <a class="edit" href="<?php echo page('my-account-edit') ?>"><i class="fa fa-cog"></i>Edit profile</a>

    </div>
    <div class="user-sidebar-cont col-sm-6  col-md-6">
        <div class="row">
            <ul class="cf">
                <li><a data-toggle="modal" data-target="#joined" href="#joined" class="<?php echo $premiumclass ?>" title="<?php echo $premium_notification ?>"><i class="fa"><img src="<?php echo bloginfo('template_url') ?>/library/img/crowd.svg" alt="get-togheter" width="25"></i>Joined get-togethers</a>
                <?php
                /*<li><a href="<?php echo !$premium ? $premiumURL : page('my-account-chat') ?>" class="<?php echo $premiumclass ?>" title="<?php echo $premium_notification ?>"><i class="fa fa-weixin"></i>Chat</a>

                </li>*/
                 ?>
                <li><a href="<?php echo !$premium ? $premiumURL : page('my-account-friendlist') ?>" class="<?php echo $premiumclass ?>" title="<?php echo $premium_notification ?>"><i class="fa fa-users"></i>See friends list</a>

                </li>
                <li><a href="<?php echo !$premium ? $premiumURL : page('my-account-map') ?>" class="<?php echo $premiumclass ?>" title="<?php echo $premium_notification ?>"><i class="fa fa-map-marker"></i>Map</a>

                </li>
                <li><a href="<?php echo !$premium ? $premiumURL : page('my-account-messages') ?>" class="<?php echo $premiumclass ?>" title="<?php echo $premium_notification ?>"><i class="fa fa-envelope-o"></i>Messages
                    <?php if($unreadglobal > 0) : ?>
                    <span><?php echo $unreadglobal ?></span>
                <?php endif; ?>
                </a>

                </li>
            </ul>
        </div>
    </div>
</div>

<div class="alert alert-donate">
	<div class="row">
		<div class="col-md-4">
			<p>Make a donation<p>
		</div>
		
		<div class="col-md-2">
			<?php /* paypal donation live */ ?>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="JVH7AHP5BGJDW">
			 	<input type="hidden" name="custom" value="ksx-<?php echo $user->ID; ?>">
				<input type="image" src="<?php bloginfo('stylesheet_directory'); ?>/library/img/suport.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>

			<?php /* paypal donation sandbox */
			/*			
			<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="EWUC4LNPP42UE">
				<input type="hidden" name="custom" value="ksx-<?php echo $user->ID; ?>">
				<input type="image"  src="https://www.sandbox.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
			*/ ?>

		</div>	
	</div>

	<?php 
	$paypal_donate = get_user_meta($user->ID, 'paypal_donate', true);
	?>
	
	<?php if(!empty($paypal_donate)) : ?>
		<div class="row">
			<div class="col-md-12">
				<p class="paypal_donate_thanks">Thank you for your previous donnation</p>
			</div>
		</div>	
	<?php endif; ?>	
		
	

</div>



<div class="modal fade" role="dialog" id="joined">
	<div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Joined get-togethers</h4>
		      </div>
		<!-- start table -->

		<?php if(!$premium) : ?>
		<div class="premium-notice">
		    <a href="<?php echo page('membership') ?>">UPGRADE TO PREMIUM MEMBERSHIP AND ENJOY THE FULL BENEFITS OF THIS SECTION</a>
		</div>
		<?php else: ?>
		<div id="no-more-tables" class="clearfix">

			<?php
			$forumID = 72;
			$nr = 1;
			$args = array(
				'post_type' => 'topic',
	            'posts_per_page' => -1,
				// 'orderby' => array(
					// 'meta_key' => '_bbp_voice_count',
					// 'meta_value_num' => 'DESC'
				// ),

			);
			$topics = new WP_Query($args);

			?>
			<table class="table-condensed clearfix">
			<?php if($topics->have_posts()): ?>

					<?php
					while ( $topics->have_posts() ) : $topics->the_post();
	                    $topic = $post;
						/* get topic id */
						$tid = $topic->ID;
						/* get topic meta */
						$topicMeta = get_post_meta($topic->ID);

						/* get the topic date and hour last updated */
						$updated = explode(' ', $topicMeta['_bbp_last_active_time'][0]);
						/* voices */
						$voices = $topicMeta['_bbp_voice_count'][0];
						/* get the date */
						$date = $topicMeta['date'][0];
						/* get meeting time */
						$time = $topicMeta['time'][0];

						$location = explode(',',$topicMeta['location'][0]);

						/* topic title and permalink */
						$subforumID = get_post($topic->post_parent)->ID;
						$topicTitle = get_the_title($tid);
						$topicPermalink = get_the_permalink( $tid );

						$joined = total_joined($tid);
						/* TOPIC HTML
						-----------------------------------------------------*/

						 ?>
						 <?php if(joined_user($topic->ID, $userinfo->ID)): ?>


						<?php if ($nr==1):?>
							<thead class="clearfix">
								<tr>
									<th class="title-table">Get-togethers taking place</th>
									<th class="location-table">Location</th>
									<th class="date-table">Happening</th>
									<th class="number-people">People attending</th>
									<th class="join-table">Join</th>
								</tr>
							</thead>
						<?php endif;?>



						<!-- topic -->
						<tr <?php echo $nr > 5?'style="display: none;"':''; ?>>
							<td data-title="<?php echo $topicTitle; ?>" class="first">
								<a href="<?php echo $topicPermalink; ?>"><?php echo $topicTitle; ?></a>
							</td>
							<td data-title="Location">
								<b><?php echo $location[0].',' ?></b>
								<br>
								<span><?php echo $location[1] ?></span>
							</td>
							<td data-title="Happenings">
								<b><?php echo $date ?></b>
								<br>
								<span><?php echo $time ?></span>
							</td>
							<td data-title="People attending" class="last total-attending"><?php echo $joined ?></td>
							<td data-title="Join"><button class="join-meeting <?php if(joined($topic->ID)) echo 'unjoin-meeting active';?>" data-text-active="joined" data-text-inactive="join" data-id="<?php echo $tid ?>">
								<span class="join-text">
									<?php
									if(!joined($topic->ID)) : ?>
										join
									<?php else : ?>
										joined
									<?php endif; ?>
								</span>
							</button></td>
						</tr>
						<!-- topic -->


						<?php
						$nr++;
						endif; ?>
					<?php endwhile ?>

			<?php endif; ?>
				
			</table>		

			<?php if($nr==1) : ?>
				<h2 style="color: #000 !important;">No get-togethers joined</h2>
			<?php endif; ?>

			<nav class="pagination-wrapper">
				<?php
				if($nr > 6): ?>
	            	<a href="javascript:void(0)" class="load-more" data-load="10">load more</a>
	            <?php endif; ?>
	        </nav>
		</div>
		<!-- end table -->
		<?php endif; ?>
	    </div>
    </div>
</div>