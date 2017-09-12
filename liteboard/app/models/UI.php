<?php

class UI extends Database {

    const TABLE_NAME = 'content_folder_join';
    private $f3;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();

        $this->set_nav();
        $this->set_breadcrumbs();
    }

    // set side nav options
    public function set_nav() {
        // a folder qualifies as a nav option if it has no parent_folder_id
        $nav_options = $this->db->exec('SELECT * FROM ' . self::TABLE_NAME . ' WHERE parent_folder_id IS NULL ORDER BY arrangement ASC');
        $this->f3->set('nav_options', $nav_options);
    }

    // recursively collect breadcrumbs data, starting with current page
    public function set_breadcrumbs() {
        // first check if current page is a static or dynamic type
        $breadcrumbs = array();
        $first_param = Misc::get_param(0);

        // dynamic page
        if ($first_param == "content") {
            $second_param = Misc::get_param(1);
            $this->perform_breadcrumb_search($second_param, $breadcrumbs);
        }
        // static page
        else {
            // static page URL aliases
            $alt_names = array('admin'=>'Admin', 'news'=>'Alerts & News', 'calendar'=>'Year Plan', ''=>'Alerts & News');
            $alias_name = $first_param;

            if (array_key_exists($first_param, $alt_names))
                $alias_name = $alt_names[$alias_name];

            array_unshift($breadcrumbs, array('url' => $first_param, 'name' => $alias_name));
            $this->f3->set('breadcrumbs', $breadcrumbs);
        }
    }

    // recursive function
    public function perform_breadcrumb_search($leaf_id, $breadcrumbs) {
        // check if folder exists
        $this->load(array('folder_id=?', $leaf_id));
        if (!$this->loaded()) return false;

        // add current page to breadcrumbs list
        array_unshift($breadcrumbs, array('url' => 'content/' . $this->get('folder_id'), 'name' => $this->get('name'), 'id' => $this->get('folder_id')));

        if ((string)$this->get('parent_folder_id') != "") {
            return $this->perform_breadcrumb_search($this->get('parent_folder_id'), $breadcrumbs);
        }
        else {
            $this->f3->set('breadcrumbs', $breadcrumbs);
            return true;
        }
    }

}