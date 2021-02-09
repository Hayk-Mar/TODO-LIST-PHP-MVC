<?php

define("base_url", 'http://localhost/todolist-task/');
define("default_controller", 'tasks');

class Index
{
    function __constract()
    {
        ini_set('display_errors', 0);
    }

    function getDatabase()
    {
        $database = mysqli_connect('localhost', 'root', '', 'todolist');
        return $database;
    }

    function getController($controller)
    {
        $controller = ucfirst($controller);
        if (file_exists('./controllers/' . ucfirst($controller) . '.php')) {
            include_once('./controllers/' . $controller . '.php');
            return new $controller;
        }
    }

    function getModel($model)
    {
        $model = ucfirst($model);
        include_once('./models/' . $model . '.php');
        $this->$model = new $model;
    }

    function getView($view, $asVariable = FALSE)
    {
        if (!empty($asVariable)) {
            ob_start();
            include('./views/' . $view . '.php');
            return ob_get_clean();
        }

        if (is_array($view)) {
            foreach ($view as $key) {
                include_once('./views/' . $key . '.php');
            }
        } else {
            include_once('./views/' . $view . '.php');
        }
    }

    function getLayouts($view)
    {
        if (empty($view)) exit;

        // getView function is gotten from index.php
        $this->getView('layouts/header');
        $this->getView($view);
        $this->getView('layouts/footer');
    }

    function getHelper($helper)
    {
        $helper .= '_helper';
        include_once('./helpers/' . $helper . '.php');
    }

    function getUriSegments()
    {
        return explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }

    function error_404()
    {
        $this->getLayouts('error_404');
    }

    protected function buildSomeNotification($type, $title, $text, $button_text = 'OK')
    {
        $notification = array(
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'button_text' => $button_text
        );

        setcookie('some_notification', serialize($notification), time() + 2592000, '/');
    }
}

$index = new Index;
session_start();

$database = $index->getDatabase();
if (!$database) {
    die("Connection failed: " . mysqli_connect_error());
}

// getting controller
$segments = $index->getUriSegments();
$classname = $segments[2];
if (empty($segments[2])) {
    $classname = default_controller;
}

$controller = $index->getController($classname);
if (empty($controller)) {
    $index->error_404();
    exit;
}

// getting controller function
$classFunction = 'index';
if (isset($segments[3]) && !empty($segments[3])) {
    $classFunction = $segments[3];
}

$funcParams = array();
if (count($segments) > 4) {
    // getting function arguments
    $funcParams = array_slice($segments, 4);
}

// replace array as function arguments.
if (method_exists($controller, $classFunction)) {
    call_user_func_array(array($controller, $classFunction), $funcParams);
    exit;
}

$index->error_404();
