<?php
require('functions.php');
session_start();
//ログイン状況でアクセス振り分け
$access = 'login.php';
if(!empty($_SESSION['id']))
{
	$access = 'post.php';
}
//接続
$pdo = connectDB();
//未削除投稿全件取得
$stmt = $pdo->prepare('SELECT * FROM board LEFT OUTER JOIN members ON board.user_id = members.id WHERE delete_flag = 0');
$stmt->execute();
//結果を連想配列で
$resultPost = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="index.php">掲示板</a>
		<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#Navber" aria-controls="Navber" aria-expanded="false" aria-label="ナビゲーションの切替">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="Navber">
			<ul class="navbar-nav mr-auto"></ul>
			<ul class="navbar-nav">
				<li class="nav-item">
					<?php if(!isset($_SESSION['id'])): ?>
					<a class="nav-link" href="login.php">ログイン</a>
					<?php endif; ?>
				</li>
				<li class="nav-item">
					<?php if(!isset($_SESSION['id'])): ?>
					<a class="nav-link" href="registration.php">新規登録</a>
					<?php else: ?>
					<a class="nav-link" href="logout.php">ログアウト</a>
					<?php endif; ?>
				</li>
			</ul>
		</div>
	</nav>
	<div class="mx-auto" style="width: 70vw;">
	<a href="<?php echo $access; ?>">
<button type="button" class="btn" style="border:1px solid black";>投稿する</button></a>
<?php
//投稿が一件以上あったら表示
if(!empty($resultPost)):
	foreach($resultPost as $value): ?>
		<table class="table table-border" style="margin-top:24px;">
			<tr><th>ID:<?php echo $value['post_id']; ?></th><th><?php echo $value['post_date']; ?></th><th>投稿者:<a href="mypage.php?user_id=<?php echo $value['user_id']; ?>"><?php echo $value['name']; ?></th></a></tr>
			<tr><td colspan="3"><?php echo $value['title']; ?></td></tr>
			<tr><td colspan="3"><?php echo $value['text']; ?></td></tr>
<?php
//投稿者とログイン者が同じなら削除編集有効にする
if(!empty($_SESSION['id']) && $_SESSION['id'] == $value['user_id']): ?>
	<tr>
		<td colspan="3">
			<form method="GET" action="edit.php">
				<input type="hidden" name="editId" value="<?php echo $value['post_id'] ?>"/>
				<input type="submit" value="編集・削除"/>
			</form>
		</td>
	</tr>
<?php endif; ?>
</table>
<?php endforeach; ?>
<?php else: ?>
		<div class="alert alert-danger" role="alert"><strong>投稿がありません</strong></div>
		<?php endif; ?>
	</div>

	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>

