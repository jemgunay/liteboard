<?php

class AlertCategory extends Database
{
    const TABLE_NAME = 'alert_category';
    private $f3;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
    }

    // get all records
    public function get_all() {
        $this->load(null, array('order' => 'category_id'));
        return $this->query;
    }

    // create
    public function create() {
        $required_fields = array(array('name', 'colour'), array('Label', 'Colour'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        // validate input
        if (strlen($this->f3->get('POST.name')) < 3) {
            $this->f3->set('error', 'The category label must be 3 or more characters long.');
            return;
        }
        if (!Misc::validate_hex($this->f3->get('POST.colour'))) {
            $this->f3->set('error', 'The category colour must be a valid hexadecimal colour.');
            return;
        }

        // ensure unwanted post fields are not included in copy to mapper
        Misc::copyFrom_filter($this, $required_fields[0]);

        $this->insert();
    }

    // edit
    public function edit() {
        $required_fields = array(array('category_id', 'name', 'colour'), array('Category ID', 'Label', 'Colour'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        // populate mapper to check if category exists, else silently deny access
        $this->load(array('category_id=?', $this->f3->get('POST.category_id')));
        if (!$this->loaded()) return;
        $current_name = $this->get('name');

        // validate input
        if (strlen($this->f3->get('POST.name')) < 3) {
            $this->f3->set('error', 'One of the above categories does not have a label of 3 or more characters long.');
            return;
        }
        if (!Misc::validate_hex($this->f3->get('POST.colour'))) {
            $this->f3->set('error', 'The category colour of the ' . $this->f3->get('POST.name') . ' category must be a valid hexadecimal colour.');
            return;
        }

        // ensure unwanted post fields are not included in copy to mapper
        Misc::copyFrom_filter($this, $required_fields[0]);

        // prevent edit of default category name
        if ((int)$this->f3->get('POST.category_id') == 1)
            $this->set('name', $current_name);

        $this->update();
    }

    // delete
    public function delete() {
        $required_fields = array(array('category_id'), array('Category ID'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        // prevent deletion of default category
        if ((int)$this->f3->get('POST.category_id') == 1) {
            $this->f3->set('error', 'The default category cannot be deleted.');
            return;
        }
        $this->load(array('category_id=?', $this->f3->get('POST.category_id')));
        $this->erase();

        // edit the category_id of alerts from the deleted category_id to the default
        $this->db->exec('UPDATE alert SET category_id = 1 WHERE category_id = :category_id',array(':category_id' => $this->f3->get('POST.category_id')));
    }
}
