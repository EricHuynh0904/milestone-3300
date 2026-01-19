<?php
include 'db.php';
include 'header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Todo List Database – Project Overview</h2>
    </div>
    <p><strong>Course:</strong> CPSC 3300 – Database Systems</p>
    <p><strong>Developers:</strong> Khoi Huynh, Abdul-Rahman, Conor, Josiah</p>
    <p class="muted">
        This website lets you browse the relations between all the tables in the milestone project, 
        run the SQL queries from Milestone 3 and 4, and perform the ad-hoc SQL commands.
    </p>
</div>



<div class="card">
    <div class="card-header">
        <h2>Relations</h2>
        <span class="card-subtitle">Base tables for the Todo List schema</span>
    </div>
    <p class="muted">
        Click any table below to see all rows currently stored in that relation.
    </p>
    <ul style="list-style-type: disc; margin-left: 20px;">
        <li><a href="relations.php?table=Users">Users</a></li>
        <li><a href="relations.php?table=Category">Category</a></li>
        <li><a href="relations.php?table=Todo_List">Todo List</a></li>
        <li><a href="relations.php?table=Tasks">Tasks</a></li>
        <li><a href="relations.php?table=Notes">Notes</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h2>Saved Queries</h2>
    </div>

    <ul style="list-style-type: disc; margin-left: 20px;">
        <li><a href="queries.php?q=1">Query 1</strong></a> – Alice’s tasks with list name, status, due date, and priority.</li>
        <li><a href="queries.php?q=2">Query 2</strong></a> – Total task count per user.</li>
        <li><a href="queries.php?q=3">Query 3</strong></a> – Users with at least one high-priority task.</li>
        <li><a href="queries.php?q=4">Query 4</strong></a> – Lists that have at least three high-priority tasks.</li>
        <li><a href="queries.php?q=5">Query 5</strong></a> – All users and how many tasks they have (including 0).</li>
        <li><a href="queries.php?q=6">Query 6</strong></a> – All overdue tasks (per user).</li>
        <li><a href="queries.php?q=8">Query 7</strong></a> – Completion rate per user.</li>
        <li><a href="queries.php?q=9">Query 8</strong></a> – Task counts by category for Alice.</li>
    </ul>
</div>


<div class="card">
    <div class="card-header">
        <h2>Ad-hoc Query</h2>
        <span class="card-subtitle">Try any legal SQL function on the Todo DB</span>
    </div>
    <p>Use the ad-hoc console to quickly test SELECTs, filters, joins, and more.</p>
    <p><a class="btn" href="adhoc.php">Open Ad-hoc Query</a></p>
</div>

</div></body></html>
