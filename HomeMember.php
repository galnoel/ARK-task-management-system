<?php
require 'config.php';
require 'get_user_task.php';
// include 'functions.php';
// $id = 
// $destination = read($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-do List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .header button:hover {
            background-color: #0056b3;
        }
        .header a {
            text-decoration: none;
            color: black;
            margin-left: 20px;
        }
        .container {
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .tasks-container {
            width: 80%;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .completed {
            background-color: #e0ffe0;
        }
        .pending {
            background-color: #ffe0e0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>To-do List</h1>
        <div>
            <button onclick="window.location.href='CreateTask.php'">New Task</button>
            <a href="logout.php">Log out</a>
        </div>
    </div>

    <div class="container">
        <div class="tasks-container" id="tasks-container">
            <table>
                <tr>
                    <th>Progress</th>
                    <th>Task</th>
                    <th>Due Date</th>
                    <th>Completed at</th>
                    <th>Created At</th>

                </tr>
                <?php foreach($tasks as $task): ?>
                <tr class="<?php echo $task['is_complete'] ? 'completed' : 'pending'; ?>">
                    <td>
                        <form action="update_status.php" method="post">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <input type="checkbox" name="is_complete" value="1" <?php echo $task['is_complete'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                        </form>
                    </td>
                    <td><?php echo $task['name']; ?></td>
                    <td><?php echo $task['deadline']; ?></td>
                    <td><?php echo $task['completed_at']; ?></td>
                    <td><?php echo $task['created_at']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
