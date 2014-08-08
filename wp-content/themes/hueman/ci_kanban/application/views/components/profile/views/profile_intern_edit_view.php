
<script>$("body").attr('id', 'component-profileEdit')</script>
<div class="block_heading"><?php if($this->user->currentUserData->data_filled) echo 'Welcome, ' . $this->user->currentUserData->name . '! Tell us a bit about yourself.'; ?></div>
<div class="block form_block">
    <div class="title">We’ll use this information to create your profile page</div>
    <div class="body">
        <form method="POST" action="/profile/profile_actions_controller/edit">
            <?php $this->load->view('modules/imageupload/views/imageupload_view', array('imageUploadConfig' => $imageUploadConfig)); ?>
            <?php $this->load->view('renderers/tagsystem/views/renderer_tag_selection', array('labelTitle' => 'Skills'));  ?>
            <?php echo generateInputFields($this->user->profileTypes_fields($this->user->currentUserData->profile_type), $this->user->currentUserData); ?>
            <?php echo generate_submitButton(($this->user->currentUserData->data_filled ? 'Save your profile' : 'Take me to my profile')); ?>
        </form>
    </div>
</div>


<script type="application/javascript">
    <?php
    $day = date('d', strtotime($this->user->currentUserData->birthDate));
    $mon = date('m', strtotime($this->user->currentUserData->birthDate));
    $yer = date('Y', strtotime($this->user->currentUserData->birthDate));
    ?>
    $("#profile_birthdate_day").val('<?php echo $day; ?>');
    $("#profile_birthdate_month").val('<?php echo $mon; ?>');
    $("#profile_birthdate_year").val('<?php echo $yer; ?>');
</script>

<?php if(!$this->user->currentUserData->data_filled) { ?><script type="application/javascript">$("#main_header").hide(); $("#header_float_placeholder").hide();  $( document ).ready(function() { $("#site_footer").hide(); });</script><?php } ?>

<script type="text/javascript" charset="utf-8">(function($){ $(function(){ $('#profile_country').selectToAutocomplete(); }); })(jQuery);</script>