<?php
include 'db.php';
include 'header.php';

$tables = ['Users', 'Category', 'Todo_List', 'Tasks', 'Notes'];
$currentTable = isset($_GET['table']) ? $_GET['table'] : null;

if ($currentTable === null || !in_array($currentTable, $tables)) {
    ?>
    <div class="card">
        <h2>Relations</h2>
        <p><strong>Base tables for the Todo List schema</strong></p>
        <p>Click any table below to see all rows currently stored in that relation.</p>

        <ul>
            <li><a href="relations.php?table=Users">Users</a></li>
            <li><a href="relations.php?table=Category">Category</a></li>
            <li><a href="relations.php?table=Todo_List">Todo List</a></li>
            <li><a href="relations.php?table=Tasks">Tasks</a></li>
            <li><a href="relations.php?table=Notes">Notes</a></li>
        </ul>
    </div>
    </div></body></html>
    <?php
    exit;
}
?>
<div class="card">
    <h2>Relation: <?php echo htmlspecialchars($currentTable); ?></h2>

    <?php
    try {
        $stmt = $pdo->query("SELECT * FROM $currentTable");
        $rows = $stmt->fetchAll();

        if (!$rows) {
            echo "<p>No data in this table.</p>";
        } else {
            echo "<table>";
            echo "<thead><tr>";
            foreach (array_keys($rows[0]) as $colName) {
                echo "<th class=\"sortable\">" . htmlspecialchars($colName) . "</th>";
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
        echo "<p><strong>Error reading table:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</div>

</div></body></html>
