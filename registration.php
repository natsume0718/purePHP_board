<?php
require('functions.php');
session_start();
$msg = null;
$success = null;
if(!empty($_POST))
{
	if(!empty($_POST['user_name']) &&!empty($_POST['mail']) && !empty($_POST['pass']))
	{
		//サニタイズ
		$user_name = htmlspecialchars($_POST['user_name'],ENT_QUOTES);
		$mail = htmlspecialchars($_POST['mail'],ENT_QUOTES);
		$pass = htmlspecialchars($_POST['pass'],ENT_QUOTES);
		//メールアドレスチェック
		if(!filter_var($mail,FILTER_VALIDATE_EMAIL))
		{
			$msg = 'メールアドレスの形式で入力してください';
		}
		else
		{
			//接続
			$pdo = connectDB();
			//重複があったら挿入しない
			$stmt = $pdo->prepare('INSERT IGNORE INTO members (name,mail,pass) VALUES(:name,:mail,:pass)');
			//クエリ実行し、結果取得
			$stmt->execute(array(':name'=>$user_name,':mail'=>$mail,'pass'=>$pass));
			$result = $stmt->rowCount();
			//接続遮断
			$pdo = null;
			$stmt = null;
			//挿入成功
			if($result)
			{
				$msg = '新規登録しました';
			}
			else
			{
				$msg = '既に登録されています';
			}
		}
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
  <title>新規登録</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
  <body>
	<div class="mx-auto" style="width: 70vw;">
<?php
if(!isset($_SESSION['id'])):
	if(isset($msg)):
		//登録されていたら
		if(!empty($result)): ?>
			<div class="alert alert-success" role="alert"><strong><?php echo $msg; ?></strong><a href="./login.php">■ログインする</a></div>
			<?php else: ?>
			<div class="alert alert-danger" role="alert"><strong><?php echo $msg; ?></strong></div>
			<?php endif; ?>
		<?php endif; ?>
<?php
			//登録されていたら
			if(empty($result)): ?>
		 <form method="POST">
			<div class="form-group">
				<label for="exampleInputEmail1">ユーザー名</label>
				<input type="text" name="user_name" required class="form-control" id="exampleFormControlTextarea1" placeholder="ユーザー名" value="<?php if(isset($_POST['user_name'])) echo $_POST['user_name']; ?>">
			</div>
			<div class="form-group">
				<label for="exampleInputEmail1">Eメールアドレス</label>
				<input type="email" name="mail" class="form-control" id="exampleInputEmail1" placeholder="Eメールアドレス" value="<?php if(isset($_POST['mail'])) echo $_POST['mail']; ?>">
			</div>
			<div class="form-group">
				<label for="exampleInputPassword1">パスワード</label>
				<input type="password" required name="pass" class="form-control" id="exampleInputPassword1" placeholder="パスワード">
			</div>
			<button type="submit" class="btn btn-primary">送信する</button>
		</form>
		<?php endif; ?>
<?php
			else: ?>
	<div class="alert alert-primary" role="alert"><strong>既にログインしています。</strong><a href="index.php" class="alert-link">■トップへ戻る</a></div>
	<?php endif; ?>
</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </body>
</html>

