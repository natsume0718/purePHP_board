<?php
require('functions.php');
session_start();
$msg = null;
//ログインしないでアクセスされたら飛ばす
if(empty($_SESSION['id']) || empty($_SESSION['name']))
{
	header('Location:index.php');
	exit();
}
if(!empty($_POST))
{
	if(!empty($_POST['title']) && !empty($_POST['main-text']))
	{
		//タイトル、テキスト、ユーザーid格納
		$title = htmlspecialchars($_POST['title'],ENT_QUOTES);
		$main_text = htmlspecialchars($_POST['main-text'],ENT_QUOTES);
		$user_id = $_SESSION['id'];
		//接続
		$pdo = connectDB();
		$stmt = $pdo->prepare('INSERT INTO board (title,text,user_id) VALUES(:title,:text,:user_id)');
		$stmt->execute(array(':title'=>$title,':text'=>$main_text,'user_id'=>$user_id));
		header('Location:index.php');
		exit();
	}
	else
	{
		$msg = '未入力があります';
	}
}
?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
<meta charset="utf-8">
<title>掲示板</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>

<body>
	<div class="mx-auto" style="width: 70vw;">
	 <form method="POST">
		<div class="form-group">
			<label for="exampleInputTextarea1">タイトル</label>
			<input type="text" name="title" class="form-control" id="exampleFormControlTextarea1" placeholder="タイトル">
		</div>
		<div class="form-group">
			<label for="exampleFormControlTextarea1">本文</label>
			<textarea name="main-text" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
		</div>
		<button type="submit" class="btn btn-primary">送信する</button>
	</form>
</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>

