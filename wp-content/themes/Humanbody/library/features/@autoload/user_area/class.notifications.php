<?php

/**
 * handles all the notifications for the user
 */
class notifications extends __user
{
	/**
	 * $ID  current user id
	 * @var int
	 */
	public $ID = false;
	/**
	 * $meta  user meta options, #NOTE still figuring out if this is the best place to save
	 * @var array
	 */
	public $meta = array();

	/**
	 * $notifications  the user notifications
	 * @var array
	 */
	public $notifications = array();

	/**
	 * $count the total of user notifications
	 * @var integer
	 */
	public $count = 0;

	private $table_name = 'notifications';



	function __construct() {
		/* store id of logged in user */
		$this->ID = parent::id();
		/* store the current user meta */
		$this->meta = parent::meta();

	}
	/**
	 * @method get_all  this should count all the user interation on the website
	 * one way this could be possible:
	 *  - first of we have messages and post comments, both should handle in similar way
	 *  store a param that makes this viewed or not in the db
	 *  based on this assumption the most efficient way it would be to make a buffer table
	 *  that registers events(any event) pased on post type and id
	 *  if the user views the event it gets checked as read and its done #FIXME
	 *
	 * @return [type] [description]
	 */
	public function get_all($limit = 5, $page=0) {
		$max = $limit;
		$start_from = $page*$limit;
        global $wpdb;
        $table = $wpdb->prefix.$this->table_name;
		$ID = $this->ID;
        $query = "SELECT * FROM $table WHERE `user_id`= $ID ORDER BY `date` DESC LIMIT $start_from, $limit";
        $rows = $wpdb->get_results($query);
		return $rows;
	}

	public function get_all_no_messages($limit = 5, $page=0, $include_user_message = false) {
		$max = $limit;
		if ($page==1) {
			$start_from = 0;
		} else {
			$start_from = ($page-1)*$limit;
		}
        global $wpdb;
        $table = $wpdb->prefix.$this->table_name;
		$ID = $this->ID;

		if ($include_user_message)  {
			$query = "SELECT * FROM $table WHERE `user_id`= $ID ORDER BY `date` DESC LIMIT $start_from, $limit";
		} else {
			$query = "SELECT * FROM $table WHERE `user_id`= $ID AND `post_type` != 'user-message' ORDER BY `date` DESC LIMIT $start_from, $limit";
		}


        $rows = $wpdb->get_results($query);
		return $rows;
	}

	public function mark_all_as_read() {
		global $wpdb;
		
		$table = $wpdb->prefix.$this->table_name;
		$update = $wpdb->update( $table, array('status'=>1), $where = array('user_id'=>$this->ID));
		return $update;
	}

	public function get_all_unread($limit = 5, $page) {

		$max = $limit;
		$start_from = $page*$limit;
		global $wpdb;
		$table = $wpdb->prefix.$this->table_name;
		$ID = $this->ID;
		$query = "SELECT * FROM $table WHERE `user_id`= $ID AND `status` = 0 ORDER BY `date` DESC LIMIT $start_from, $limit";
		$rows = $wpdb->get_results($query);
		return $rows;
	}

	/* Limit and page are not used */
	public function get_all_unread_messages($limit = 5, $page) {

		$max = $limit;
		if ($page==1) {
			$start_from = 0;
		} else {
			$start_from = ($page-1)*$limit;
		}
		global $wpdb;
		$table = $wpdb->prefix.$this->table_name;
		$ID = $this->ID;
		$query = "SELECT * FROM $table WHERE `user_id`= $ID AND `post_type` = 'user-message' ORDER BY `date` DESC ";

		$rows = $wpdb->get_results($query);

		return $rows;
	}

