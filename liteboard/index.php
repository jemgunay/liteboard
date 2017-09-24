<?php
define('DIR_BASE', dirname( __FILE__ ).'/');

$f3 = require('lib/base.php');
$f3->config('config/config.ini');

// login & logout routes
$f3->route('GET|POST /login', 'UserController->login');
$f3->route('GET /logout', 'UserController->logout');

// admin routes
$f3->route(
    array(
        'GET|POST /admin',
        'GET|POST /admin/@action/@target'
    ),
    'UserController->admin'
);

// alerts routes
$f3->route(array(
    'POST /alert/@action',
    'POST /category/@action'
),
    'AlertController->alert'
);

// news routes
$f3->route(array(
    'GET /',
    'GET /news',
    'POST /news/@action',
),
    'NewsController->news'
);

// calendar routes
$f3->route(
    array(
        'GET /calendar',
        'GET|POST /calendar/@action'
    ),
    'CalendarController->calendar'
);

// content routes
$f3->route(
    array(
        'GET /content/@folder',
        'POST /content/@folder/@action',
        'GET /download/@file'
    ),
    'ContentController->content'
);

// error page
if ($f3->get('error_page_enabled')) {
    $f3->set('ONERROR', function($f3) {
        $f3->set('use_viewport', false);
        $f3->set('view', 'error.htm');
        $f3->set('render_target', 'main.htm');
        echo Template::instance()->render($f3->render_target, 'text/html');
    });
}

// extend session to prevent user-agent change 403 error
new Session(function(Session $session, $id) {
    return true;
});

// template & static functions
new Misc();

// start listening to requests
$f3->run();