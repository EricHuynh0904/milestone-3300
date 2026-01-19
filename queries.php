<?php
include 'db.php';
include 'header.php';
?>

<!-- CARD 1: QUERY LIST -->
<div class="card">
<h2>Predefined Queries</h2>
<p>Click one to run it</p>

<?php

$queries = [
    1 => [
        "All of Alice’s tasks with list name, status, due date, and priority.",
        "SELECT
            u.username,
            tl.list_name        AS list_name,
            t.task_id,
            t.task_name         AS task_title,
            t.complete,
            t.due_date,
            t.priority
         FROM Users u
         JOIN Todo_List tl ON u.user_id = tl.user_id
         JOIN Tasks t      ON tl.todo_list_id = t.todo_list_id
         WHERE u.username = 'alice'
         ORDER BY t.due_date"
    ],
    2 => [
        "Count how many total tasks each user has in the system.",
        "SELECT 
            u.username,
            COUNT(t.task_id) AS total_tasks
         FROM Users u
         JOIN Tasks t ON u.user_id = t.user_id
         GROUP BY u.username"
    ],
    3 => [
        "List all users who have at least one High-priority task.",
        "SELECT username
         FROM Users
         WHERE user_id IN (
             SELECT user_id
             FROM Tasks
             WHERE priority = 'High'
         )"
    ],
    4 => [
        "Lists with at least 3 HIGH-priority tasks (GROUP BY + HAVING).",
        "SELECT
            l.todo_list_id,
            l.list_name,
            u.username,
            COUNT(t.task_id) AS high_priority_tasks
         FROM Todo_List l
         JOIN Users u ON l.user_id = u.user_id
         JOIN Tasks t ON l.todo_list_id = t.todo_list_id
         WHERE t.priority = 'High'
         GROUP BY l.todo_list_id, l.list_name, u.username
         HAVING COUNT(t.task_id) >= 3"
    ],
    5 => [
        "Show all users (even if they never created a list) and how many tasks they have in total (LEFT JOIN).",
        "SELECT
            u.username,
            COUNT(t.task_id) AS total_tasks
         FROM Users u
         LEFT JOIN Todo_List l ON u.user_id = l.user_id
         LEFT JOIN Tasks t     ON l.todo_list_id = t.todo_list_id
         GROUP BY u.username
         ORDER BY total_tasks DESC"
    ],

    6 => [
        "Overdue tasks (not completed, due date before today).",
        "SELECT 
            u.username,
            t.task_name,
            t.due_date,
            tl.list_name
         FROM Tasks t
         JOIN Users u      ON t.user_id = u.user_id
         JOIN Todo_List tl ON t.todo_list_id = tl.todo_list_id
         WHERE t.complete = 0
           AND t.due_date IS NOT NULL
           AND t.due_date < CURDATE()
         ORDER BY t.due_date ASC"
    ],
 
    7 => [
        "Completion rate per user (percentage of tasks completed).",
        "SELECT
            u.username,
            COUNT(*) AS total_tasks,
            SUM(CASE WHEN t.complete = 1 THEN 1 ELSE 0 END) AS completed_tasks,
            ROUND(
                100.0 * SUM(CASE WHEN t.complete = 1 THEN 1 ELSE 0 END) / COUNT(*),
                1
            ) AS completion_rate_pct
         FROM Users u
         JOIN Tasks t ON u.user_id = t.user_id
         GROUP BY u.username
         ORDER BY completion_rate_pct DESC"
    ],
    8 => [
        "Task counts by category for Alice.",
        "SELECT 
            u.username,
            c.name        AS category,
            COUNT(*)      AS task_count
         FROM Tasks t
         JOIN Users u    ON t.user_id = u.user_id
         JOIN Category c ON t.category_id = c.category_id
         WHERE u.username = 'alice'
         GROUP BY u.username, c.name
         ORDER BY task_count DESC"
    ],
];

$selected = isset($_GET['q']) ? (int)$_GET['q'] : 0;
?>

<ol>
<?php foreach ($queries as $id => $info): ?>
    <li>
        <a href="queries.php?q=<?php echo $id; ?>">Query<?php echo $id; ?></a>:
        <?php echo htmlspecialchars($info[0]); ?>
    </li>
<?php endforeach; ?>
</ol>

</div> 

<?php if ($selected && isset($queries[$selected])): ?>
<div class="card">
<h3>Result for Query <?php echo $selected; ?></h3>

<?php
$sql = $queries[$selected][1];
try {
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    if (!$rows) {
        echo "<p>No rows returned.</p>";
    } else {

        echo "<table>";
        echo "<thead><tr>";
        foreach (array_keys($rows[0]) as $col) {
            echo "<th class=\"sortable\">" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr></thead>";
        echo "<tbody>";
        foreach ($rows as $row) {
            echo "<tr>";
            foreach ($row as $val) {
                echo "<td>" . htmlspecialchars((string)$val) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "<p><strong>Error running query:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

</div> 
<?php endif; ?>

<?php
echo "</div></body></html>";
?>
