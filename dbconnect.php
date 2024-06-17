<?php
$db = @mysqli_connect(
    "localhost",
    "id22273636_yuya",
    "Yuya1119!",
    "id22273636_yuya");

if (!$db) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>