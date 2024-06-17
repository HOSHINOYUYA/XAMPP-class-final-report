<?php 
session_start();
require('dbconnect.php'); 

if (isset($_SESSION['id']) && isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];

	
	$posts = mysqli_prepare($db, 'SELECT created_by FROM posts WHERE id=?');
	mysqli_stmt_bind_param($posts, 'i', $id);
	mysqli_stmt_execute($posts);
	mysqli_stmt_bind_result($posts, $created_by);
	mysqli_stmt_fetch($posts);
	mysqli_stmt_close($posts);

	
	if ($created_by == $_SESSION['id']) {
		$del = mysqli_prepare($db, 'DELETE FROM posts WHERE id=?');
		mysqli_stmt_bind_param($del, 'i', $id);
		mysqli_stmt_execute($del);
		mysqli_stmt_close($del);
	}
}


header('Location: post.php');
exit();
?>