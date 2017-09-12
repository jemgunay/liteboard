<?php

class CalendarController extends Controller {

	private $calendar, $auth;

	public function __construct() {
		parent::__construct();
        $this->auth = new Authenticate();
		$this->calendar = new Calendar();
	}

	public function calendar() {
        $this->auth->check_session_exists(true);

        // get events data in json form
        if ($this->f3->get('PARAMS.action') == "fetch") {
            $this->render_target = 'snippets/plain_text.htm';
            $this->f3->set('response_message', $this->calendar->db_to_json());
            return;
        }

        // create news post
        if ($this->f3->get('PARAMS.action') == "create") {
            $this->auth->check_admin("/yearplan");
            $this->calendar->create();
            return;
        }

        // edit news post
        else if ($this->f3->get('PARAMS.action') == "edit") {
            $this->auth->check_admin("/yearplan");
            $this->calendar->edit();
            return;
        }

        // delete news post
        else if ($this->f3->get('PARAMS.action') == "delete") {
            $this->auth->check_admin("/yearplan");
            $this->calendar->delete();
            return;
        }

        // get calendar
        $this->render_target = 'main.htm';
        $this->f3->set('view','calendar/display.htm');
        
	}

}