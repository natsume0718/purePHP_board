<?php
require('functions.php');
session_start();
if(!empty($_POST))
{
	if(!empty($_POST['email']))
	{
		//サニタイズ
		$email = h($_POST['email']);
		$pdo = connectDB();
		$stmt = $pdo->prepare('SELECT COUNT(mail) FROM members WHERE mail=:mail');
		$stmt->execute(array(':mail'=>$email));
		$exist_email = $stmt->fetch();
		if($exist_email)
		{
			//ランダムなバイト文字列を生成して、16進変換
			$key = bin2hex(openssl_random_pseudo_bytes(20));
			$title = 'パスワード再発行のご案内';
			//キーをurlに連結
			$message = '新規パスワード入力ページ：http://freely-tech-std.sakura.ne.jp/takahashi/board/setPass.php?key=' .$key;
			//メールを送信して結果を受け取る
			$send_result = sendMail($email,$title,$message);
			if($send_result)
			{
				//成功していたら各種情報をセッションで保持
				$_SESSION['key'] = $key;
				$_SESSION['key_limit'] = time()+(60*30);
				$_SESSION['email'] = $email;
			}
		}
		//成功しても失敗してもメッセージをだす
		$msg = '再発行用URLを送信しました。';
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
if(!empty($msg)):
?>
<div class="alert alert-success" role="alert"><strong><?php echo $msg; ?></strong></div>
<?php endif; ?>
<p>パスワードをリセットします</p>
<form method="POST">
<div class="form-group">
<label for="exampleInputEmail1">Eメールアドレス</label>
<input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Eメールアドレス">
</div>
<button type="submit" class="btn btn-primary">送信する</button>
</form>
<a href="registration.php">新規登録はこちら</a>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>
