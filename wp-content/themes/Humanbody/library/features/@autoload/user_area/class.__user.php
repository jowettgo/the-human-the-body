<?php
/**
 * core constructor class
 */
class __user
{
	public function id() {
		$user = wp_get_current_user();
		if($user->ID) :
			return $user->ID;
		endif;
		return false;
	}
	public function meta() {
		$id = self::id();
		if($id !== false) :
			return get_user_meta($id);
		endif;
		return false;
	}

}
 ?>
