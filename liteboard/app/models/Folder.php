<?php

class Folder extends Database {

    const TABLE_NAME = 'folder';
    private $f3;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
    }

    // check if folder exists
    public function check_exists($folder_id) {
        $this->load(array('folder_id=?', $folder_id));
        return $this->loaded();
    }

    // fetch single folder
    public function fetch($folder_id) {
        return $this->db->exec('SELECT * FROM content_folder_join WHERE folder_id = ?', $folder_id)[0];
    }

    // create
    public function create() {
        // check if required POST values exist
        $required_fields = array(array('name'), array('Name'));
        if (!Misc::check_required_fields($required_fields)) return false;

        $this->name = $this->f3->get('POST.name');
        $this->date_created = date('Y-m-d H:i:s');
        if ($this->f3->exists('POST.description'))
            $this->description = $this->f3->get('POST.description');

        $this->insert();
        return true;
    }

    // edit
    public function edit() {
        // check if required POST values exist
        $required_fields = array(array('target_id', 'name'), array('Target ID', 'Name'));
        if (!Misc::check_required_fields($required_fields)) return;

        // check target exists and load
        if (!$this->check_exists($this->f3->get('POST.target_id'))) {
            $this->f3->set('error', 'Folder does not exist.');
            return;
        }

        // update text
        $this->name = $this->f3->get('POST.name');
        if ($this->f3->exists('POST.description'))
            $this->description = $this->f3->get('POST.description');

        $this->update();
    }

    // recursively delete folder and its contents
    public function delete($target_id) {
        // check target exists
        if (!$this->check_exists($target_id)) {
            $this->f3->set('error', 'Folder does not exist.');
            return false;
        }

        // delete folder contents
        $content = new Content();
        $folder_contents = $content->find(array('parent_folder_id=?', $this->folder_id));

        foreach ($folder_contents as $item) {
            $this->f3->set('POST.content_id', $item->content_id);
            if ($item->target_type == 1) {
                $content->process_request('delete', 'folder', $item->parent_folder_id);
            }
            else if ($item->target_type == 2) {
                $content->process_request('delete', 'editor', $item->parent_folder_id);
            }
            else if ($item->target_type == 3) {
                $content->process_request('delete', 'file', $item->parent_folder_id);
            }
        }

        $this->erase();
        return true;
    }
}