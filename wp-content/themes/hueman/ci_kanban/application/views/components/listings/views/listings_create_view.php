<?php
$isEditing              = (isset($listing) ? TRUE : FALSE);
$extraStuffForSubmit    = ($isEditing ? '<a href="#" onclick="listings.listing.edit_delete(' . $listing->id . ')" class="btn">Delete application</a>' : '');
?>

<?php
if($this->input->get('ref'))
{
    switch($this->input->get('ref'))
    {
        case 'profile':
            echo '<a href="/profile/edit?ref=listing">Return to profile edit</a>';
    }
}
?>

<div class="block">
    <div class="title"><?php echo ($isEditing ? 'Edit your application' : 'Create a new application'); ?></div>
    <div class="body">
        <form method="POST" action="/listings/listings_actions_controller/<?php echo ($isEditing ? 'edit' : 'create'); ?>">
            <?php if($isEditing) { ?><input type="hidden" name="listingId" value="<?php echo $listing->id; ?>"><?php } ?>
            <?php echo generateInputFields($inputFields, ($isEditing ? $listing : FALSE)); ?>
            <?php echo generate_submitButton(($isEditing ? 'Edit your application' : 'Create your application'), $extraStuffForSubmit); ?>
        </form>
    </div>
</div>


<script type="application/javascript">
    $("#listings_compensation_estimatedPay").attr('onkeypress', 'return event.charCode >= 48 && event.charCode <= 57');
</script>