	/**
	 * get all notifications, built as an adapter for the notification table
	 * @method get
	 * @param  integer $limit      notifications per page
	 * @param  integer $page       page
	 * @return [type]              returns an array of notifications
	 */
	public function get($limit = 5, $page = 0)
	{

		$notifications = $this->get_all($limit, $page);
		if(is_array($notifications) && count($notifications) > 0) :
			foreach ($notifications as $notification) :

				/* 6 values
				avatar, user name, action, text, date link
				 */
				$n_id = $notification->ID;
				$date_time = date_today($notification->date);
				$n_user = new user_info();
				$userinfo = $n_user->get($notification->from_id);
				/* avatar */
				$avatar = $userinfo['avatar'];
				/* name */
				$name = $userinfo['name'];

				/* do a switch on all supported custom type notifications */
				switch ($notification->post_type) :
					/* user messages type notification */
					case 'user-message':
							$user_ID = get_current_user_id();
							$premium_user_check = $n_user->user_info['premium'];

							$scroll_to = '#message-'.$notification->comment_id;


							/* action */
							$action = $premium_user_check != 1?'has attempted to send you a message':'messaged you';
							/* url */
							$permalink = $notification->goto.'&n='.$this->encrypt($n_id).$scroll_to;
							/* post name */
							$mcr = new user_messages();
							$room = $mcr->get_room($notification->post_id);
							$message = $mcr->get_message($notification->comment_id);
							$postname = $message->message;

						break;
					/* user comments */
					case 'post':
							/* action */
							$action = 'commented on';
							/* url */
							$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id);
							/* post name */
							$postname = get_the_title($notification->post_id);
						break;
					case 'reply':

							$postdata = get_postdata($notification->post_id);
							$authorID = $postdata['Author_ID'];
							$user_ID = get_current_user_id();

							switch (get_post_type( $notification->post_id )) {
								case 'galleries':
									$text = 'replied to your comment on the picture gallery:';
									break;
								default:
									$text = $authorID == $user_ID?'replied to your comment on your post:':'replied to your comment on:';
							}

