<?php

class Content extends Database {

    /* rearrange & delete = content_id      edit = target_id     create = parent_folder_id */
    const TABLE_NAME = 'content';
    private $f3, $content_types;
    public $editor, $file, $folder;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->f3 = Base::instance();

        $this->content_types = array('folder'=>1, 'editor'=>2, 'file'=>3);
        $this->folder = new Folder();
        $this->editor = new Editor();
        $this->file = new File();
    }

    // determine request type
    public function process_request($action, $content_type, $parent_folder) {
        if ($action == 'create') {
            if ($content_type == 'folder') {
                if ($this->folder->create())
                    $this->create_content_record($parent_folder, 'folder', $this->folder->_id, false);
            }
            else if ($content_type == 'editor') {
                $this->editor->create();
                $this->create_content_record($parent_folder, 'editor', $this->editor->_id, false);
            }
            else if ($content_type == 'file') {
                if ($this->file->create())
                    $this->create_content_record($parent_folder, 'file', $this->file->_id, false);
            }
        }
        else if ($action == 'edit') {
            if ($content_type == 'folder') {
                $this->folder->edit();
            }
            else if ($content_type == 'editor') {
                $this->editor->edit();
            }
            else if ($content_type == 'file') {
                $this->file->edit();
            }
        }
        else if ($action == 'delete') {
            // check if required POST values exist
            $required_fields = array(array('content_id'), array('Content ID'));
            if (!Misc::check_required_fields($required_fields)) return;
            // fetch content data (such as target_id)
            $content_id = $this->f3->get('POST.content_id');
            $this->load(array('content_id=?', $content_id));

            if ($content_type == 'folder') {
                if ($this->folder->delete($this->target_id))
                    $this->delete_content_record($content_id);
            }
            else if ($content_type == 'editor') {
                if ($this->editor->delete($this->target_id))
                    $this->delete_content_record($content_id);
            }
            else if ($content_type == 'file') {
                if ($this->file->delete($this->target_id))
                    $this->delete_content_record($content_id);
            }
        }
    }

    // create content record
    public function create_content_record($parent_folder, $target_type, $target_id, $is_divider) {
        // determine next arrangement based on the parent folder
        $this->load(array('parent_folder_id=?', $parent_folder), array('order'=>'arrangement DESC', 'limit'=>1));
        $next_arrangement = $this->cast()['arrangement'] + 1;
        $this->reset();
        // create content record to represent new editor
        $this->parent_folder_id = $parent_folder;
        $this->arrangement = $next_arrangement;
        $this->target_type = $this->content_types[$target_type];
        $this->target_id = $target_id;
        $this->is_divider = (int)$is_divider;
        $this->insert();
    }

    // delete content record
    public function delete_content_record($content_id) {
        // shift arrangement to highest before deletion
        while (true) {
            if (!$this->perform_rearrange_shift($content_id, 'down'))
                break;
        }
        $this->load(array('content_id=?', $content_id));
        $this->erase();
    }

    // validate input & shift arrangement of content upwards
    public function rearrange() {
        $required_fields = array(array('content_id', 'direction'), array('Content ID', 'Direction'));
        // check if required POST values exist
        if (!Misc::check_required_fields($required_fields)) return;

        $this->perform_rearrange_shift($this->f3->get('POST.content_id'), $this->f3->get('POST.direction'));
    }

    // perform rearrange shift (returns true if content could be rearranged, returns false if already reached upper/lower arrangement bound)
    public function perform_rearrange_shift($content_id, $direction) {
        // derive parent_folder_id from content_id
        $this->load(array('content_id=?', $content_id));

        if ($direction == 'up') {
            // check if content exists with a lower arrangement than target
            if ($this->arrangement > 1) {
                // shift content which is lower than target higher
                $this->load(array('parent_folder_id=? AND arrangement=?', $this->parent_folder_id, $this->arrangement - 1));
                $this->arrangement = $this->arrangement + 1;
                $this->update();
                // shift target lower
                $this->load(array('content_id=?', $content_id));
                $this->arrangement = $this->arrangement - 1;
                $this->update();
                return true;
            }
        }
        else {
            // check if content exists with a higher arrangement than the target
            $this->load(array('parent_folder_id=? AND arrangement=?', $this->parent_folder_id, $this->arrangement + 1));
            if ($this->loaded()) {
                // shift content which is higher than target lower
                $this->arrangement = $this->arrangement - 1;
                $this->update();
                // shift target higher
                $this->load(array('content_id=?', $content_id));
                $this->arrangement = $this->arrangement + 1;
                $this->update();
                return true;
            }
        }
        return false;
    }

    // fetch all contents for the parent folder
    public function fetch_folder_contents($parent_folder_id) {
        // get all child content
        $content = $this->find(array('parent_folder_id=?', $parent_folder_id), array('order' => 'arrangement'));
        $content_data = array();

        // iterate over each content object
        foreach ($content as $item) {
            // folder content type
            if ($item->target_type == $this->content_types['folder'])
                $result = $this->folder->fetch($item->target_id);
            // editor content type
            if ($item->target_type == $this->content_types['editor'])
                $result = $this->editor->fetch($item->target_id);
            // file content type
            if ($item->target_type == $this->content_types['file'])
                $result = $this->file->fetch($item->target_id);

            // add to array for displaying
            $result['type'] = array_search($item->target_type, $this->content_types);
            array_push($content_data, $result);

        }

        // set content to be displayed
        $this->f3->set('content_items', $content_data);
    }

}