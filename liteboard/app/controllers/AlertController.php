<?php

class AlertController extends Controller {

	private $alert, $category, $auth;

	public function __construct() {
		parent::__construct();
        $this->auth = new Authenticate();
        $this->alert = new Alert();
        $this->category = new AlertCategory();
	}

	public function alert() {
        $this->auth->check_admin("/news");

        // alert related requests
        if (Misc::get_param(0) == "alert") {

            // create alert
            if ($this->f3->get('PARAMS.action') == "create")
                $this->alert->create();

            // edit alert
            else if ($this->f3->get('PARAMS.action') == "edit")
                $this->alert->edit();

            // delete alert
            else if ($this->f3->get('PARAMS.action') == "delete")
                $this->alert->delete();

        }

        // category related requests
        else if (Misc::get_param(0) == "category") {

            // create alert
            if ($this->f3->get('PARAMS.action') == "create")
                $this->category->create();

            // edit alert
            else if ($this->f3->get('PARAMS.action') == "edit")
                $this->category->edit();

            // delete alert
            else if ($this->f3->get('PARAMS.action') == "delete")
                $this->category->delete();

        }

    }

}