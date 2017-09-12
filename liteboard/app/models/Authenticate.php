<?php

class Authenticate extends Database
{
    const TABLE_NAME = 'account';
    private $f3, $crypt;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
        $this->crypt = \Bcrypt::instance();
    }

    // check if login credentials are valid
    public function authenticate() {
        $p = Base::instance()->get('POST');
        $account = $p['account'];
        $password = $p['password'];

        $this->load(array('name=?', $account));

        // check password against hash
        if (!is_null($this->account_id) && $this->crypt->verify($password, $this->password)) {
            // create session
            $account_match = $this->db->exec('SELECT * FROM account WHERE account_id = ?', $this->account_id)[0];
            $this->f3->set('SESSION.user', $account_match);

            // increment login count
            $this->login_count = $this->login_count + 1;
            $this->update();
        }
    }

    // check if any session exists (standard or admin)
    public function check_session_exists($login_redirect=false) {
        if ($this->f3->exists('SESSION.user')) {
            return true;
        }
        else {
            if ($login_redirect) {
                // redirect to login page
                $this->f3->reroute('/login');
            }
            return false;
        }
    }

    // check if session user id matches specified target user id
    public function check_auth_id($requested_id, $redirect_target="") {
        // no session
        if (!$this->check_session_exists(true)) {
            return false;
        }

        // session exists but id does not match AND is not admin
        if (($this->f3->get('SESSION.user.user_id') != $requested_id) && ($this->f3->get('SESSION.user.is_admin') == 0)) {
            // no id match
            if ($redirect_target != "") {
                $this->f3->reroute($redirect_target);
            }
            return false;
        }
        // id match
        return true;
    }

    // check if current session user has admin privileges
    public function check_admin($redirect_target="") {
        if ($this->check_session_exists(true) && $this->f3->get('SESSION.user.is_admin')) {
            // is admin
            return true;
        }
        // not admin
        if ($redirect_target != "") {
            $this->f3->reroute($redirect_target);
        }
        return false;
    }

    // redirect to login page
    public function login_redirect() {
        $this->f3->set('use_viewport', false);
        $this->f3->set('view', 'user/login.htm');
    }
}
