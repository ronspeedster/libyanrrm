<?php
	include('dbh.php');
	$user_id =  $_SESSION['user_id'];
	$getURI = $_SESSION['getURI'];

	if(isset($_POST['status_post'])){
		$status_post = $_POST['status_text'];
		$status_post = str_replace('"', "", $status_post);
		$status_post = str_replace("'", "", $status_post);
		$mysqli->query(" INSERT INTO user_posts ( user_id, user_post /*, user_location */) VALUES('$user_id','$status_post' ) ") or die ($mysqli->error());

		header("location: index.php");
	}

	if(isset($_GET['addlink'])){
		$link_id = $_GET['addlink'];
		$mysqli->query(" INSERT INTO user_links ( from_user_id, to_user_id /*, user_location */) VALUES('$user_id','$link_id' ) ") or die ($mysqli->error());
		header("location: ".$getURI);
	}

	if(isset($_GET['confirmfromlink'])){
		echo $confirmfromlink = $_GET['confirmfromlink'];
		echo $tolink = $_GET['tolink'];
		$mysqli->query("UPDATE user_links SET linked='true' WHERE from_user_id='$confirmfromlink' AND to_user_id='$tolink' ") or die ($mysqli->error());
		header("location: ".$getURI);
	}
?>