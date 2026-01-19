<?php
$servername = "localhost";
$username = "root";
$password = "B@khoi2005";
$dbname = "school";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Drop the table if you want to reset it each time (optional for testing)
// mysqli_query($conn, "DROP TABLE IF EXISTS AvailableCourses");

// Create table only if it does not exist
$sql = "CREATE TABLE IF NOT EXISTS AvailableCourses (
    courseCode VARCHAR(10) PRIMARY KEY,
    courseName VARCHAR(100) NOT NULL,
    level VARCHAR(5) NOT NULL,
    credits INT
)";

if (mysqli_query($conn, $sql)) {
    echo "✅ Table 'AvailableCourses' is ready.<br>";
} else {
    echo "❌ Error creating table: " . mysqli_error($conn) . "<br>";
}

// Insert sample data
$sql = "INSERT INTO AvailableCourses (courseCode, courseName, level, credits)
        VALUES ('CS460', 'Software Engineering', 'U4', 5)";

if (mysqli_query($conn, $sql)) {
    echo "✅ New record inserted successfully.<br>";
} else {
    if (mysqli_errno($conn) == 1062) {
        echo "⚠️ Record already exists, skipping insert.<br>";
    } else {
        echo "❌ Error inserting record: " . mysqli_error($conn) . "<br>";
    }
}

// Optional: Display all records to confirm
$result = mysqli_query($conn, "SELECT * FROM AvailableCourses");
if (mysqli_num_rows($result) > 0) {
    echo "<h3>📘 Current Available Courses:</h3>";
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr><th>Course Code</th><th>Course Name</th><th>Level</th><th>Credits</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['courseCode']}</td>
                <td>{$row['courseName']}</td>
                <td>{$row['level']}</td>
                <td>{$row['credits']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No courses found.";
}

// Close connection
mysqli_close($conn);
?>
