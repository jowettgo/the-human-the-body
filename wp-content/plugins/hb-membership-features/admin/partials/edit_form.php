


<div class="container">
    <br />
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php
                        if(isset($item->id)) {
                            echo "$item->title <span class='text-muted'>(id: $item->id)</span>";
                        } else {
                            echo "New box";
                        }
                    ?></h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo $base_url ?>" method="POST">
                        <input type="hidden" value="POST_ITEM" name="action" />
                        <input type="hidden" value="<?php echo $item->id ?>" name="item_id" />
                        <div class="form-group">
                            <label for="item_title">Title</label>
                            <input type="text" class="form-control" id="item_title" placeholder="Title" value="<?php echo $item->title ?>" name="item_title" required>
                        </div>
                        <div class="form-group">
                            <label for="item_link">Link</label>
                            <input type="text" class="form-control" id="item_link" placeholder="Link" value="<?php echo $item->link ?>" name="item_link">
                        </div>
                        <div class="form-group">
                            <label for="item_content">Content</label>
                            <textarea class="form-control" rows="3" id="item_content" placeholder="Content" name="item_content"><?php echo $item->content ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="item_icon">Icon</label>
                            <input type="text" class="form-control" id="item_icon" placeholder="Icon" value="<?php echo $item->icon ?>" name="item_icon" required>
                        </div>
                        <button type="submit" class="btn btn-default">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php


