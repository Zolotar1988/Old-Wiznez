<div class="row">

    <div class="large-7 columns">
        <div class="item_row_container">
            <div class="block">
                <div class="title"></div>
                <form method="POST" action="/listings/listings_actions_controller/apply">
                    <div class="body">
                        <div class="cf apply_questions">
                            <?php
                            $questions = json_decode($listing->intern_questions);
                            if(!$questions) $questions = array();
                            foreach($questions as $key => $question)
                            {
                                ?>
                                <div class="keyVal_row">
                                    <div class="key"><?php echo $question; ?></div>
                                    <div class="val">
                                        <textarea class="sized_fullWidth" placeholder="" id="listings_intern_questions[" name="listings_intern_questions[<?php echo $key; ?>]" required=""></textarea>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <input type="hidden" value="<?php echo $listing->id; ?>" name="listingId">
                        <input type="submit" value="Apply" class="btn">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="large-5 columns">
        <div class="block">
            <div class="title"><?php echo $listing->title; ?></div>
            <div class="body">
                <div class="position-listing inner_block">
                    <div class="keyValContainer">
                        <div class="keyVal_row"><div class="key">Description</div><div class="val"><?php echo $listing->description; ?></div></div>
                        <div class="keyVal_row"><div class="key">Work hours per week</div><div class="val"><?php echo $listing->time_workHours; ?></div></div>
                        <div class="keyVal_row"><div class="key">Possibility for full-time position after ended internship</div><div class="val"><?php echo $techpear_config['listings_fields']['intern_possibleJob']['options'][$listing->intern_possibleJob]; ?></div></div>
                        <div class="keyVal_row"><div class="key">Evaluation date</div><div class="val"><?php echo $techpear_config['listings_fields']['applications_evaluationDate']['options'][$listing->applications_evaluationDate]; ?></div></div>
                        <div class="keyVal_row"><div class="key">Payment currency</div><div class="val"><?php echo $techpear_config['listings_fields']['compensation_currency']['options'][$listing->compensation_currency]; ?></div></div>
                        <div class="keyVal_row"><div class="key">Estimated pay</div><div class="val"><?php echo $listing->compensation_estimatedPay; ?></div></div>
                        <div class="keyVal_row"><div class="key">Position</div><div class="val"><?php echo $techpear_config['listings_fields']['intern_type']['options'][$listing->intern_type]; ?></div></div>
                        <div class="keyVal_row"><div class="key">On location</div><div class="val"><?php echo ($listing->intern_onLocation ? 'Yes' : 'No'); ?></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>