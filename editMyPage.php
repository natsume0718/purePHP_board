<?php
require('functions.php');
session_start();
if($_GET)
{
	//ログイン者と違ったら飛ばす
	if($_SESSION['id'] == $_GET['user_id'])
	{
		if(!empty($_GET['user_id']) && is_numeric($_GET['user_id']))
		{
			$user_id = htmlspecialchars($_GET['user_id'],ENT_QUOTES);
			$pdo = connectDB();
			//対象ユーザー情報取得
			$stmt = $pdo->prepare('SELECT * FROM members WHERE id = :id');
			$stmt->execute(array(':id'=>$user_id));
			$result_user = $stmt->fetch();
			//対象がいなかったら遷移
			if(!$result_user)
			{
				header('Location:index.php');
				exit();
			}
		}
	}
	else
	{
		header('Location:index.php');
		exit();
	}
}
if(!empty($_POST) || !empty($_FILES))
{
	//画像削除が選択されたら
	if(!empty($_POST['action']) && $_POST['action'] ==='del')
	{
		$pdo = connectDB();
		$stmt = $pdo-> prepare('UPDATE members SET image=NULL WHERE id=:id');
		$stmt->execute(array(':id'=>$_SESSION['id']));
	}
	else
	{
		//画像登録
		if(!empty($_FILES['prof-img']))
		{
			$img = $_FILES['prof-img'];
			$path = uploadImageToServer($img);
			if($path)
			{
				$pdo = connectDB();
				$stmt = $pdo-> prepare('UPDATE members SET image=:path WHERE id=:id');
				$stmt->execute(array(':path'=>$path,':id'=>$_SESSION['id']));
			}
		}
		//comment登録
		if(!empty($_POST['comment']))
		{
			$comment = htmlspecialchars($_POST['comment'],ENT_QUOTES);
			$pdo = connectDB();
			$stmt = $pdo-> prepare('UPDATE members SET comment=:comment WHERE id=:id');
			$stmt->execute(array(':comment'=>$comment,':id'=>$_SESSION['id']));
		}
	}
	//遷移する
	header("Location:mypage.php?user_id=".$_SESSION['id']);
	exit();
}

?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
<meta charset="utf-8">
<title>マイページ編集</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<body>
<div class="mx-auto" style="width: 70vw;">
<?php
if(!empty($result_user['image'])): ?>
<img src="<?php echo $result_user['image']; ?>" alt="プロフィール画像" class="img-thumbnail .img-fluid">
<?php else: ?>
<p>画像未登録</p>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
<input type="file" name="prof-img"/>
<input type="textarea" name="comment" value="<?php echo $result_user['comment']; ?>">
<button type="submit">保存</button>
<button type="submit" name="action" value="del">画像削除</button>
</form>
<p><?php echo $result_user['name']; ?></p>
<p><?php echo $result_user['mail']; ?></p>
<a href="index.php">topに戻る</a>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>

