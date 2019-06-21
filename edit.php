<?php
require('functions.php');
session_start();

if(!empty($_POST))
{
	//編集
	if(!empty($_POST['title']) && !empty($_POST['main-text']))
	{
		//タイトル、テキスト
		$title = htmlspecialchars($_POST['title'],ENT_QUOTES);
		$main_text = htmlspecialchars($_POST['main-text'],ENT_QUOTES);
		$edit_post = $_SESSION['edit_post'];
		//セッション変数破棄
		$_SESSION['edit_post'] = null;
		//接続
		$pdo = connectDB();
		//対象投稿上書き
		$stmt = $pdo->prepare('UPDATE board SET title = :title,text = :text WHERE post_id = :id');
		$stmt->execute(array(':title'=>$title,':text'=>$main_text,':id'=>$edit_post));
		//遷移
		header('Location:index.php');
		exit();

	}
	//削除処理
	if(!empty($_POST['delete']))
	{
		$edit_post = $_SESSION['edit_post'];
		//セッション変数破棄
		$_SESSION['edit_post'] = null;
		//接続
		$pdo = connectDB();
		//倫理削除
		$stmt = $pdo->prepare('UPDATE board SET delete_flag = 1 WHERE post_id = :id');
		$stmt->execute(array(':id'=>$edit_post));
		//遷移
		header('Location:index.php');
		exit();

	}
}
if(!empty($_GET))
{
	if(!empty($_GET['editId']) && is_numeric($_GET['editId']))
	{
		$editId = htmlspecialchars($_GET['editId'],ENT_QUOTES);
		//接続
		$pdo = connectDB();
		//対象投稿取得
		$stmt = $pdo->prepare('SELECT * FROM board WHERE post_id = :id');
		$stmt->execute(array(':id'=>$editId));
		$result = $stmt->fetch();
		//DB内の投稿者と違ったら処理中断
		if($result['user_id'] != $_SESSION['id'] )
		{
			header('Location:index.php');
			exit();
		}
		//結果を保存しておく
		$_SESSION['edit_post'] = $result['post_id'];
	}
}

?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
<meta charset="utf-8">
<title>編集</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>

<body>
	<div class="mx-auto" style="width: 70vw;">
	 <form method="POST">
		<div class="form-group">
			<label for="exampleInputTextarea1">タイトル</label>
			<input type="text" name="title" class="form-control" id="exampleFormControlTextarea1" placeholder="タイトル" value="<?php if(isset($result['title'])) echo $result['title']; ?>">
		</div>
		<div class="form-group">
			<label for="exampleFormControlTextarea1">本文</label>
			<textarea name="main-text" class="form-control" id="exampleFormControlTextarea1" rows="3"><?php if(isset($result['text'])) echo $result['text']; ?></textarea>
		</div>
		<button type="submit" class="btn btn-primary">編集する</button>
	</form>
	<form method="POST">
		<input type="hidden" name="delete" value="delete"/>
		<button type="submit" class="btn btn-primary">削除する</button>
	</form>
</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>

