<?php

class ContentController extends Controller {

	private $auth, $content;

	public function __construct() {
		parent::__construct();
        $this->auth = new Authenticate();
        $this->content = new Content();
	}

	// manage content operation routing
	public function content() {
        $this->auth->check_session_exists(true);

        // download file request
        if (Misc::get_param(0) == 'download' && $this->f3->exists('PARAMS.file')) {
            $this->content->file->perform_download($this->f3->get('PARAMS.file'));
            return;
        }

        // get request properties
        $parent_folder = $this->f3->get('PARAMS.folder');
        $action = $this->f3->get('PARAMS.action');

        // check folder exists
        if (!$this->content->folder->check_exists($parent_folder))
            $this->f3->error(404);

        // determine operation type
        $actions = array('create', 'edit', 'delete', 'rearrange');
        $content_types = array('folder', 'editor', 'file');

        if (in_array($action, $actions)) {
            // check if admin
            $this->auth->check_admin("/content/" . $parent_folder);

            // perform rearrange (operation is independent of content type)
            if ($action == 'rearrange') {
                $this->content->rearrange();
                return;
            }

            // determine content target type
            $target_type = $this->f3->get('POST.target_type');
            if (in_array($target_type, $content_types)) {
                // process request
                $this->content->process_request($action, $target_type, $parent_folder);
                return;
            }
        }

        // get all folder contents
        $this->content->fetch_folder_contents($parent_folder);

        $this->render_target = 'main.htm';
        $this->f3->set('view','content/display.htm');
	}

}