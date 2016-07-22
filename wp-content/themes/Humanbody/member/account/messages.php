<?php
/* purge everything on get and post */
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
?>
<?php
$messages = new user_messages();
$rooms = $messages->get_rooms();


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
							<div class="table-section messages-table collection">
								<div class="title-wrapper">
									<h1>Messages</h1>
								</div>
								<div id="no-more-tables" class="clearfix">
									<table class="table-condensed clearfix">
										<tbody>
											<?php if(is_array($rooms) && count($rooms) > 0) : 50 ?>
												<?php
												foreach ($rooms as $room) :
													$lines = $messages->get_messages_from_room($room->ID);
													$last  = $messages->last_message($room->ID);
													$message_user = new user_info();
													$other_user_id = $room->from_id == $message_user->ID ? $room->to_id : $room->from_id;
													$info = $message_user->get($other_user_id);
													$firstname = explode(' ', $info['name']);
													$firstname = ucfirst($firstname[0]);
													$unread = $messages->unread($room->ID);

													if($last !== false) :
														$action = 'wrote';
														$message = '“'.$last->message.'”';
														$timestamp = date_create($last->date);
														$datecomment = date_format($timestamp, 'F jS, Y');
														$timecomment = date_format($timestamp, 'g:i A');

													else:
														$action = 'hasn`t messaged back yet';
														$message = '';
													endif;
													$secure_link = $messages->get_room_secure($room->ID);
                                                    if(is_array($lines) && count($lines) > 0) :
												    ?>
        												<tr data-messages="<?php echo $secure_link ?>">
        													<td>
        														<div class="messenger-wrapper">
        															<?php if($unread != false) : ?>
        															<div class="number-messages">
        																<?php echo $unread ?>
        															</div>
        															<?php endif; ?>
        															<div class="messenger-inner">
        																<img src="<?php echo $info['avatar'] ?>" alt="x">
        															</div>
        														</div>
        													</td>
        													<td>
        														<div class="messenger-name">
        															<?php echo ucfirst($info['name']) ?>
        														</div>
        													</td>
        													<td>
        														<div class="message-detail">
        															<a href="#"><?php echo $firstname ?></a> <?php echo $action ?>
        															<p><?php echo $message ?></p>
        														</div>
        													</td>
        													<td>
        														<div class="time-detail">
        															<?php echo $datecomment ?>
        															<br/>
        															<?php echo $timecomment ?>
        														</div>
        													</td>
        													<td>
        														<a href="#" class="edit-message"><i class="fa fa-eye"></i> view</a>
        													</td>
        												</tr>
                                                    <?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>

										</tbody>
									</table>

								</div>

							</div>
							<!-- start pagination -->
							<!-- <nav class="pagination-wrapper">
								<ul class="pagination clearfix">
									<li>
										<a href="#" class="first-item disabled">First</a>
									</li>
									<li>
										<a href="#" class="prev disabled">
											<i class="fa fa-angle-left"></i>
										</a>
									</li>
									<li><a href="#" class="active">1</a></li>
									<li><a href="#">2</a></li>
									<li><a href="#">3</a></li>
									<li><a href="#">4</a></li>
									<li><a href="#">5</a></li>
									<li><a href="#">...</a></li>
									<li><a href="#">19</a></li>
									<li><a href="#">20</a></li>
									<li><a href="#">21</a></li>
									<li><a href="#">22</a></li>
									<li><a href="#">23</a></li>
									<li>
										<a href="#" class="next">
											<i class="fa fa-angle-right"></i>
										</a>
									</li>
									<li>
										<a href="#" class="last-item">Last</a>
									</li>

								</ul>
							</nav> -->
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
