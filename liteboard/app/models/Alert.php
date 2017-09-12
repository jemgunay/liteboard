<?php

class Alert extends Database {

    const TABLE_NAME = 'alert';
    private $f3;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
    }

    // get all records
    public function get_all() {
        return $this->db->exec('SELECT * FROM alert_category_join ORDER BY date_created DESC');
    }

    // create
    public function create() {
        $required_fields = array(array('description', 'category_id'), array('Description', 'Category'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;
        // ensure unwanted post fields are not included in copy to mapper
        Misc::copyFrom_filter($this, $required_fields[0]);

        // set individual record values
        $this->set('date_created', date('Y-m-d H:i:s'));

        $this->insert();
    }

    // edit
    public function edit() {
        $required_fields = array(array('alert_id', 'description', 'category_id'), array('Alert ID', 'Description', 'Category'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        // populate mapper to check if alert exists, else silently deny access
        $this->load(array('alert_id=?', $this->f3->get('POST.alert_id')));
        if (!$this->loaded()) return;

        // ensure unwanted post fields are not included in copy to mapper
        Misc::copyFrom_filter($this, $required_fields[0]);

        $this->update();
    }

    // delete
    public function delete() {
        $required_fields = array(array('alert_id'), array('Alert ID'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        $this->load(array('alert_id=?', $this->f3->get('POST.alert_id')));
        $this->erase();
    }
}
