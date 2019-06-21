<?php
require('functions.php');
session_start();
if(!empty($_GET))
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
		}
	}

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
	<p><?php echo $result_user['name']; ?></p>
	<p><?php echo $result_user['mail']; ?></p>
	<p>一言:<?php echo $result_user['comment']; ?></p>
	<?php if(!empty($_SESSION['id']) && $_SESSION['id'] == $result_user['id']): ?>
	<p><a href="editMyPage.php?user_id=<?php echo $_SESSION['id']; ?>">編集</a></p>
	<?php endif; ?>
	<a href="index.php">トップに戻る</a>
	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>

