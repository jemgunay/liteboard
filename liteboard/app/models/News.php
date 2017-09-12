<?php

class News extends Database
{
	const TABLE_NAME = 'news_post';
    private $f3;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
    }

    // get all records
    public function get_all() {
        $this->load(null, array('order' => 'date_created DESC'));
        return $this->query;
    }

    // create
    public function create() {
        $required_fields = array(array('title', 'description', 'URL'), array('Title', 'Description', 'URL'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;
        // ensure unwanted post fields are not included in copy to mapper
        Misc::copyFrom_filter($this, $required_fields[0]);

        // validate url
        if (substr($this->get('URL'), 0, 4) != "http")
            $this->set('URL', "http://" . $this->get('URL'));

        // set individual record values
        $this->set('date_created', date('Y-m-d H:i:s'));

        $this->insert();
    }

    // update
    public function edit() {
        $required_fields = array(array('news_id', 'title', 'description', 'URL'), array('News ID', 'Title', 'Description', 'URL'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        // populate mapper to check if news post exists, else silently deny access
        $this->load(array('news_id=?', $this->f3->get('POST.news_id')));
        if (!$this->loaded()) return;

        // ensure unwanted post fields are not included in copy to mapper
        Misc::copyFrom_filter($this, $required_fields[0]);

        // validate url
        if (substr($this->get('URL'), 0, 4) != "http")
            $this->set('URL', "http://" . $this->get('URL'));

        $this->update();
    }

    // delete
    public function delete() {
        $required_fields = array(array('news_id'), array('News ID'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        $this->load(array('news_id=?', $this->f3->get('POST.news_id')));
        $this->erase();
    }
}
