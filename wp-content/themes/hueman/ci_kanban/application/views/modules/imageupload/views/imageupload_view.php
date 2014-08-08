<?php
extract($imageUploadConfig);
?>

<script src="/application/views/modules/imageupload/assets/uploadifive/jquery.uploadifive.min.js"></script>


<div class="row input-block cf" id="avatarUploadArea">
    <div class="input-container small-8 columns">
        <img src="<?php echo $this->user->currentUserData->thumb; ?>">
        <input id="file_upload" name="file_upload" type="file" multiple="true" class="btn">
    </div>
</div>


<script type="text/javascript">
    $(function() {
        $('#file_upload').uploadifive({
            'multi'            : <?php echo $multi; ?>,
            'removeCompleted'  : true,
            'buttonClass'      : 'btn',
            'buttonText'       : '<?php echo $buttonText; ?>',
            'uploadScript'     : '<?php echo $uploadScript; ?>',
            'onUploadComplete' : function(file, data)   { <?php echo $onUploadComplete; ?>(data); },
            'onProgress'       : function(file, e)      { <?php echo $onProgress; ?>(file, e); },
            'onUpload'         : function()             { <?php echo $onUpload; ?>(); }
        });
    });
</script>