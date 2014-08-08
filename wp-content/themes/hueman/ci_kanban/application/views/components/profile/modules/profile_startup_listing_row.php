<div class="block" id="profileListingRow-<?php echo $listing->id; ?>">
    <?php if($this->user->id == $listing->user_id ) { ?><div class="profileListingInactiveActive"><?php echo (!$listing->isActive ? '<div class="alert">This listing is inactive</div>' : '<div class="alert success">This listing is active</div>'); ?></div><?php } ?>
    <div class="title">
        <?php echo $listing->title; ?>
        <?php if($this->user->id == $listing->user_id) { ?><div class="profileListingRowEdit"><a href="#" onclick="listings.admin.openEdit(<?php echo $listing->id; ?>); return false;">Edit this listing</a></div><?php } ?>
    </div>
    <div class="body">

            <div class="inner_body keyValContainer">
                <div class="keyVal_row"><div class="key">Description</div><div class="val"><?php echo $listing->description; ?></div></div>
                <div class="keyVal_row"><div class="key">Start period</div><div class="val"><?php echo date('M/Y', strtotime($listing->date_from)); ?></div></div>
                <div class="keyVal_row"><div class="key">End period</div><div class="val"><?php echo date('M/Y', strtotime($listing->date_to)); ?></div></div>
                <div class="keyVal_row"><div class="key">Possibility for full-time position after ended internship</div><div class="val"><?php echo $techpear_config['listings_fields']['intern_possibleJob']['options'][$listing->intern_possibleJob]; ?></div></div>
                <div class="keyVal_row"><div class="key">Evaluation date</div><div class="val"><?php echo $techpear_config['listings_fields']['applications_evaluationDate']['options'][$listing->applications_evaluationDate]; ?></div></div>
                <div class="keyVal_row"><div class="key">Payment currency</div><div class="val"><?php echo $techpear_config['listings_fields']['compensation_currency']['options'][$listing->compensation_currency]; ?></div></div>
                <div class="keyVal_row"><div class="key">Estimated pay</div><div class="val"><?php echo $listing->compensation_estimatedPay; ?></div></div>
                <div class="keyVal_row"><div class="key">Position</div><div class="val"><?php echo $techpear_config['listings_fields']['intern_type']['options'][$listing->intern_type]; ?></div></div>
                <div class="keyVal_row"><div class="key">On location</div><div class="val"><?php echo ($listing->intern_onLocation ? 'Yes' : 'No'); ?></div></div>
            </div>

        <?php if($listing->user_id != $this->user->id) { ?><div class="block_footer"><?php echo $this->techpear->listings_applyBtn($this->user->id, $listing->id); ?></div><?php } ?>
    </div>
</div>