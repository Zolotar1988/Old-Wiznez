<script>$("body").attr('id', 'component-profile')</script>
<?php
$headerModule['avatar']     = $listingCreator->thumb_large;
$headerModule['title']      = $listingCreator->name;
$headerModule['meta']       = $listingCreator->city . ', ' . $listingCreator->country . ' · ' . '<a href="' . $listingCreator->url_website . '" target="_blank">' . $listingCreator->url_website . '</a>';
$headerModule['actions']    = $this->techpear->listings_applyBtn($this->user->id, $listing->id) . '<a href="/profile/' . $listingCreator->id . '" class="btn">Company profile</a>';
$this->load->view('components/profile/modules/profile_header_module', $headerModule);
?>


<div class="wrap" id="listing_info">

    <div class="row">
        <div class="large-8 columns">
            <div class="block"><div class="title">Position description</div><div class="body"><?php echo $listing->description; ?></div></div>
            <div class="block"><div class="title">Type of intern we seek</div><div class="body"><?php echo $listing->intern_type; ?></div></div>
        </div>

        <div class="large-4 columns">
            <div class="block">
                <div class="title">Key info</div>
                <div class="body keyValContainer">
                    <div class="keyVal_row"><div class="key">Work hours</div><div class="val"><?php echo $listing->time_workHours; ?></div></div>
                    <div class="keyVal_row"><div class="key">Open positions</div><div class="val"><?php echo $listing->positions_number; ?></div></div>
                    <div class="keyVal_row"><div class="key">Eval. date</div><div class="val"><?php echo $listing->applications_evaluationDate; ?></div></div>
                    <div class="keyVal_row"><div class="key">Est. pay</div><div class="val"><?php echo $listing->compensation_estimatedPay; ?></div></div>
                    <div class="keyVal_row"><div class="key">On location</div><div class="val"><?php echo $listing->intern_onLocation; ?></div></div>
                </div>
            </div>
        </div>
    </div>

</div>