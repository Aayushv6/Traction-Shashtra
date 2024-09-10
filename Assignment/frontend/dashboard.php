<?php
// frontend/dashboard.php
session_start();
require '../backend/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch tasks
$sql = "SELECT * FROM tasks WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styling/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head><style>
        /* Reset some default styling */
        body, h1, h2, h3, p, ul, li {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin: 20px 0;
        }

        h2 {
            color: #0056b3;
            margin-top: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        h3 {
            color: #333;
            margin-top: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        ul#taskList {
            max-width: 600px;
            margin: 20px auto;
            padding: 0;
            list-style-type: none;
        }

        ul#taskList li {
            background: #fff;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        ul#taskList li button {
            background-color: #dc3545;
            padding: 5px 10px;
            font-size: 14px;
            margin-left: 5px;
        }

        ul#taskList li button:hover {
            background-color: #c82333;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
            margin: 20px;
            display: block;
            text-align: center;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
<body>
    <h1>Dashboard</h1>
    <a href="../backend/logout.php">Logout</a>

    <h2>Add Task</h2>
    <form id="taskForm">
        <input type="hidden" name="action" value="create">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>
        <label for="category">Category:</label>
        <input type="text" name="category" id="category">
        <label for="due_date">Due Date:</label>
        <input type="date" name="due_date" id="due_date">
        <button type="submit">Add Task</button>
    </form>

    <h3>Task List</h3>
    <ul id="taskList">
        <?php foreach ($tasks as $task): ?>
            <li id="task-<?php echo $task['id']; ?>">
                <span><?php echo htmlspecialchars($task['title']); ?> - <?php echo htmlspecialchars($task['status']); ?></span>
                <button onclick="editTask(<?php echo $task['id']; ?>)">Edit</button>
                <button onclick="deleteTask(<?php echo $task['id']; ?>)">Delete</button>
            </li>
        <?php endforeach; ?>
    </ul>

    <script>
        $(document).ready(function() {
            $('#taskForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../backend/tasks.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        location.reload();
                    }
                });
            });
        });

        function deleteTask(taskId) {
            $.ajax({
                url: '../backend/tasks.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    id: taskId
                },
                success: function(response) {
                    $('#task-' + taskId).remove();
                }
            });
        }

        function editTask(taskId) {
            // Implement task editing functionality here
        }
    </script>
</body>
</html>
