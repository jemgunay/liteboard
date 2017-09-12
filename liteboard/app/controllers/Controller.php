<?php

class Controller {

    /** @var Base */
	protected $f3, $render_target;
	protected $UI;

    public function __construct() {
        $this->f3 = Base::instance();
    }

    public function beforeroute() {
        // overall root html file for rendering
        $this->render_target = 'snippets/plain_text.htm';
        // viewport is the window surrounded by the main UI frame & menus (set false for login screen)
        $this->f3->set('use_viewport', true);
        // for responses within normal body html structure
        $this->f3->set('view','snippets/plain_text.htm');
        // variable data entered into plain text or alert responses
        $this->f3->set('response_message', 'success');
	}

    public function afterroute() {
        // initialise UI if viewport is in use (breadcrumbs & nav)
        if ($this->f3->get('use_viewport')) {
            $this->UI = new UI();
        }

        // requested operation failed
        if ($this->f3->exists('error')) {
            $this->render_target = 'snippets/alert.htm';
            $this->f3->set('response_message', $this->f3->get('error'));
        }

        // render
        echo Template::instance()->render($this->render_target, 'text/html');
	}

}
