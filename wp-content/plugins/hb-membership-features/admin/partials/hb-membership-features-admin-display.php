<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.iquatic.com
 * @since      1.0.0
 *
 * @package    Hb_Membership_Features
 * @subpackage Hb_Membership_Features/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">HB Membership Features Admin Display</div>

<?php
//    var_dump($results);
    var_dump($_REQUEST['action']);
?>
<ul id="sortable">
    <?php
        foreach ($results as $res) {
            echo "<li class=\"ui-state-default\"><i class=\"fa fa-bars\" aria-hidden=\"true\"></i>$res->title</li>";
        }
    ?>
</ul>

<form action="" method="POST">
    <input type="submit" value="Submit">
</form>