							$goto = $notification->goto;
							/* action */
							$action = $text;
							/* url */
							$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id).'#'.$goto;
							/* post name */
							$postname = get_the_title($notification->post_id);
						break;
					case 'meeting':
							$user_ID = get_current_user_id();
							$premium_user_check = $n_user->user_info['premium'];

							/* action */
							$action = $premium_user_check != 1?'has attempted to invite you to a get-together':'invited you to the get-together:';
							/* url */
							$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id);
							/* post name */
							$postname = get_the_title($notification->post_id);
						break;
					case 'friend-request':
							$user_ID = get_current_user_id();
							$premium_user_check = $n_user->user_info['premium'];
							/* action */
							$action = $premium_user_check != 1?'has attempted to add you to their friends list':'added you to their friends list:';
							/* url */
							$permalink = page('member').'?u='.$this->encrypt($notification->from_id).'&n='.$this->encrypt($n_id);
							/* post name */
							$postname = 'view friend request';
						break;
					case 'topic':
						$meta = get_post_meta($notification->post_id);
						switch ($meta['type'][0]) {
							case 'meeting':
								$text = 'commented on the get-together that you created:';
								break;
							default:
								$text = 'commented on your post:';
						}

						/* action */
						$action = $text;
						/* url */
						$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id);
						/* post name */
						$postname = get_the_title($notification->post_id);
						break;
					default:
					case 'galleries':
						/* action */
						$action = 'commented on your gallery:';
						/* url */
						$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id);
						/* post name */
						$postname = get_the_title($notification->post_id);
						break;
					default:


						break;
				endswitch;

				$n_a[$notification->ID] = to_object(array(
					'avatar' => $avatar,
					'name' => $name,
					'action' => $action,
					'text' => $postname,
					'url' => self::url($notification->post_type, $permalink),
					'date' =>$date_time,
					'status' => self::status($notification->status, $notification->post_type),
					'type' => str_replace('-', ' ', $notification->post_type),
					'class' => self::css_class($notification->post_type),
					'title' => self::locked_title($notification->post_type),
					'from_id' => $notification->from_id
				));
		endforeach;
		return $n_a;
	endif;
	}


	public function get_no_messages($limit = 5, $page = 0, $include_user_message = false)
	{
		if ($include_user_message) {
			$notifications = $this->get_all_no_messages($limit, $page, true);
		} else {
			$notifications = $this->get_all_no_messages($limit, $page);
		}

		//deg ($notifications);

		if(is_array($notifications) && count($notifications) > 0) :
			foreach ($notifications as $notification) :

				/* 6 values
				avatar, user name, action, text, date link
				 */
				$n_id = $notification->ID;
				$date_time = date_today($notification->date);
				$n_user = new user_info();
				$userinfo = $n_user->get($notification->from_id);
				/* avatar */
				$avatar = $userinfo['avatar'];
				/* name */
				$name = $userinfo['name'];

				/* do a switch on all supported custom type notifications */
				switch ($notification->post_type) :
					/* user messages type notification */
					case 'user-message':
							$user_ID = get_current_user_id();
							$premium_user_check = $n_user->user_info['premium'];

							$scroll_to = '#message-'.$notification->comment_id;


							/* action */
							$action = $premium_user_check != 1?'has attempted to send you a message':'messaged you';
							/* url */
							$permalink = $notification->goto.'&n='.$this->encrypt($n_id).$scroll_to;
							$permalink_without_goto = '';
							/* post name */
							$mcr = new user_messages();
							$room = $mcr->get_room($notification->post_id);
							$message = $mcr->get_message($notification->comment_id);
							$postname = $message->message;

						break;
					/* user comments */
					case 'post':
							/* action */
							$action = 'commented on';
							/* url */
							$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id);
							$permalink_without_goto = '';
							/* post name */
							$postname = get_the_title($notification->post_id);
						break;
					case 'reply':

							$postdata = get_postdata($notification->post_id);
							$authorID = $postdata['Author_ID'];
							$user_ID = get_current_user_id();

							switch (get_post_type( $notification->post_id )) {
								case 'galleries':
									$text = 'replied to your comment on the picture gallery:';
									break;
								case 'topic':
									//get notification page ancestors to find if the post is get_togheter, forum, or support group
									$post_ancestors = get_post_ancestors( $notification->post_id );
									$id_top_ancestor = ($post_ancestors) ? $post_ancestors[count($post_ancestors)-1]: $notification->post_id;
									$top_ancestor = get_post( $id_top_ancestor );

									switch ($top_ancestor->post_name) {
										case 'support-group':
											$post_name = 'support groups';
											break;
										case 'get-together':
											$post_name = 'get together';
											break;
										case 'meetings':
											$post_name = 'get together';
											break;
										case 'forum':
											$post_name = 'topic';
											break;	
										default:
											$post_name = 'post';
											break;
									}


									$text = $authorID == $user_ID?'replied to your comment on the '.$post_name.' that you created:':'replied to your comment on the '.$post_name.':';
									break;	
								default:
									$text = $authorID == $user_ID?'replied to your comment on your post:':'replied to your comment on:';
							}

							$goto = $notification->goto;
							/* action */
							$action = $text;
							/* url */
							$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id).'#'.$goto;
							$permalink_without_goto = get_the_permalink( $notification->post_id );
							/* post name */
							$postname = get_the_title($notification->post_id);
						break;
					case 'meeting':
							$user_ID = get_current_user_id();
							$premium_user_check = $n_user->user_info['premium'];

							/* action */
							$action = $premium_user_check != 1?'has attempted to invite you to a get-together':'invited you to the get-together:';
							/* url */
							$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id);
							$permalink_without_goto = '';
							/* post name */
							$postname = get_the_title($notification->post_id);
						break;
					case 'friend-request':
							$user_ID = get_current_user_id();
							$premium_user_check = $n_user->user_info['premium'];
							/* action */
							$action = $premium_user_check != 1?'has attempted to add you to their friends list':'added you to their friends list';
							/* url */
							$permalink = page('member').'?u='.$this->encrypt($notification->from_id).'&n='.$this->encrypt($n_id);
							$permalink_without_goto = '';
							/* post name */
							$postname = 'view friend request';
						break;
					case 'topic':
						$meta = get_post_meta($notification->post_id);

						switch ($meta['type'][0]) {
							case 'meeting':
								$text = 'commented on the get-together that you created:';
								break;
							case 'topic':
								$text = 'commented on topic that you created:';
								break;	
							default:
								$text = 'commented on your post:';
						}

						/* action */
						$action = $text;
						/* url */
						$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id);
						$permalink_without_goto = '';
						/* post name */
						$postname = get_the_title($notification->post_id);
						break;
					default:
					case 'galleries':
						/* action */
						$action = 'commented on your gallery:';
						/* url */
						$permalink = get_the_permalink( $notification->post_id ).'?n='.$this->encrypt($n_id);
						$permalink_without_goto = '';
						/* post name */
						$postname = get_the_title($notification->post_id);
						break;
					default:


						break;
				endswitch;

				//echo $notification->post_type;
				$n_a[$notification->ID] = to_object(array(
					'avatar' => $avatar,
					'name' => $name,
					'action' => $action,
					'text' => $postname,
					'url' => self::url($notification->post_type, $permalink),
					'url_without_goto' => (isset($permalink_without_goto)?$permalink_without_goto:''),
					'date' =>$date_time,
					'status' => self::status($notification->status, $notification->post_type),
					'type' => str_replace('-', ' ', $notification->post_type),
					'class' => self::css_class($notification->post_type),
					'title' => self::locked_title($notification->post_type),
					'from_id' => $notification->from_id
				));
		endforeach;
		return $n_a;
	endif;
	}

	public function get_no_messages_number ($include_user_message = false) {
		global $wpdb;
        $table = $wpdb->prefix.$this->table_name;
		$ID = $this->ID;

		if ($include_user_message)  {
			$query = "SELECT COUNT(*) FROM $table WHERE `user_id`= $ID";
		} else {
			$query = "SELECT COUNT(*) FROM $table WHERE `user_id`= $ID AND `post_type` = 'user-message' ";
		}

        $rows = $wpdb->get_var($query);
		return $rows;		
	}

	/* Limit and page are not used */
	public function get_all_messages($limit = 5, $page = 0)
	{

		$notifications = $this->get_all_unread_messages($limit, $page);
		if(is_array($notifications) && count($notifications) > 0) :
			foreach ($notifications as $notification) :

				/* 6 values
				avatar, user name, action, text, date link
				 */
				$n_id = $notification->ID;
				$date_time = date_today($notification->date);
				$n_user = new user_info();
				$userinfo = $n_user->get($notification->from_id);
				/* avatar */
				$avatar = $userinfo['avatar'];
				/* name */
				$name = $userinfo['name'];

				/* do a switch on all supported custom type notifications */
				switch ($notification->post_type) :
					/* user messages type notification */
					case 'user-message':
							$user_ID = get_current_user_id();
							$premium_user_check = $n_user->user_info['premium'];
							$scroll_to = '#message-'.$notification->comment_id;
							/* action */
							$action = $premium_user_check != 1?'has attempted to send you a message':'messaged you';
							/* url */
							$permalink = $notification->goto.'&n='.$this->encrypt($n_id).$scroll_to;
							/* post name */
							$mcr = new user_messages();
							$room = $mcr->get_room($notification->post_id);
							$message = $mcr->get_message($notification->comment_id);
							$postname = $message->message;

						break;
				endswitch;

				$n_a[$notification->ID] = to_object(array(
					'avatar' => $avatar,
					'name' => $name,
					'action' => $action,
					'text' => $postname,
					'url' => self::url($notification->post_type, $permalink),
					'date' =>$date_time,
					'status' => self::status($notification->status, $notification->post_type),
					'type' => str_replace('-', ' ', $notification->post_type),
					'class' => self::css_class($notification->post_type),
					'title' => self::locked_title($notification->post_type),
					'from_id' => $notification->from_id
				));
		endforeach;
		return $n_a;
	endif;
	}

	public function locked_title($type)
	{
		$user = new user_info();
		if(!$user->premium) :
			switch ($type) :
				case 'friend-request':
						$title = 'premium feature';
					break;
				case 'user-message':
						$title = 'premium feature';
					break;
				case 'meeting':
						$title = 'premium feature';
					break;
				default:
						$title = false;
					break;
			endswitch;
		endif;
		return $title;
	}
	/**
	 * notification status makes the message be read unread based on certain areas
	 * @method status
	 * @param  integer $status the raw notification status
	 * @param  string  $type   the notification type
	 * @return integer         returns new status
	 */
	static public function status($status, $type)
	{
		$user = new user_info();
		if(!$user->premium) :
			switch ($type) :
				case 'friend-request':
						//$status = 1;
					break;
				case 'user-message':
						//$status = 1;
					break;
				case 'meeting':
						//$status = 1;
					break;
				default:
						$status = $status;
					break;
			endswitch;
		endif;
		return $status;
	}
	/**
	 * notification class handles the different classes added by different notifications types
	 * @method css_class
	 * @param  string             $type notification type
	 * @return string             return css class to be applied to the notifications
	 */
	public function css_class($type = '')
	{
		$user = new user_info();
		if(!$user->premium) :
			switch ($type) :
				case 'friend-request':
						$class = 'premium reduce-opacity';
					break;
				case 'user-message':
						$class = 'premium reduce-opacity';
					break;
				case 'meeting':
						$class = 'premium reduce-opacity';
					break;
				case 'reply':
						$class = 'premium reduce-opacity';
					break;	
				default:
						//$class = '';
						$class = 'premium reduce-opacity';
					break;
			endswitch;
		endif;
		return $class;
	}
	/**
	 * handle url of the notifications for premium and normal memmbers
	 * @method url
	 * @param  string          $type  type of the notifications
	 * @param  string           $url  url of the notification
	 * @return string                 url of the notification
	 */
	public function url($type, $url)
	{
		/* set the fallback of the url for non premium users */
		$fallback = page('membership');
		/* get logged in user */
		$user = new user_info();
		/* store the premium status */
		$premium = $user->premium;
		switch ($type) {
			/* friend request is not active to non premium members */
			case 'friend-request':
					/* check for premium and do the fallback */
					if(!$premium) :
						$url = $fallback;
					endif;
				break;
			case 'meeting':
					/* check for premium and do the fallback */
					if(!$premium) :
						$url = $fallback;
					endif;
				break;
			case 'user-message':
					/* check for premium and do the fallback */
					if(!$premium) :
						$url = $fallback;
					endif;
				break;
			default:
					if(!$premium) :
						$url = $fallback;
					else:
						$url = $url;
					endif;
					
				break;
		}

		return $url;
	}
	public function total_unread()
	{
		global $wpdb;
		$table = $wpdb->prefix.$this->table_name;
		$ID = $this->ID;
		$user = new user_info();
		$premium = $user->premium;
		if(!$premium) :
			//$query = "SELECT COUNT(*) FROM $table WHERE `user_id`= $ID AND `status` = 0 AND `post_type` != 'friend-request' AND `post_type` != 'meeting' AND `post_type` != 'user-message'";

			$query = "SELECT COUNT(*) FROM $table WHERE `user_id`= $ID AND `status` = 0  AND `post_type` = 'user-message' GROUP BY `from_id`";
			$rows = $wpdb->get_results($query);
			$rows = (array)$rows[0];
			$total_unread_users = $rows['COUNT(*)'];

			//get all other notifications
			$query2 = "SELECT COUNT(*) FROM $table WHERE `user_id`= $ID AND `status` = 0  AND `post_type` != 'user-message'";
			$rows2 = $wpdb->get_results($query2);
			$rows2 = (array)$rows2;

			$rows2 = $wpdb->get_results($query2);
			$rows2 = (array)$rows2[0];
			$total_other_notifications = $rows2['COUNT(*)'];

			$total = $total_unread_users + $total_other_notifications;
			return $total;

		else :
			//get all user-messages
			$query = "SELECT COUNT(*) FROM $table WHERE `user_id`= $ID AND `status` = 0  AND `post_type` = 'user-message' GROUP BY `from_id`";

			$rows = $wpdb->get_results($query);
			$rows = (array)$rows;

			$total_unread_users = count($rows);
			
			//get all other notifications
			$query2 = "SELECT COUNT(*) FROM $table WHERE `user_id`= $ID AND `status` = 0  AND `post_type` != 'user-message'";
			$rows2 = $wpdb->get_results($query2);
			$rows2 = (array)$rows2;

			$rows2 = $wpdb->get_results($query2);
			$rows2 = (array)$rows2[0];
			$total_other_notifications = $rows2['COUNT(*)'];

			$total = $total_unread_users + $total_other_notifications;
			return $total;

		endif;

		
	}
	public function total_count()
	{
		global $wpdb;
		$table = $wpdb->prefix.$this->table_name;
		$ID = $this->ID;
		$user = new user_info();
		$query = "SELECT COUNT(*) FROM $table WHERE `user_id`= $ID";

		$rows = $wpdb->get_results($query);
		$rows = (array)$rows[0];
		return $rows['COUNT(*)'];
	}
	/**
	 * paginates the notifications as they are extracted with mysql so no wordpress pagination support
	 * @method paginate
	 * @return string        pagination links
	 */
	public function paginate($perpage = 8)
	{
		$total = $this->total_count();
		$totalPages = round(ceil($total/$perpage));
		$pagevar = get_query_var('page') ? get_query_var('page') : 1;
		$next = $pagevar != $totalPages ? $pagevar+1 : $pagevar;
		$prev = get_query_var('page') > 1 ? $pagevar-1 : '';
		$base = page('notifications');
		$max = 5;
		$break = false;
		if($totalPages > 1) {
			for ($i=1; $i <= $totalPages; $i++) {

				if($i < $pagevar+1+$max && $i > $pagevar-$max || $i > $totalPages-$max && $i < $totalPages+1) :
					$active = '';

					if(get_query_var('page') && $pagevar-1 > -1) :
						if($pagevar+1 == $i) :
							$active = 'active';

						endif;
					else :
						if($pagevar-1 == 0 && $i == 1) :
							$active = 'active';
							$disabledstart = 'disabled';
						else :
							$active = '';
						endif;
					endif;

					$page = $i > 0 ? $i : '';
					$url = $base.($page-1 != 0 ? $page-1 : '') ;
					$pagination .= '<li><a href="'.$url.'" class="'.$active.'">'.$i.'</a></li>';
				else :
					if(!$break) :
						$break = true;
						$pagination .= '<li><a href="javascript:void(0)" class="">...</a></li>';
					endif;

				endif;

			}

			if($pagevar*$perpage == $total) {
				$disabled = 'disabled';
			}
			$start = '<li>
							<a href="'.$base.'" class="first-item '.$disabledstart.'">First</a>
						</li>
						<li>
							<a href="'.$base.$prev.'" class="prev '.$disabledstart.'">
								<i class="fa fa-angle-left"></i>
							</a>
						</li>';
			$ending = ' <li>
							<a href="'.$base.$next.'" class="next '.$disabled.'">
								<i class="fa fa-angle-right"></i>
							</a>
						</li>
						<li>
							<a href="'.$base.$totalPages.'" class="last-item '.$disabled.'">Last</a>
						</li>';

			return $start.$pagination.$ending;
		}
	}




	//Custom pagination (from tpl systems)
	function paginare($page,$total_pages,$link,$ff=false,$firstPageLink=false) {
		//use java form post for filters
		// $ff = for filter variable
		function forfilters($page){
			return ' class="pagg_'.$page.'" onclick="javascript:pageCountF('.$page.');return false" ';
		}
		// var
		$html = '<div class="pageNav clearfix"><ul class="pagination clearfix">';

		// check
		if(!is_numeric($page) OR $page>$total_pages OR $page<1) $page = 1;

		// paginare
		if ($total_pages > 1) {

			if (($page > 1)) {
				$html .= '<li><a href="'.$link.($page-1).'" title="Previous">&laquo;</a></li>';
			}

			// left skip
			if ($page > 6) {

				$skiplow[1] = 3;
				$skiplow[2] = $page - 3;
				for ($i = 1;$i <= $skiplow[1];$i++) {

					if ($page == $i) {
						$html .= '<li><a class="active" title="Pagina '.$i.'">'.$i.'</a></li>';
					} else {
						$html .= '<li><a '.(($ff)?forfilters($i):'').'href="'.(($i==1 and $firstPageLink)?$firstPageLink:$link.''.$i).'" title="Pagina '.$i.'" rel="pagina_'.$i.'">'.$i.'</a></li>';
					}

				}

				$html .= '<li class="puncte"><span>...</span></li>';

				for ($i = $skiplow[2];$i <= $page;$i++) {

					if ($page == $i) {
						$html .= '<li><a class="active" title="Pagina '.$i.'">'.$i.'</a></li>';
					} else {
						$html .= '<li><a '.(($ff)?forfilters($i):'').'href="'.(($i==1 and $firstPageLink)?$firstPageLink:$link.''.$i).'" title="Pagina '.$i.'" rel="pagina_'.$i.'">'.$i.'</a></li>';
					}

				}

			} else {

				for ($i = 1;$i <= $page;$i++) {

					if ($page==$i) {
						$html .= '<li><a class="active" title="Pagina '.$i.'">'.$i.'</a></li>';
					} else {
						$html .= '<li><a '.(($ff)?forfilters($i):'').'href="'.(($i==1 and $firstPageLink)?$firstPageLink:$link.''.$i).'" title="Pagina '.$i.'" rel="pagina_'.$i.'">'.$i.'</a></li>';
					}

				}

			}

			// right skip
			if (($total_pages - $page) > 6) {

				$skiphigh[1] = $page + 3;
				$skiphigh[2] = $total_pages - 2;
				for ($i = $page+1;$i <= $skiphigh[1];$i++) {
					$html .= '<li><a '.(($ff)?forfilters($i):'').'href="'.(($i==1 and $firstPageLink)?$firstPageLink:$link.''.$i).'" title="Pagina '.$i.'" rel="pagina_'.$i.'">'.$i.'</a></li>';
				}

				$html .= '<li class="puncte"><span>...</span></li>';

				for ($i = $skiphigh[2];$i <= $total_pages;$i++) {
					$html .= '<li><a '.(($ff)?forfilters($i):'').'href="'.(($i==1 and $firstPageLink)?$firstPageLink:$link.''.$i).'" title="Pagina '.$i.'" rel="pagina_'.$i.'">'.$i.'</a></li>';
				}

			} else {

				for ($i = $page+1;$i <= $total_pages;$i++) {

					if ($page == $i) {
						$html .= '<li><a class="active" title="Pagina '.$i.'">'.$i.'</a></li>';
					} else {
						$html .= '<li><a '.(($ff)?forfilters($i):'').'href="'.(($i==1 and $firstPageLink)?$firstPageLink:$link.''.$i).'" title="Pagina '.$i.'" rel="pagina_'.$i.'">'.$i.'</a></li>';
					}

				}

			}

			if($page < $total_pages) {
				$html .= '<li><a href="'.$link.($page+1).'" title="Next">&raquo;</a></li>';
			}

		}

		//$html .= '</ul> <p>Page '.$page.' of '.$total_pages.'</p> </div>';

		return $html;

	}








	/**
	 * @method read   handy function to check the notification as read based on ID
	 * @param  integer $id notification ID
	 * @return boolean  true on success or false on error
	 */
	public function read($id) {
		/* check if the notification is of logged user */
		global $wpdb;
		$table = $wpdb->prefix.$this->table_name;
		$update = $wpdb->update( $table, array('status'=>1), $where = array('ID'=>$id, 'user_id'=>$this->ID));
		return $update;
	}
	/* SECURITY FUNCTIONS
	------------------------------------------------------------------- */
	/**
	 * decrypt string
	 * @method decrypt
	 * @param  [type]  $string [description]
	 * @param  string  $key    [description]
	 * @return [type]          [description]
	 */
	function decrypt($string, $key='humanbody_messages_2') {
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}
	/**
	 * encrypt string
	 * @method encrypt
	 * @param  [type]  $string [description]
	 * @param  string  $key    [description]
	 * @return [type]          [description]
	 */
	function encrypt($string, $key='humanbody_messages_2') {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}
    /* Installer
    ------------------------------------------------------------ */
	/**
	 * @method install()  install the notifications database with a custom table just for this
	 * @return boolean   true on successfull install or false on error
	 */
	public function install() {
		/* table name */
		$notifications_table = $this->table_name;
		/* table installer class */
		$installer = new spinal_table_install();
		/* #NOTE
		the sql statement needs [table_name] and [charset]
		the installer will take care of the rest
		 */
		$sql = "CREATE TABLE IF NOT EXISTS [table_name] (
				  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'notification id',
				  `date` DATETIME NOT NULL COMMENT  'time of the notification',
				  `post_type` varchar(50) NOT NULL COMMENT 'post type',
				  `goto` varchar(200) NOT NULL COMMENT '#elementid',
				  `post_id` mediumint(9) NOT NULL COMMENT 'post id',
				  `user_id` mediumint(9) NOT NULL COMMENT 'user id',
				  `from_id` mediumint(9) NOT NULL COMMENT 'user id that created the notification',
				  `comment_id` mediumint( 9 ) NOT NULL COMMENT 'comment id if the type is comment',
				  `status` int(1) NOT NULL DEFAULT '0' COMMENT 'notification status (read / unread)',
				  PRIMARY KEY (`ID`),
				  UNIQUE KEY `ID` (`ID`),
				  KEY `ID_2` (`ID`)
			  ) [charset];";
			 /* returns true or false on install success or error */
			 return $installer->install($sql,$notifications_table);
	}
	/**
	 * @method add()  add notification to database every time an event is made, still needs more work
	 */
	public function add($post,$fromID, $elementID, $toID = false, $type = false, $comment_id = false) {
		/*
			If we comment and the comment is not a reply, give notification to author of the post
			Valid only if is topic or forum

		*/
		if ($post->type=='forum' || $post->type=='topic' || $post->type=='meeting') {
			$author = is_object($post) ? $post->post_author : $toID;
			$toID = $toID ? $toID : $author;
		}

		/* if the comment is not a reply, give notification to nobody */
		if (!$toID) return;

		global $wpdb;
		$notifications_table = $wpdb->prefix . $this->table_name;
		if($toID != $fromID) :
			$posttype = is_object($post) ? $post->post_type : $type;
			$post_id = is_object($post) ? $post->ID : $post;
			//deg(array($post,$fromID,$elementID,$toID, $posttype, $post_id));
			$insert = $wpdb->insert(
				$notifications_table,
				array(
					'date' => current_time( 'mysql' ),
					'post_type' => $type ? $type : $posttype,
					'goto' => $elementID,
					'post_id' => $post_id,
					'user_id' => $toID,
					'from_id' => $fromID,
					'comment_id' => $comment_id,
					'status' => 0
				)
			);

			$wpdb->print_error();
		endif;
	}
}
/* install notifications [it only runs once] */
$notifications = new notifications();
$notifications->install();



