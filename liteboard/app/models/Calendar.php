<?php

class Calendar extends Database {
	
	const TABLE_NAME = 'event';
    private $f3;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
    }

    // get all records
    public function get_all() {
        $this->load();
        return $this->cast();
    }

    // create
    public function create() {
        $required_fields = array(array('title', 'date_start', 'date_end', 'colour'), array('Title', 'Start Date', 'End Date', 'Colour'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;
        // ensure unwanted post fields are not included in copy to mapper
        Misc::copyFrom_filter($this, $required_fields[0]);

        $this->insert();
    }

    // update
    public function edit() {
        $required_fields = array(array('event_id', 'title', 'date_start', 'colour'), array('Event ID', 'Title', 'Start Date', 'Colour'));
        $included_fields = array('event_id', 'title', 'date_start', 'date_end', 'colour');
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        // populate mapper to check if event exists, else silently deny access
        $this->load(array('event_id=?', $this->f3->get('POST.event_id')));
        if (!$this->loaded()) return;

        // ensure unwanted post fields are not included in copy to mapper
        Misc::copyFrom_filter($this, $included_fields);

        $this->update();
    }

    // delete
    public function delete() {
        $required_fields = array(array('event_id'), array('Event ID'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        $this->load(array('event_id=?', $this->f3->get('POST.event_id')));
        $this->erase();
    }

    // return all events as json
    public function db_to_json() {
        // hydrate mapper with all events
        $events = array_map(array($this, 'cast'), $this->find());

        // rename db columns to corresponding js calendar lib keys
        $rewriteKeys = array('event_id'=>'id', 'title'=>'title', 'date_start'=>'start', 'date_end'=>'end', 'colour'=>'color');
        foreach ($events as $index => $event) {
            $newArr = array();

            foreach ($event as $key => $value) {
                $newArr[$rewriteKeys[$key]] = $value;
            }

            $events[$index] = $newArr;
        }

        echo json_encode($events);
    }

    // prepare json events for DB
    public function json_to_db() {

    }
}
