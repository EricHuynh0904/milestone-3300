-- CPSC 3300 Project Milestone 3 - To-Do Web Application
-- Target DBMS: SQL Server on cssql.seattleu.edu

/* 
========================================
Question 1: Create Tables (5 points)
========================================
Schema supports:
- User registration & login
- Multiple lists per user
- Tasks tied to lists
*/

-----------------------------
-- Users table
-- Stores registered accounts
-----------------------------
CREATE TABLE Users (
    user_id       INT          PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    email         VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    DATETIME     NOT NULL DEFAULT GETDATE(),
    is_active     BIT          NOT NULL DEFAULT 1
);

-----------------------------
-- TodoLists table
-- Each user can have multiple lists
-----------------------------
CREATE TABLE TodoLists (
    list_id    INT          PRIMARY KEY,
    user_id    INT          NOT NULL,
    list_name  VARCHAR(100) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT GETDATE(),
    is_default BIT          NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-----------------------------
-- Tasks table
-- Individual to-do items
-----------------------------
CREATE TABLE Tasks (
    task_id      INT          PRIMARY KEY,
    list_id      INT          NOT NULL,
    title        VARCHAR(200) NOT NULL,
    description  VARCHAR(500) NULL,
    due_date     DATE         NULL,
    priority     VARCHAR(10)  NOT NULL DEFAULT 'Medium',   -- Low, Medium, High
    status       VARCHAR(15)  NOT NULL DEFAULT 'Pending',  -- Pending, In Progress, Completed, Overdue
    created_at   DATETIME     NOT NULL DEFAULT GETDATE(),
    completed_at DATETIME     NULL,
    FOREIGN KEY (list_id) REFERENCES TodoLists(list_id)
);

/*
========================================
Question 2: Populate Tables (5 points)
At least 10 records per table
========================================
*/

-----------------------------
-- Users sample data (10)
-----------------------------
INSERT INTO Users (user_id, username, email, password_hash, is_active) VALUES
(1,  'alice',   'alice@example.com',   'hash_alice',   1),
(2,  'bob',     'bob@example.com',     'hash_bob',     1),
(3,  'carol',   'carol@example.com',   'hash_carol',   1),
(4,  'david',   'david@example.com',   'hash_david',   1),
(5,  'eric',    'eric@example.com',    'hash_eric',    1),
(6,  'fatima',  'fatima@example.com',  'hash_fatima',  1),
(7,  'grace',   'grace@example.com',   'hash_grace',   1),
(8,  'helen',   'helen@example.com',   'hash_helen',   1),
(9,  'ivan',    'ivan@example.com',    'hash_ivan',    1),
(10, 'judy',    'judy@example.com',    'hash_judy',    0); -- inactive user

-----------------------------
-- TodoLists sample data (10)
-----------------------------
INSERT INTO TodoLists (list_id, user_id, list_name, is_default) VALUES
(1, 1, 'Alice - Personal', 1),
(2, 1, 'Alice - School',   0),
(3, 2, 'Bob - Work',       1),
(4, 2, 'Bob - Groceries',  0),
(5, 3, 'Carol - Personal', 1),
(6, 4, 'David - Personal', 1),
(7, 5, 'Eric - Startup',   1),
(8, 6, 'Fatima - School',  1),
(9, 7, 'Grace - Personal', 1),
(10,8,'Helen - Fitness',   1);

-----------------------------
-- Tasks sample data (16 ≥ 10)
-----------------------------
INSERT INTO Tasks (task_id, list_id, title, description, due_date, priority, status, completed_at) VALUES
(1,  1, 'Pay rent',           'Transfer monthly rent',          '2025-11-01', 'High',   'Completed',   '2025-11-01'),
(2,  1, 'Call mom',           'Weekly check-in call',           '2025-11-03', 'Low',    'Pending',     NULL),
(3,  2, 'Finish DB homework', 'Milestone 3 SQL script',         '2025-11-10', 'High',   'In Progress', NULL),
(4,  3, 'Prepare report',     'Quarterly sales summary',        '2025-11-15', 'High',   'Pending',     NULL),
(5,  4, 'Buy milk',           '2% milk, 1 gallon',              '2025-11-02', 'Medium', 'Completed',   '2025-11-02'),
(6,  5, 'Book dentist',       'Checkup before end of month',    '2025-11-25', 'Medium', 'Pending',     NULL),
(7,  6, 'Gym session',        'Leg day training',               '2025-11-05', 'Low',    'Completed',   '2025-11-05'),
(8,  7, 'Deploy MVP',         'Push v1 of startup app',         '2025-11-20', 'High',   'In Progress', NULL),
(9,  8, 'Study for midterm',  'Review chapters 1-5',            '2025-11-08', 'High',   'Completed',   '2025-11-08'),
(10, 9, 'Plan weekend trip',  'Hiking with friends',            '2025-11-12', 'Low',    'Pending',     NULL),
(11, 2, 'Read textbook',      'Normalization & ER diagrams',    '2025-11-07', 'Medium', 'Completed',   '2025-11-07'),
(12, 3, 'Client follow-up',   'Email ACME Corp status',         '2025-11-04', 'Medium', 'Completed',   '2025-11-04'),
(13,10, 'Morning run',        '5km easy pace',                  '2025-11-03', 'Medium', 'Completed',   '2025-11-03'),
(14,8, 'Group project sync',  'Meet with team on Zoom',         '2025-11-06', 'High',   'Pending',     NULL),
(15,7, 'Investor pitch deck', 'Finalize slides',                '2025-11-18', 'High',   'Pending',     NULL),
(16,7, 'Code review',         'Review pull requests',           '2025-11-12', 'High',   'Pending',     NULL);

/*
========================================
Question 3: Five Queries (15 points)
Each query = 3 pts, each powers a feature & 
demonstrates required SQL concept.
========================================
*/

------------------------------------------------
-- Q3.1 INNER JOIN
-- Feature: Show all tasks for a given user
-- (e.g., "My Tasks" page for logged-in user)
------------------------------------------------
SELECT
    u.username,
    l.list_name,
    t.task_id,
    t.title,
    t.status,
    t.due_date,
    t.priority
FROM Users u
JOIN TodoLists l ON u.user_id = l.user_id
JOIN Tasks t     ON l.list_id = t.list_id
WHERE u.username = 'alice'
ORDER BY t.due_date;

------------------------------------------------
-- Q3.2 Aggregation + GROUP BY
-- Feature: Admin/analytics widget:
-- how many tasks each user currently has.
------------------------------------------------
SELECT
    u.username,
    COUNT(t.task_id) AS total_tasks
FROM Users u
JOIN TodoLists l ON u.user_id = l.user_id
JOIN Tasks t     ON l.list_id = t.list_id
GROUP BY u.username
ORDER BY total_tasks DESC;

------------------------------------------------
-- Q3.3 Subquery
-- Feature: Find "power users":
-- users with more completed tasks than the
-- average completed-tasks-per-user.
------------------------------------------------
SELECT
    u.username,
    COUNT(t.task_id) AS completed_tasks
FROM Users u
JOIN TodoLists l ON u.user_id = l.user_id
JOIN Tasks t     ON l.list_id = t.list_id
WHERE t.status = 'Completed'
GROUP BY u.username
HAVING COUNT(t.task_id) >
(
    SELECT AVG(CompletedCount)
    FROM (
        SELECT COUNT(t2.task_id) AS CompletedCount
        FROM Users u2
        JOIN TodoLists l2 ON u2.user_id = l2.user_id
        JOIN Tasks t2     ON l2.list_id = t2.list_id
        WHERE t2.status = 'Completed'
        GROUP BY u2.user_id
    ) AS CompletedPerUser
);

------------------------------------------------
-- Q3.4 GROUP BY + HAVING
-- Feature: Flag overloaded lists:
-- lists with at least 3 HIGH-priority tasks.
------------------------------------------------
SELECT
    l.list_id,
    l.list_name,
    u.username,
    COUNT(t.task_id) AS high_priority_tasks
FROM TodoLists l
JOIN Users u ON l.user_id = u.user_id
JOIN Tasks t ON l.list_id = t.list_id
WHERE t.priority = 'High'
GROUP BY l.list_id, l.list_name, u.username
HAVING COUNT(t.task_id) >= 3;

------------------------------------------------
-- Q3.5 LEFT OUTER JOIN
-- Feature: Admin view:
-- show all users (even if they never created a list)
-- and how many tasks they have total.
------------------------------------------------
SELECT
    u.username,
    COUNT(t.task_id) AS total_tasks
FROM Users u
LEFT JOIN TodoLists l ON u.user_id = l.user_id
LEFT JOIN Tasks t     ON l.list_id = t.list_id
GROUP BY u.username
ORDER BY total_tasks DESC;
