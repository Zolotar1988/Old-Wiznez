<?php $randId = '_' . substr(md5(microtime()), 0, 10); ?>

<form class="kanban_fileUploadForm">
    <div id="queue<?php echo $randId; ?>" style="display: none"></div>
    <input id="file_upload<?php echo $randId; ?>" type="file" name="file_upload" />
    <a href="javascript:$('#file_upload').uploadifive('upload')"><?php echo lang('photoUpload_selectPhoto'); ?></a>

    <div id="photoUpload_photo_progress" style="display: none"><div class="progress_bar"><div class="bar"></div></div></div>
</form>


<script type="text/javascript">
    $(function() {
        $('#file_upload<?php echo $randId; ?>').uploadifive({
            'multi'            : false,
            'removeCompleted'  : true,
            'buttonClass'      : 'btn btn-primary',
            'buttonText'       : 'Upload files ',
//            'queueID'          : 'photoUpload_photo_chooseFile',
            'uploadScript'     : '/kanban/kanban_upload_controller/fileUpload?taskId=<?php echo $taskId; ?>',
            'onUploadComplete' : function(file, data)   { console.log(data); kanban.file_upload.upload_complete(data); },
            'onProgress'       : function(file, e)      { kanban.file_upload.upload_progress(file, e); },
            'onUpload'         : function()             { kanban.file_upload.file_selected(); }
        });
    });
</script>
<style>
    .uploadifive-button.btn-primary {
        height: 40px !important;
        cursor: pointer !important;
    }
    .uploadifive-button.btn-primary input {
        height: 40px !important;
        cursor: pointer !important;
    }

    a.close {
        display: none;
    }
</style>