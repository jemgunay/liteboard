<?php

class Editor extends Database {

    const TABLE_NAME = 'editor';
    private $f3;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
    }

    // check if editor exists
    public function check_exists($editor_id) {
        $this->load(array('editor_id=?', $editor_id));
        return $this->loaded();
    }

    // fetch single editor
    public function fetch($editor_id) {
        return $this->db->exec('SELECT * FROM content_editor_join WHERE editor_id = ?', $editor_id)[0];
    }

    // create
    public function create() {
        // default text
        $this->text = "<h3>Description Title</h3><p>Write your own description content here.</p>";
        $this->date_created = date('Y-m-d H:i:s');
        $this->insert();
    }

    // edit
    public function edit() {
        // check if required POST values exist
        $required_fields = array(array('target_id', 'text'), array('Target ID', 'Text'));
        if (!Misc::check_required_fields($required_fields)) return;

        // check target exists and load
        if (!$this->check_exists($this->f3->get('POST.target_id'))) {
            $this->f3->set('error', 'Description does not exist.');
            return;
        }

        // update text
        $this->text = $this->f3->get('POST.text');
        $this->update();
    }

    // delete
    public function delete($target_id) {
        // check target exists
        if (!$this->check_exists($target_id)) {
            $this->f3->set('error', 'Description does not exist.');
            return false;
        }

        $this->load(array('editor_id=?', $target_id));
        $this->erase();
        return true;
    }

}