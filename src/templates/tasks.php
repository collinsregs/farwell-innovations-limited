<?php
// templates/tasks.php

include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();
include '../includes/header.php';
include '../includes/functions.php';



$user_id = $_SESSION['user_id'];

// Fetch tasks
$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>

<div class="flex-center">
    <div class="main-container tasks">
        <h2>Your Tasks</h2>
        <?php include '../includes/add_task.php'; ?>

        <table class="task-table">
            <thead>
                <tr>
                    <th class="td1"></th>
                    <th>Title</th>

                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tasks) != 0): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td class="td1">
                                <form action="update_task_status_checkbox.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                    <input type="checkbox" name="status" value="completed" onclick="this.form.submit()" <?php echo $task['status'] == 'completed' ? 'checked' : ''; ?>>
                                </form>
                            </td>
                            <td>
                                <div class="cell td-title"><?php echo htmlspecialchars($task['title']); ?></div>
                            </td>
                            <td>
                                <div class="cell "><?php echo htmlspecialchars($task['due_date']); ?></div>
                            </td>
                            <td>
                                <div class="dropdown">


                                    <?php
                                    switch ($task['priority']) {
                                        case 'high':
                                            echo '<div class="cell ">
                                                <button class="dropbtn" onclick="toggleDropdown(event)">
                                                <div class="tag high">
                                <span class="tag-icon"><span class="tag-icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M480-120q-33 0-56.5-23.5T400-200q0-33 23.5-56.5T480-280q33 0 56.5 23.5T560-200q0 33-23.5 56.5T480-120Zm-80-240v-480h160v480H400Z"/></svg></span>High</div>
                                </button></div> ';
                                            break;
                                        case 'medium':
                                            echo '<div class="cell ">
                                                <button class="dropbtn" onclick="toggleDropdown(event)">
                                                <div class="tag medium"><span class="tag-icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 280q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg></span>Medium</div>
                                                </button></div>';
                                            break;
                                        case 'low':
                                            echo '<div class="cell ">
                                                <button class="dropbtn" onclick="toggleDropdown(event)">
                                                <div class="tag low"><span class="tag-icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/></svg></span>Low</div>
                                                </button>
                                                </div>';
                                            break;
                                        default:
                                            echo htmlspecialchars($task['priority']);
                                    }
                                    ?>

                                    <div class="dropdown-content">
                                        <a href="update_task_priority.php?id=<?php echo $task['id']; ?>&priority=low">Low</a>
                                        <a
                                           href="update_task_priority.php?id=<?php echo $task['id']; ?>&priority=medium">Medium</a>
                                        <a href="update_task_priority.php?id=<?php echo $task['id']; ?>&priority=high">High</a>

                                    </div>

                                </div>

                            </td>
                            <td>
                                <div class="dropdown">
                                    <?php
                                    switch ($task['status']) {
                                        case 'completed':
                                            echo '<div class="cell ">
                                            <button class="dropbtn" onclick="toggleDropdown(event)"><div class="tag completed"><span class="tag-icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M400-304 240-464l56-56 104 104 264-264 56 56-320 320Z"/></svg></span>Completed</div></button></div>';
                                            break;
                                        case 'in progress':
                                            echo '<div class="cell ">
                                            <button class="dropbtn" onclick="toggleDropdown(event)"><div class="tag progress"><span class="tag-icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-64-24-123t-69-104L480-480v-320q-134 0-227 93t-93 227q0 134 93 227t227 93Z"/></svg></span>In Progress</div></button></div>';
                                            break;
                                        case 'pending':
                                            echo '<div class="cell ">
                                            <button class="dropbtn" onclick="toggleDropdown(event)"><div class="tag pending"><span class="tag-icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M680-80q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80Zm67-105 28-28-75-75v-112h-40v128l87 87Zm-547 65q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v250q-18-13-38-22t-42-16v-212h-80v120H280v-120h-80v560h212q7 22 16 42t22 38H200Zm280-640q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z"/></svg></span>Pending</div></button></div>';
                                            break;
                                        default:
                                            echo htmlspecialchars($task['status']);
                                    }
                                    ?>
                                    <div class="dropdown-content">
                                        <a
                                           href="update_task_status.php?id=<?php echo $task['id']; ?>&status=completed">Completed</a>
                                        <a href="update_task_status.php?id=<?php echo $task['id']; ?>&status=in%20progress">In
                                            Progress</a>
                                        <a
                                           href="update_task_status.php?id=<?php echo $task['id']; ?>&status=pending">Pending</a>

                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="cell action">
                                    <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="edit"><svg
                                             xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                             width="24px">
                                            <path
                                                  d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                        </svg></a>
                                    <div class="divide_vert">

                                    </div>
                                    <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="delete"
                                       onclick="return confirm('Are you sure you want to delete this task?');"><svg
                                             xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                             width="24px">
                                            <path
                                                  d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg></a>
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-tasks">No tasks found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



<?php include '../includes/footer.php'; ?>