<?php

class Tasks extends Index
{

    function __construct()
    {
        // getModel function is gotten from index.php
        $this->getHelper('my');
        $this->getModel('Universal_model');

        if (!empty($_COOKIE['some_notification'])) {
            $this->some_notification = unserialize($_COOKIE['some_notification']);
            setcookie('some_notification', '', FALSE, '/');
        }

        if (isset($_SESSION) && is_exists('adminTodo', $_SESSION)) {
            $this->loggedIn = 1;
        }
    }

    function view_tasks($page = 1)
    {
        if ($page < 1) $page = 1;
        $where = array('is_deleted' => 0);
        $order_by = 'name ';
        $this->uriGetInfo = $_GET;

        if (isset($_GET['status']) && (!empty($_GET['status']) || $_GET['status'] === '0')) {
            $where['is_completed'] = $_GET['status'];
        }

        if (is_exists('field', $_GET)) {
            $order_by = $_GET['field'];
        }

        $order_by .= ' ASC';
        if (is_exists('DESC', $_GET)) {
            $order_by = str_replace('ASC', 'DESC', $order_by);
        }

        $taskTotalCount = $this->Universal_model->select('tasks', 'COUNT(id) as task_count', $where, $order_by);
        $taskTotalCount = $taskTotalCount[0]['task_count'];

        if (!empty($taskTotalCount)) {
            $perPageLimit = 3;

            $max_page = ceil($taskTotalCount / $perPageLimit);

            if ($max_page < $page) $page = $max_page;

            $limit = (($page - 1) * $perPageLimit) . ',' . $perPageLimit;

            $this->tasks = $this->Universal_model->select('tasks', '*', $where, $order_by, '', $limit);

            $config = array(
                'url' => base_url . 'tasks/view_tasks/',
                'max_page' => $max_page,
                'per_page' => $perPageLimit,
                'num_links' => 2,
                'cur_page' => $page,
                'tag_classes' => 'page-link',
                'get' => !empty($_GET) ? $_GET : array(),
            );

            // getting the html codes of pagination
            $config = array_merge($config, taskPagination());

            $this->pagination = createPagination($config);
        }
        $this->page = $page;

        $this->getLayouts('tasks/view-tasks');
    }

    function add_task()
    {
        $this->getLayouts('tasks/addTask');
    }

    function addTaskRun()
    {
        $post = $this->Universal_model->getAllowedFields($_POST, 'tasks', 'XSS');

        if (!is_exists('name', $post) || !is_exists('email', $post) || !is_exists('text', $post)) {
            $this->buildSomeNotification('error', 'Новое задание !', 'Вы заполнили не все поля.');
        } else if (!empty($post['email']) && !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->buildSomeNotification('error', 'Эл. адрес !', 'Ваш эл. адрес недействительный.');
        } else {
            $this->Universal_model->insert('tasks', $post);
            $this->buildSomeNotification('success', 'Новое задание !', 'Ваша новая задача успешно добавлена');
            header('Location: ' . base_url . 'tasks/view_tasks');
            exit;
        }
        header('Location: ' . base_url . 'tasks/add_task');
    }

    function edit_task($id)
    {
        if (!isset($this->loggedIn)) {
            $this->buildSomeNotification('error', 'Авторизация !', 'Вы должны быть авторизованы, прежде чем изменять задачи.');
            header('Location: ' . base_url . 'tasks/view_tasks');
            exit;
        }

        $this->task = $this->Universal_model->select('tasks', '*', array('id' => $id, 'is_deleted' => 0));
        if (empty($this->task)) {
            header('Location: ' . base_url . 'tasks/view_tasks');
            exit;
        }
        $this->task = $this->task[0];
        $this->getLayouts('tasks/editTask');
    }

    function editTaskRun()
    {
        if (!isset($this->loggedIn)) {
            $this->buildSomeNotification('error', 'Авторизация !', 'Вы должны быть авторизованы, прежде чем изменять задачи.');
            header('Location: ' . base_url . 'tasks/view_tasks');
            exit;
        }

        $post = $this->Universal_model->getAllowedFields($_POST, 'tasks', 'XSS');

        if (!is_exists('text', $post)) {
            $this->buildSomeNotification('error', 'Изменения задания !', 'Текстовое поле не может быть пустым');
        } else {
            $prevFields = $this->Universal_model->select('tasks', '*', array('id' => $post['id']))[0];

            if (empty($prevFields['is_admin_changed']) && trim($prevFields['text']) != trim($post['text'])) {
                $post['is_admin_changed'] = 1;
            }

            $this->Universal_model->update('tasks', $post, 'id = ' . $post['id']);
            $this->buildSomeNotification('success', 'Изменения задания !', 'Задания успешно изменена');
            header('Location: ' . base_url . 'tasks/view_tasks');
            exit;
        }
        header('Location: ' . base_url . 'tasks/edit_task/' . $post['id']);
    }

    function delete_task($id)
    {
        if (!isset($this->loggedIn)) {
            $this->buildSomeNotification('error', 'Авторизация !', 'Вы должны быть авторизованы, прежде чем изменять задачи.');
        } else {
            $this->Universal_model->update('tasks', array('is_deleted' => 1), "id = $id");
        }

        header('Location: ' . base_url . 'tasks/view_tasks');
    }

    function adminLogin()
    {
        if (!empty($_SESSION['adminTodo'])) {
            $this->buildSomeNotification('error', 'Авторизация !', 'Вы уже авторизованы');
            header('Location: ' . base_url . 'tasks/view_tasks');
            exit;
        }

        if (empty($_GET['login']) || empty($_GET['password'])) {
            $this->buildSomeNotification('error', 'Авторизация !', 'Вы не заполнили входные данные');
        } else {
            $loginedUser = $this->Universal_model->select('users', '*', array('login' => $_GET['login'], 'password' => md5($_GET['password'])));
            if (empty($loginedUser)) {
                $this->buildSomeNotification('error', 'Авторизация !', 'Неверный логин или пароль');
            } else {
                $_SESSION['adminTodo'] = $loginedUser[0]['id'];
                $this->buildSomeNotification('success', 'Авторизация !', 'Авторизация прошла успешна');
            }
        }
        header('Location: ' . base_url . 'tasks/view_tasks');
    }

    function adminLogOut()
    {
        unset($_SESSION['adminTodo']);
        header('Location: ' . base_url . 'tasks/view_tasks');
    }
}
