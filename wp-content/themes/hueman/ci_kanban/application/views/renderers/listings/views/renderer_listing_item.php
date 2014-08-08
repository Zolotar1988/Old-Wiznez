<?php
$userData    = $listingData['userData'];
$listingData = $listingData['listing'];
$hasApplied  = $this->techpear->listings_hasUserAppliedToListing($this->user->id, $listingData->id);
$hasApldCls  = ($hasApplied ? 'listing-rowItem-hasApplied-' . $listingData->id : 'listing-rowItem-hasNotApplied-' . $listingData->id);
$randomGoToString = $userData->id . rand(0,99999);
?>

<div class="row-item row-item-user <?php echo $hasApldCls; ?>">
    <header>
        <a href="/profile/<?php echo $userData->id; ?>">
            <div class="image_sideBorder border_right"></div>
            <img src="<?php echo $userData->thumb_large; ?>">
        </a>
    </header>

    <section>
        <div class="username"><?php echo $listingData->title; ?></div>
        <div class="meta"><?php echo $userData->city; ?>, <?php echo $userData->country; ?></div>
        <div class="meta second_meta"><?php echo days_until($listingData->deadline); ?> days left to apply</div>
        <div class="meta second_meta"><?php echo ($listingData->intern_onLocation ? 'On Location' : 'Anywhere'); ?></div>
    </section>

    <div class="overlay row-item-actions" id="listingOverlay_<?php echo $randomGoToString; ?>">
        <?php if($hasApplied) echo $this->techpear->listings_applyBtn($this->user->id, $listingData->id); ?>
        <a href="/profile/<?php echo $userData->id; ?>?via=listing&via_id=<?php echo $listingData->id; ?>" class="btn">View</a>
    </div>
</div>


<script type="application/javascript">
    $("#listingOverlay_<?php echo $randomGoToString; ?>").click(function(e) {
        if(e.target != this) return; // only continue if the target itself has been clicked
        window.location.href = '/profile/<?php echo $userData->id; ?>?via=listing&via_id=<?php echo $listingData->id; ?>';
    });
</script>