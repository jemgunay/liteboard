<?php

class File extends Database {

    const TABLE_NAME = 'file';
    private $f3, $upload_dir;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
        $this->upload_dir = DIR_BASE . "uploads/";
    }

    // check if folder exists
    public function check_exists($file_id) {
        $this->load(array('file_id=?', $file_id));
        return $this->loaded();
    }

    // fetch single folder
    public function fetch($file_id) {
        return $this->db->exec('SELECT * FROM content_file_join WHERE file_id = ?', $file_id)[0];
    }

    // create
    public function create() {
        // check if file upload was successful
        $upload_result = $this->process_upload();
        if (!$upload_result)
            return false;

        $this->reset();
        $this->name = $upload_result['original_name'];
        $this->stored_name = $upload_result['stored_name'];
        $this->date_created = date('Y-m-d H:i:s');
        if ($this->f3->exists('POST.description'))
            $this->description = $this->f3->get('POST.description');

        $this->insert();
        return true;
    }

    // edit
    public function edit() {
        // check if required POST values exist
        $required_fields = array(array('target_id'), array('Target ID'));
        if (!Misc::check_required_fields($required_fields)) return;

        // replacement file upload is optional
        $f = $this->f3->get('FILES')['file-upload'];
        $upload_result = false;
        if ($f != null) {
            // check if file upload was successful
            $upload_result = $this->process_upload();
            if (!$upload_result)
                return;
        }

        // check target exists and load
        if (!$this->check_exists($this->f3->get('POST.target_id'))) {
            $this->f3->set('error', 'File does not exist.');
            return;
        }

        // delete old file and amend changes if one was uploaded
        if ($upload_result) {
            $this->delete_file($this->stored_name);
            $this->name = $upload_result['original_name'];
            $this->stored_name = $upload_result['stored_name'];
        }

        if ($this->f3->exists('POST.description'))
            $this->description = $this->f3->get('POST.description');

        $this->update();
    }

    public function process_upload() {
        $f = $this->f3->get('FILES')['file-upload'];

        // check file has been submitted
        if ($f == null) {
            $this->f3->set('error', 'Please select a file to upload.');
            return false;
        }
        // check for errors
        if ($f['error'] != 0) {
            $this->f3->set('error', 'An unexpected upload error occurred.');
            return false;
        }
        // enforce minimum file size
        if ($f["size"] > 20000000) {
            $this->f3->set('error', 'File must be no larger than 20MB in size.');
            return false;
        }
        // generate random, unique file name
        while (true) {
            $random_name = uniqid();
            $this->load(array('stored_name=?', $random_name));
            if (!$this->loaded())
                break;
        }

        // move uploaded file from temp location to uploads folder
        if (!move_uploaded_file($f["tmp_name"], $this->upload_dir . $random_name)) {
            $this->f3->set('error', 'An unexpected upload error occurred.');
            return false;
        }

        // upload success
        return array('original_name'=>$f['name'], 'stored_name'=>$random_name);
    }

    // delete file and record
    public function delete($target_id) {
        // check target exists
        if (!$this->check_exists($target_id)) {
            $this->f3->set('error', 'File does not exist.');
            return false;
        }

        $this->delete_file($this->stored_name);
        $this->erase();
        return true;
    }

    // delete file
    public function delete_file($file_name) {
        if (file_exists($this->upload_dir . $file_name)) {
            unlink($this->upload_dir . $file_name);
        }
    }

    // download
    public function perform_download($file_id) {
        // check target exists
        if (!$this->check_exists($file_id)) {
            $this->f3->set('error', 'File does not exist.');
            return false;
        }

        // get file path and mime type
        $file_path = $this->upload_dir . $this->stored_name;
        //$file_extension = pathinfo($this->name, PATHINFO_EXTENSION);
        $file_mime = mime_content_type($file_path);

        // set headers based on file type/extension to determine whether to open with browser or download
        header('Content-Type: ' . $file_mime);
        header('Content-Disposition: inline; filename="' . $this->name . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file_path));
        header('Accept-Ranges: bytes');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        ini_set('zlib.output_compression','0');

        // increment download count
        $this->download_count = $this->download_count + 1;
        $this->update();

        // display file contents
        $content = file_get_contents($file_path);
        die($content);
    }
}