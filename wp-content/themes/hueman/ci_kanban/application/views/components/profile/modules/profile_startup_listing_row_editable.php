<div class="block profileListingRowInEditing" id="profileListingRow-<?php echo ($isEditing ? $listing->id : 'new'); ?>-editable" style="display: <?php echo ($isEditing ? 'none' : 'block'); ?>">
    <form id="<?php echo ($isEditing ? 'listing_edit_'. $listing->id : 'listing_create_new'); ?>">
        <div class="title">
            <span id="title_placeholderText"><?php echo $this->user->currentUserData->name; ?> is looking for <span class="titlePlaceholder_position"><?php echo ($isEditing ? $listing->intern_type : '____'); ?></span> <span class="titlePlaceholder_location"><?php echo ($isEditing ? ($listing->intern_onLocation ? 'in ' . $this->user->currentUserData->city : 'anywhere') : 'in ' . $this->user->currentUserData->city); ?></span></span>
            <input type="hidden" class="sized_fullWidth" placeholder="Title" value="" id="listings_title" name="listings_title" required="">
        </div>
        <div class="body">
            <div class="position-listing inner_block">
                <div class="inner_body keyValContainer">
                    <div class="keyVal_row"><div class="key">Intern type</div><div class="val"><select class="sized_fullWidth" id="listings_intern_type" name="listings_intern_type" required=""><option value="">-- Select --</option><option value="designer">Designer</option><option value="programmer">Programmer</option><option value="marketer">Marketer</option><option value="finance">Finance</option><option value="pr">PR</option></select></div></div>
                    <div class="keyVal_row"><div class="key">Start period</div><div class="val"><select class="sized_fullWidth dateField_month" id="listings_date_from_month" name="listings_date_from_month" required=""><option value="">-- Select --</option><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select><select class="sized_fullWidth dateField_year" id="listings_date_from_year" name="listings_date_from_year" required=""><option value="">-- Select --</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option></select></div></div>
                    <div class="keyVal_row"><div class="key">End period</div><div class="val"><select class="sized_fullWidth dateField_month" id="listings_date_to_month" name="listings_date_to_month" required=""><option value="">-- Select --</option><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select><select class="sized_fullWidth dateField_year" id="listings_date_to_year" name="listings_date_to_year" required=""><option value="">-- Select --</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option></select></div></div>
                    <div class="keyVal_row"><div class="key">On location</div><div class="val"><select class="sized_fullWidth" id="listings_intern_onLocation" name="listings_intern_onLocation" required=""><option value="">-- Select --</option><option value="1">Yes</option><option value="0">No</option><option value="2">Either</option></select></div></div>
                    <div class="keyVal_row"><div class="key">Possibility for full-time position after ended internship</div><div class="val"><select class="sized_fullWidth" id="listings_intern_possibleJob" name="listings_intern_possibleJob" required=""><option value="">-- Select --</option><option value="1">Yes</option><option value="0">No</option><option value="maybe">Maybe</option></select></div></div>
                    <div class="keyVal_row"><div class="key">Description</div><div class="val"><textarea class="sized_fullWidth" placeholder="Give a brief description of the position, what type of intern you seek and the responsibilities" id="listings_description" name="listings_description" required=""></textarea></div></div>
                    <div class="keyVal_row"><div class="key">Work hours per week</div><div class="val"><select class="sized_fullWidth" id="listings_time_workHours" name="listings_time_workHours" required=""><option value="">-- Select --</option><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option></select></div></div>
                    <div class="keyVal_row"><div class="key">Evaluation date</div><div class="val"><select class="sized_fullWidth" id="listings_applications_evaluationDate" name="listings_applications_evaluationDate" required=""><option value="">-- Select --</option><option value="asap">ASAP</option><option value="after_deadline">After deadline</option></select></div></div>
                    <div class="keyVal_row"><div class="key">Payment currency</div><div class="val"><select class="sized_fullWidth" id="listings_compensation_currency" name="listings_compensation_currency" required=""><option value="">-- Select --</option><option value="usd">USD</option><option value="eur">EUR</option><option value="nok">NOK</option><option value="sek">SEK</option><option value="dkk">DKK</option></select></div></div>
                    <div class="keyVal_row"><div class="key">Estimated pay</div><div class="val"><input type="text" class="sized_fullWidth" placeholder="Estimated pay" value="" id="listings_compensation_estimatedPay" name="listings_compensation_estimatedPay" required=""></div></div>
                    <div class="keyVal_row"><div class="key">Questions</div><div class="val"><textarea class="sized_fullWidth" placeholder="Questions" id="listings_intern_questions" name="listings_intern_questions" required=""></textarea></div></div>
                </div>
            </div>
            <div class="block_footer">
                <a class="btn" href="#" onclick="listings.admin.<?php echo ($isEditing ? 'update(' . $listing->id . ')' : 'create_new()'); ?>; return false;"><?php echo ($isEditing ? 'Update your application' : 'Create your application'); ?></a>
                <?php if($isEditing) { ?><a class="btn red" href="#" onclick="listings.listing.edit_delete(<?php echo $listing->id; ?>); return false;">Delete this listing</a><?php } ?>
            </div>
        </div>
    </form>
</div>


<script type="application/javascript">
    $("#listings_intern_type").change(function() { $(".titlePlaceholder_position").text($("#listings_intern_type").val()); $("#listings_title").val($("#title_placeholderText").text()); });
    $("#listings_intern_onLocation").change(function() { $(".titlePlaceholder_location").text( ($("#listings_intern_onLocation").val() == '1' ? 'in <?php echo $this->user->currentUserData->city; ?>' : 'anywhere') ); $("#listings_title").val($("#title_placeholderText").text()); });
</script>


<script type="application/javascript">
    <?php
    if($isEditing)
    {
        foreach($listing as $key => $val)
        {
        if($key == 'intern_questions')
        {
            $x = '';
            foreach(json_decode($val) as $txt)
            {
                $x.=$txt . '\n';
            }
            $val = $x;
        }
            ?>
    $("#profileListingRow-<?php echo $listing->id; ?>-editable #listings_<?php echo $key; ?>").val('<?php echo $val; ?>');
    <?php
    }
}
else
{
?>
    $("#listing_create_new .title input").focus();
    <?php
}
?>
</script>