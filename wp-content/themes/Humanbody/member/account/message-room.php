<?php
/* purge everything on get and post */
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
?>


<?php
$mcr = new user_messages();
$room_id = $mcr->decrypt($_GET['hb']);
$room = $mcr->get_room($room_id);
$room = $room[0];
$list = $mcr->get_messages_from_room($room_id, 20, 0);
$count = $mcr->get_messages_count_in_room($room_id);
$unread = $mcr->unread($room_id);
$currentuser = new user_info();
$currentinfo = $currentuser->get();

$otherUserID = $room->from_id == $currentinfo['ID'] ? $room->to_id : $room->from_id;
$otheruserinfo = $currentuser->get($otherUserID);

/* CLEAR all the messages of unread messages */
$status = $mcr->clear_unread($room_id);

$user_id = $room->to_id;
$prem_check = get_userdata( $user_id );
?>
<div id="content" class="secondary-subpage messages">

	<!-- cont-utilizator-section -->
	<div class="cont-utilizator-section">
		<!-- Start top-banner-wrapper -->
		<div class="top-banner-wrapper">

			<div class="container">

				<!-- Start cont-wrapper -->
				<div class="cont-wrapper">
					<div class="row">
						<!-- start profile -->
						<div class="col-md-10 col-md-offset-1">
							<?php include_once('account-topbar.php') ?>
						</div>
						<!-- start profile -->
						<div class="col-md-10 col-md-offset-1">
							<div class="row">
								<div class="col-xs-12 conversation-title">
									<h2>For new messages to load please press the refresh button.<br />For live conversations you can use the chat option.</h2>
									<?php
									$message = $otheruserinfo['name'].' is not a premium member. '.$otheruserinfo['name'].' will be notified that you have attempted to send them a message.';
									echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?'':'<p style="text-align: center;color: #fff;">'.$message.'</p>'
									?>
								</div>
							</div>
							<form id="conversation-form" method="post">
								<div class="message-details">
									<div class="clearfix ">
										<div class="pull-left">
											<div class="messenger-wrapper">
												<?php if($unread != false) : ?>
												<div class="number-messages"><?php echo $unread ?></div>
												<?php endif; ?>
												<div class="messenger-inner">
													<img src="<?php echo $otheruserinfo['avatar'] ?>" alt="x">
												</div>
											</div>

										</div>
										<div class="pull-left">
											<div class="messenger-name">
												<a href="<?php echo page('member').'?u='.$mcr->encrypt($otheruserinfo['ID']) ?>">	<?php echo $otheruserinfo['name'] ?></a>
											</div>
										</div>
										<div class="pull-right">
											<a href="#" data-redirect="<?php echo page('my-account-messages') ?>" data-rid="<?php echo $_GET['hb'] ?>" class="delete-conversation"><i class="fa fa-trash-o"></i> Delete conversation</a>

										</div>
									</div>
								</div>
								<div class="conversation-list">
									<!-- start message list -->
									<ul class="clearfix">
										<?php
									if(is_array($list) && count($list) > 0) :
										foreach (array_reverse($list) as $message) :
											$user = new user_info();
											$info = $user->get($message->user_id);
											$date = date_today($message->date);
											if($message->user_id != $currentuser->ID) :
												$align = 'conversation-user';
											else :
												$align = 'current-user';
											endif;
										 ?>
										<li id="message-<?php echo $message->ID; ?>" class="clearfix <?php echo $align; echo $message->hightlighted == 1?' highlighted-message':'';  echo $message->status == 0 && $message->user_id != $user_id?' not-seen':''; ?> ">
											<div class="message-info">
												<div class="items">
													<p>edit</p>
													<div class="delete-message" data-mid="<?php echo $mcr->encrypt($message->ID) ?>"></div>
													<div class="highlight-message" data-mid="<?php echo $mcr->encrypt($message->ID) ?>" data-val="<?php echo $message->hightlighted == 1?0:1; ?>"><span><?php echo $message->hightlighted == 1?'unhighlight':'highlight'; ?></span></div>
													<div class="cancel-edit">cancel</div>

												</div>
												<div class="time-of-message">
													<?php echo $date[0] ?> <br>
													<?php echo $date[1] ?>
												</div>
												<div class="messenger-inner">
													<img src="<?php echo $info['avatar'] ?>" alt="img">
												</div>
											</div>
											<div class="message-body">
												<p>
													<?php echo $message->message ?>
												</p>
											</div>
										</li>
									<?php endforeach ?>
								<?php endif ?>
									</ul>
									<!-- end message list -->
									<?php if($count && $count > 50):  ?>
									<!-- start laod more wrapper -->
									<div class="load-more-wrapper">
										<a href="javascript:void(0)" class="load-more messages" data-r="<?php echo $_GET['hb'] ?>" data-s="5">load more</a>
									</div>
									<!-- end laod more wrapper -->
									<?php endif; ?>
								</div>
								<textarea name="reply-message" id="reply-message" class="reply-message"></textarea>
								<input type="submit" name="send" value="Send Message" class="send-message">
							</form>
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
