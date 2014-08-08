<?php
print_r($listingData);die;
$userData    = $listingData['userData'];
$listingData = $listingData['listing'];
$hasApplied  = $this->techpear->listings_hasUserAppliedToListing($this->user->id, $listingData->id);
$hasApldCls  = ($hasApplied ? 'listing-rowItem-hasApplied-' . $listingData->id : 'listing-rowItem-hasNotApplied-' . $listingData->id);
?>

<div class="row-item row-item-user <?php echo $hasApldCls; ?>">
    <header><a href="/profile/<?php echo $userData->id; ?>"><img src="<?php echo $userData->thumb_large; ?>"></a></header>

    <section>
        <div class="username"><?php echo $listingData->title; ?></div>
        <div class="meta"><?php echo $userData->city; ?>, <?php echo $userData->country; ?></div>
        <div class="description"><?php echo $listingData->description; ?></div>
    </section>

    <footer>
        <?php if($hasApplied) echo $this->techpear->listings_applyBtn($this->user->id, $listingData->id); ?>
        <a href="/profile/<?php echo $userData->id; ?>" class="btn">View</a>
    </footer>
</div>