<?php
// backend/tasks.php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $due_date = $_POST['due_date'];

            $sql = "INSERT INTO tasks (user_id, title, description, category, due_date) VALUES (:user_id, :title, :description, :category, :due_date)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $user_id, 'title' => $title, 'description' => $description, 'category' => $category, 'due_date' => $due_date]);
        } elseif ($_POST['action'] == 'update') {
            $task_id = $_POST['id'];
            $status = $_POST['status'];

            $sql = "UPDATE tasks SET status = :status WHERE id = :id AND user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['status' => $status, 'id' => $task_id, 'user_id' => $user_id]);
        } elseif ($_POST['action'] == 'delete') {
            $task_id = $_POST['id'];

            $sql = "DELETE FROM tasks WHERE id = :id AND user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
        }
    }
}
?>