/* NOTIFICATIONS FILTERS
 ------------------------------------------------------------------------------------------------
 ------------------------------------------------------------------------------------------------ */



/* MARK NOTIFICATION AS READ
 ------------------------------------------------------------------------------------------------ */
add_filter( 'spinal-primal', 'read_notification', $priority = 10);
function read_notification() {
	$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	/* read notification */
	$notification = new notifications();
	$n_ID = $notification->decrypt($_GET['n']);
	if((int)$n_ID > 0) :
		$notification->read($n_ID);
	endif;
}
/* END MARK NOTIFICATION AS READ
 ------------------------------------------------------------------------------------------------ */


/* TOPIC NOTIFICATION ON REPLY
 ------------------------------------------------------------------------------------------------ */
/* support for topics */
add_filter( 'bbp_new_reply', 'add_reply_notification', $priority = 10, $accepted_args = 5 );
function add_reply_notification($reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author) {
	/* add notification */
	$notification = new notifications();
	$notification->add(get_page($topic_id), $reply_author, the_permalink(), $reply_to);
}
/* COMMENT NOTIFICATION ON POST
 ------------------------------------------------------------------------------------------------ */
/* support for comments */
add_filter( 'spinal_comment_notifications', 'add_comment_notification', $priority = 10, $accepted_args = 4 );
function add_comment_notification($commentID, $currentPOST, $userID, $replyID ) {
	/* add notification */
	$notification = new notifications();
	if($replyID > 0) {
		$comment = get_comment($replyID, 'OBJECT');
		$notification->add($currentPOST, $userID, 'comment-'.$commentID, $comment->user_id, 'reply');
	}
	else {

		$notification->add($currentPOST, $userID, 'comment-'.$commentID);
	}
}
/* USER MESSAGE NOTIFICATION
 ------------------------------------------------------------------------------------------------ */
