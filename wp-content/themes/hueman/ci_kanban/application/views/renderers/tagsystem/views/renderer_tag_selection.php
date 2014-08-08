<div class="row input-block">
    <div class="input-container small-8 columns">
        <label><?php echo $labelTitle; ?></label>
        <div class="tag_suggestion_container cf card" id="tag_suggestion_container-interests">
            <div class="interestsAddSection">
                <div class="tag_suggestion_input cf">
                    <div class="tag_suggestion_input_wrap"><input id="profile_interestsInput" onfocus="tagsystem.tagField_focus(this)" class="sized_fullWidth tags" type="text" data-container="interests" onkeyup="return tagsystem.fetch_suggestions(event, this)" onkeydown="return tagsystem.checkForSpecialKeyPress(event, this)" onblur="tagsystem.tagField_blurred(this)"></div>
                    <ul class="tag_suggestion_input_suggestions" style="display: none"></ul>
                </div>
            </div>
            <div class="tag_suggestion_tags"><?php echo $this->user->user_tags($this->user->id, TRUE); ?></div>
        </div>
    </div>
</div>