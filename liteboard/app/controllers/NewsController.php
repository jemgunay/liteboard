<?php

class NewsController extends Controller {

	private $news, $alert, $categories, $auth;

	public function __construct() {
		parent::__construct();
        $this->auth = new Authenticate();
		$this->news = new News();
        $this->alert = new Alert();
        $this->categories = new AlertCategory();
	}

	public function news() {
        $this->auth->check_session_exists(true);

        // create news post
        if ($this->f3->get('PARAMS.action') == "create") {
            $this->auth->check_admin("/news");
            $this->news->create();
            return;
        }

        // edit news post
        else if ($this->f3->get('PARAMS.action') == "edit") {
            $this->auth->check_admin("/news");
            $this->news->edit();
            return;
        }

        // delete news post
        else if ($this->f3->get('PARAMS.action') == "delete") {
            $this->auth->check_admin("/news");
            $this->news->delete();
            return;
        }

        $this->f3->set('news_posts', $this->news->get_all());
        $this->f3->set('categories', $this->categories->get_all());
        $this->f3->set('alerts', $this->alert->get_all());

        $this->render_target = 'main.htm';
        $this->f3->set('view','news/display.htm');
	}




}