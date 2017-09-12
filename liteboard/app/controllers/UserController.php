<?php

class UserController extends Controller {

	private $user, $auth;

	public function __construct() {
		parent::__construct();
		$this->user = new User();
        $this->auth = new Authenticate();
	}

    public function login() {
        // redirect to home if already logged in
        if ($this->auth->check_session_exists())
            $this->f3->reroute('/');

        // perform login
        if ($this->f3->exists('POST.login')) {
            // attempt to authenticate, init session on success
            $this->auth->authenticate();

            // login successful
            if ($this->auth->check_session_exists()) {
                $this->f3->set('response_message', 'success');
            }
            // login failed
            else {
                $this->render_target = 'snippets/alert.htm';
                $this->f3->set('response_message', 'Incorrect password entered.');
            }
            return;
        }

        // display login page
        $this->render_target = 'main.htm';
        $this->f3->set('accounts', $this->user->all());
        $this->auth->login_redirect();
    }

    // reset session
    public function logout() {
        $this->f3->clear('SESSION.user');
        $this->f3->reroute('/login');
    }

    // admin controls
	public function admin() {
        // redirect to home if already logged in
        $this->auth->check_admin('/');

        if ($this->f3->get('PARAMS.action') == 'reset_counter') {
            $this->user->reset_login_counter($this->f3->get('PARAMS.target'));
            $this->f3->reroute('/admin');
        }
        else if ($this->f3->get('PARAMS.action') == 'change_password') {
            $this->user->change_password();
            return;
        }

        // display main admin panel
        $this->render_target = 'main.htm';
        $this->f3->set('view','user/admin.htm');
        $this->f3->set('accounts', $this->user->all());
    }
}