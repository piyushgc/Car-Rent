<?php
include('config.php');

// Fetch last 4 search items
$sql = "SELECT item_name FROM searched_items ORDER BY id DESC LIMIT 4";
$result = $conn->query($sql);

$searches = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $searches[] = $row;
    }
}

echo json_encode($searches);

$conn->close();
?>
