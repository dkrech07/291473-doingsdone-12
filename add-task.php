<?php
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
$user_id = $_SESSION['user']['id'];

$project_id = filter_input(INPUT_GET, 'project-id', FILTER_VALIDATE_INT);
$projects = select_query($con, "SELECT p.* FROM projects p INNER JOIN users u ON u.id = p.user_id WHERE u.id = '$user_id' ORDER BY p.id DESC");
$tasks = select_query($con, "SELECT t.*, p.* FROM tasks t INNER JOIN users u ON u.id = t.user_id INNER JOIN projects p ON p.id = t.project_id WHERE u.id = '$user_id' ORDER BY t.id DESC");
$project_tasks = get_project_tasks ($project_id, $tasks);

$errors = check_new_task_validity($con, $user_id);

$page_content = include_template('add-task.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'project_tasks' => $project_tasks,
    'project_id' => $project_id,
    'errors' => $errors,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'doingsdone: добавление задачи',
]);

echo($layout_content);