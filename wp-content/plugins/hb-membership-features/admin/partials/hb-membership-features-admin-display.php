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

<div class="wrap container">
    <h2>Membership Features <small>Human-Body</small></h2>

    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Reorder boxes</h3>
                </div>
                <div class="panel-body">
                    <ul id="sortable">
                        <?php
                        foreach ($results as $res) {
                            $edit_url =  $base_url . "&edit=$res->id";
                            $delete_url = $base_url . "&delete=$res->id";
                            echo "<li class='ui-state-default' data-id='$res->id'>
                                <i class='fa fa-ellipsis-v' style='padding-right:10px;'></i> $res->title
                                <div class='pull-right'><a href='$edit_url'><i class='fa fa-pencil-square'></i></a> <a href='$delete_url'><i class='fa fa-minus-square delete'></i></a></div>
                            </li>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="panel-footer">
                    <button type="button" id="sortable-save" class="btn btn-primary"><i class="fa fa-floppy-o" id="sortable-icon"></i> <span id="sortable-label">Save Order</span></button><span id="sortable-result" class="text-muted" style="display:none;"> Saved.</span>
                    <a href="<?php echo $base_url . '&add=item' ?>" id="item-add" class="btn btn-info pull-right"><i class="fa fa-plus-square-o"></i> Add Box</a>
                </div>
            </div>
        </div>

    </div>




</div>
