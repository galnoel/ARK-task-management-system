<?php
require 'config.php';
require 'get_all_user.php';

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
        .user-container {
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
            <button onclick="window.location.href='CreateTaskAdmin.php'">New Task</button>
            <a href="HomeAdmin.php">Task</a>
            <a href="logout.php">Log out</a>
        </div>
    </div>

    <div class="container">
        <div class="user-container" id="user-container">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created at</th>

                </tr>
                <?php foreach($users as $user): ?>
                <tr>
                    <!-- <td>
                        <form action="update_status.php" method="post">
                            <input type="hidden" name="task_id" value="<?php echo $user['id']; ?>">
                            <input type="checkbox" name="is_complete" value="1" <?php echo $user['is_complete'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                        </form>
                    </td> -->
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