add_filter('add_message_notification', 'add_message_notification', $priority = 10, $accepted_args = 1);
function add_message_notification($messageID) {

	$user = wp_get_current_user();
	$user_id = $user->ID;

	$mcr = new user_messages();
	$message = $mcr->get_message($messageID);
	$room = $mcr->get_room($message->chat_id);
	$room = $room[0];
	$link = page('my-account-message-room').'?hb='.$mcr->encrypt($room->ID);
	$fromID = $message->user_id;
	$toID = $room->from_id == $user_id ? $room->to_id : $room->from_id;

	/* add notification */
	$notification = new notifications();
	$notification->add($room->ID, $fromID, $link, $toID, $type ='user-message', $messageID);

}
/* MEETING INVITATION
 ------------------------------------------------------------------------------------------------ */
/* add invite based notification */
add_action('member-friends-list', 'add_invite_notification');
add_action('member-map', 'add_invite_notification');
add_action('member-profile', 'add_invite_notification');

function add_invite_notification() {

	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

	$notifications = new notifications();
	/* get current user id */
	$fromID = (int)$notifications->ID;
	$toID = (int)$notifications->decrypt($_POST['u']);
	if($_POST['meeting'] && $toID) :
		$id = $notifications->decrypt($_POST['meeting']);
		$notifications->add($id, $fromID, false, $toID, $type ='meeting', false);
		// wp_redirect( page('my-account-friendlist') );
	endif;
}
/* FIREND REQUEST
 ------------------------------------------------------------------------------------------------ */
/* add invite based notification */
add_action('add-to-friends-list', 'add_friend_notification');
function add_friend_notification($userID) {

	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

	$notifications = new notifications();
	/* get current user id */
	$fromID = (int)$notifications->ID;
	$toID = $userID;
	if($userID) :
		$notifications->add(false, $fromID, false, $toID, $type ='friend-request', false);
		// wp_redirect(page('my-account-friendlist'));
	endif;
}
 ?>
