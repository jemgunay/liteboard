<?php

class User extends Database {
	
	const TABLE_NAME = 'account';
    private $f3, $file, $crypt;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();
        $this->file = new File();
        $this->crypt = \Bcrypt::instance();
    }

    // check if account exists
    public function check_exists($account_id) {
        $this->load(array('account_id=?', $account_id));
        return $this->loaded();
    }

    // get all records
    public function all() {
        $this->load();
        return $this->query;
    }

    // change account password
    public function change_password() {
        // check target exists
        $account_id = $this->f3->get('PARAMS.target');
        if (!$this->check_exists($account_id)) {
            $this->f3->set('error', 'Account does not exist.');
            return false;
        }

        // check fields have been entered
        $old_password = $this->f3->get('POST.old_password');
        $new_password = $this->f3->get('POST.new_password');

        if ($old_password == "") {
            $this->f3->set('error', 'Current password field is empty.');
            return false;
        }
        if ($new_password == "") {
            $this->f3->set('error', 'New password field is empty.');
            return false;
        }

        // check password against hash
        if (!$this->crypt->verify($old_password, $this->password)) {
            // wrong original password
            $this->f3->set('error', 'Incorrect current password entered.');
            return false;
        }

        // password change success
        $this->password = $this->crypt->hash($new_password);
        $this->update();

        $this->f3->set('error', 'Password for ' . $this->name . ' account successfully changed!');
        return false;
    }

    // reset log in counter
    public function reset_login_counter($account_id) {
        // check target exists
        if (!$this->check_exists($account_id)) {
            $this->f3->set('error', 'Account does not exist.');
            return false;
        }

        $this->count_reset_date = date('Y-m-d H:i:s');
        $this->login_count = 0;
        $this->update();
    }
}
