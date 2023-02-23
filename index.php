<!DOCTYPE html>
<html>
<head>
	<title>Todo List</title>
	<style type="text/css">
        body {
    font-family: Arial, sans-serif;
    font-size: 14px;
}
table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
th {
    background-color: #4CAF50;
    color: white;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
form {
    margin-top: 20px;
}
label {
    display: inline-block;
    width: 100px;
    text-align: right;
    margin-right: 10px;
}
input[type="text"] {
    width: 300px;
    padding: 5px;
    border-radius: 3px;
    border: 1px solid #ccc;
    box-shadow: inset 0 1px 3px #ddd;
}
input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}
input[type="submit"]:hover {
    background-color: #3e8e41;
}
input[type="hidden"] {
    display: none;
}
</style>
</head>
<body>

<?php
// Connect to MySQL
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'todolist';
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// If the "Add" form was submitted, insert a new item into the database
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $sql = "INSERT INTO todoitems (Title, Description) VALUES ('$title', '$description')";
    if (mysqli_query($conn, $sql)) {
        echo "Item added successfully.";
    } else {
        echo "Error adding item: " . mysqli_error($conn);
    }
}

// If the "Delete" form was submitted, delete the selected item from the database
if (isset($_POST['delete'])) {
    if (isset($_POST['itemNum'])) { // Check if the 'itemNum' key is set
        $itemNum = $_POST['itemNum'];
        $sql = "DELETE FROM todoitems WHERE itemNum = $itemNum";
        if (!mysqli_query($conn, $sql)) {
            echo "<p>Item deleted successfully.</p>";
        } else {
            echo "Error: ". mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p>Error deleting item: itemNum is not set</p>";
    }
}

// Retrieve all items from the database
$sql = "SELECT * FROM todoitems";
$result = mysqli_query($conn, $sql);

// Display items in an HTML table
echo "<table>";
echo "<tr><th>Item #</th><th>Title</th><th>Description</th><th></th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>" . (isset($row['itemNum']) ? $row['itemNum'] : '') . "</td><td>" . $row['Title'] . "</td><td>" . $row['Description'] . "</td>";
    echo "<td><form method='post'><input type='hidden' name='itemNum' value='" . (isset($row['itemNum']) ? $row['itemNum'] : '') . "'><input type='submit' name='delete' value='Delete'></form></td></tr>";
    
}
echo "</table>";

// Display form to add a new item
echo "<h2>Add Item</h2>";
echo "<form method='post'><label>Title:</label><input type='text' name='title'><br>";
echo "<label>Description:</label><input type='text' name='description'><br>";
echo "<input type='submit' name='add' value='Add'></form>";

// Close MySQL connection
mysqli_close($conn);
?>