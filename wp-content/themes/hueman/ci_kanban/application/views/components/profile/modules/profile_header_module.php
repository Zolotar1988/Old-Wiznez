<section class="cf" id="profile_header">
    <div class="wrap">

        <div class="row">
            <div class="large-9 columns">
                <div class="left"><img src="<?php echo $avatar; ?>"></div>
                <div class="info">
                    <div class="title"><?php echo $title; ?></div>
                    <div class="meta"><?php echo $meta; ?></div>
                </div>
            </div>

            <div class="large-3 columns actions">
                <?php if($actions) echo $actions; ?>
            </div>
        </div>

    </div>
</section>