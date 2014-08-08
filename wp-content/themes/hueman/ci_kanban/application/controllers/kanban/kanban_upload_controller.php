<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kanban_upload_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
    }

    function fileUpload()
    {
        $pre_id = md5(microtime());
        $userId = $this->user->id;

        # Move files uploaded
        $filesLocation =  '/srv/www/monkeywork/files/' . $userId;
        if(!is_dir($filesLocation)) mkdir($filesLocation);

        if(isset($_FILES['Filedata']))  $fileLocation  = $filesLocation . '/' . $_FILES['Filedata']['name'];
        if(isset($_FILES['Filedata']))  move_uploaded_file($_FILES['Filedata']['tmp_name'], $fileLocation);
        $fileName_compAn = (isset($_FILES['Filedata']['name']) ? $_FILES['Filedata']['name'] : '');

        $insert['fileName']     = $pre_id;
        $insert['pre_id']       = $pre_id;
        $insert['uploadedName'] = $_FILES['Filedata']['name'];
        $insert['uploader']     = $this->user->id;
        $insert['taskId']       = $this->input->get('taskId');

        $this->db->insert('kanban_fileUploads', $insert);
    }
}