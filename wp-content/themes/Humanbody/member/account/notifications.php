<?php
/* purge everything on get and post */
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$user = new user_info();
$info = $user->get();

$page = get_query_var('page', 0);
if($page==0) $page = 1;

$uno = new notifications();

$premium = $user->premium;

if(!$premium) :
	wp_redirect(page('my-account'));
endif;


if (isset($_POST['allread'])) {
	//mark all messages as read
	$not = $uno->mark_all_as_read();
	wp_redirect(page('notifications'));
}

/*
1. select from notifications where post_type = user_message  ($not = $uno->get_no_messages(25, $page);)
2. select from notifications where post_type != user_message $messages_not = $uno->get_all_messages();
*/

$per_page_no_messages = 25;
$not = $uno->get_no_messages($per_page_no_messages, $page);

//client side load more
$no_per_page = 4;
$messages_not = $uno->get_all_messages();

?>
<div id="content" class="secondary-subpage notifications">

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
							<div class="table-section notifications-table">
								<div class="title-wrapper">
									<h1>Notifications</h1>
								</div>
								<form method="post" action="" class="clearfix">
									<button name="allread" type="submit" class="mark-all-read">
										<i class="fa fa-check-square-o" aria-hidden="true"></i> Mark all as read
									</button>
								</form>
								<h2 style="color:#fff; font-size: 30px; margin-top: 10px">Notification for messages</h2>
								<div id="no-more-tables" class="clearfix">

									<?php
									//messages
									if(count($messages_not) > 0) :

									$users_arr = array();
									if(!empty($messages_not)):
										foreach ($messages_not as $message) {
											$users_arr[$message->from_id][] = $message;
										}
										if(count($users_arr) > 0) : ?>
										<table id="table-profile-messages" class="table-condensed clearfix">
											<tbody>
												<?php
													$nr = 1;
													foreach ($users_arr as $n) :
														$date_time = $n[0]->date;
														$name = $n[0]->name;
														$first = explode(' ', $name);
														$first = $first[0];
														$count = 0;
														foreach ($n as $messaege) {
															if($messaege->status == 0 ){
																$count += 1;
															}
														}
													?>
															<tr <?php echo ($nr>$no_per_page)?'style="display:none"':'' ?> class="clearfix <?php echo $count > 0?'unread':''; ?> <?php echo $n[0]->class ?>">
																<td>
																	<div class="messenger-wrapper">
																		<div class="messenger-inner">
																			<img src="<?php echo $n[0]->avatar ?>" alt="avatar">
																		</div>
																	</div>
																</td>
																<td>
																	<div class="messenger-name">
																		<a class="name" href="<?php echo page('member').'?u='.$uno->encrypt($n[0]->from_id) ?>"><?php echo $n[0]->name ?></a>
																	</div>
																</td>
																<td>
																	<div class="message-detail" style="position: relative;">
																		<?php if($count > 0): ?>
																			<span style="top: 0; right: auto; left: -20px;" class="bell-number"><?php echo $count; ?></span>
																		<?php endif; ?>
																		<a class="name" href="<?php echo page('member').'?u='.$uno->encrypt($n[0]->from_id) ?>"><?php echo $n[0]->name ?></a> <?php echo $n[0]->action ?> <?php echo $n[0]->text2 ?> <?php echo $v ?>
																		<p data-type="<?php echo 'user message' ?>"><a href="<?php echo $n[0]->url ?>">
																			<?php 
																			if (strlen($n[0]->text)>49) {
																				echo substr($n[0]->text, 0, 50).'...';
																			} else {
																				echo $n[0]->text;
																			}
																			?>																			
																		</p>
																	</div>
																</td>
																<td>
																	<div class="time-detail">
																		<?php echo $date_time[0] ?><br><?php echo $date_time[1] ?>
																	</div>
																</td>
																<td class="view-notification">
																	<a href="<?php  echo $n[0]->url ?>"><i class="fa fa-eye"></i>View</a>
																</td>
															</tr>

														<?php
														$nr ++;
													endforeach;
												?>
											</tbody>

										</table>
											<?php endif;
										endif;//check if arr not empty
									endif;
									//end user messages
									?>
									<?php if(count($users_arr)>$no_per_page): ?>
										<a href="#" id="load-messages-profile" data-perpage="<?php echo $no_per_page; ?>" class="load-more">Load more</a>
									<?php endif; ?>

									<h2 style="color:#fff; font-size: 30px; margin-top: 10px">Other notifications</h2>

									<table class="table-condensed clearfix">
										<tbody>
											<?php if(count($not) > 0) :

												$messages_arr = array();

												foreach ($not as $n) :

													/* user avatar */
	                                                $avatar = $n->avatar;
	                                                /* user name */
	                                                $name = $n->name;
													$first = explode(' ', $name);
													$first = $first[0];
	                                                /* notification action, a string in the form of a verb */
	                                                $action = $n->action;
	                                                /* cut string from the place the notification began */
	                                                $text = substr($n->text, 0, 50);
	                                                /* notification date and time (array) */
	                                                $date_time = $n->date;
	                                                /* boolean 0/1 */
	                                                $status = $n->status;
	                                                /* url to make the user go to */
	                                                $permalink = $n->url;
	                                                /* class added to the notification */
	                                                $class = $n->class;
	                                                /* notification title in case the area is locked */
	                                                $title = $n->title;
													/* notification type */
													$type = $n->type;
														?>
														<tr class="<?php echo ($status == 1 ? '' : 'unread').' '.$n->class ?>">
															<td>
																<div class="messenger-wrapper">

																	<div class="messenger-inner">
																		<img src="<?php echo $avatar ?>" alt="avatar">
																	</div>
																</div>
															</td>
															<td>
																<div class="messenger-name">
																	<a class="name" href="<?php echo page('member').'?u='.$uno->encrypt($n->from_id) ?>"><?php echo $name ?></a>
																</div>
															</td>
															<td>
																<div class="message-detail">
																	<a class="name" href="<?php echo page('member').'?u='.$uno->encrypt($n->from_id) ?>">
																		<?php echo $name ?></a> <?php echo $n->action ?> 
																		<?php echo $n->text2 ?> <?php echo $v ?>
																	<?php

																		if($type == 'friend request'){ ?>
																			<?php /*
																			<p data-type="<?php echo $type ?>"><a href="<?php echo $permalink ?>"><?php echo substr($text, 0, 50) ?></a></p>
																			*/ ?>
																		<?php }else{ ?>
																			<p data-type="<?php echo $type ?>">
																				<a href="<?php echo (isset($n->url_without_goto) and !empty($n->url_without_goto)) ? $n->url_without_goto : $permalink ?>">
																					<?php 
																					if (strlen($text)>49) {
																						echo substr($text, 0, 50).'...';
																					} else {
																						echo $text;
																					}
																					?>
																				</a>
																			</p>
																		<?php }

																	?>
																</div>

															</td>
															<td>
																<div class="time-detail">
																	<?php echo $date_time[0] ?><br><?php echo $date_time[1] ?>
																</div>
															</td>
															<td class="view-notification">
																<a href="<?php  echo $permalink ?>"><i class="fa fa-eye"></i>View</a>
															</td>
														</tr>
													<?php
												endforeach;
											endif; ?>
										</tbody>
									</table>
								</div>
							</div>
							<!-- start pagination -->
							<nav class="pagination-wrapper">
								<ul class="pagination clearfix">
									<?php //echo $uno->paginate(25); ?>

									<?php 
									$tatal_no_messages_count =  $uno->get_no_messages_number();


									$total_pages = ceil($tatal_no_messages_count / $per_page_no_messages);

									$base = page('notifications');

									echo $uno->paginare($page, $total_pages, $base, false, false);
									?>
								</ul>
							</nav>
							<!-- end pagination -->
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
