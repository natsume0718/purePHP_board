<?php
require('functions.php');
session_start();

$auth_msg = '';
$auth_flg = FALSE;

if(!empty($_POST['pass']))
{
	//キーがない時、有効時間を過ぎている,セッションのキーと一致するとき認証成功
	if(!empty($_SESSION['key']) && !empty($_GET['key']) && $_SESSION['key_limit'] > time() && $_SESSION['key'] === $_GET['key'])
	{
		$email = h($_SESSION['email']);
		$new_pass = h($_POST['pass']);
		$pdo = connectDB();
		$stmt = $pdo ->prepare('UPDATE members SET pass=:pass WHERE mail=:email');
		$stmt->execute(array(':pass'=>$new_pass,':email'=>$email));
		$update_result = $stmt->rowCount();
		if($update_result)
		{
			$auth_flg = TRUE;
			$auth_msg = '新規パスワードに変更しました。';
			//セッション変数を破棄
			$_SESSION = array();
		}
		else
		{
			$auth_msg = 'パスワード変更に失敗しました';
		}
	}
	else
	{
		$auth_msg = '不正なアクセスです。再度お試しください。';
	}
}

?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
<meta charset="utf-8">
<title>パスワード再設定</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<body>
<div class="mx-auto" style="width: 70vw;">
<?php
//メッセージ表示
if(!empty($auth_msg)):
?>
<div class="alert alert-primary" role="alert"><strong><?php echo $auth_msg; ?></strong></div>
<?php endif; ?>

<?php
//認証可否でフォーム表示
if(!$auth_flg):
?>
<form method="POST">
<div class="form-group">
<label for="exampleInputPassword1">新規パスワード</label>
<input type="password" required name="pass" class="form-control" id="exampleInputPassword1" placeholder="パスワード">
<button type="submit" class="btn btn-primary">送信する</button>
</form>
</div>
<?php endif; ?>
<p><a href="index.php">トップへ</a></p>
<p><a href="login.php">ログインする</a></p>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
 </body>
</html>
