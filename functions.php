<?php
require('config.php');
function connectDB()
{
	try
	{
		$db_options = array
			(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
			);
		return new PDO(DSN,DUSER,DPASS,$db_options);
	}
	catch(PDOException $e)
	{
		echo $e->getMessages();
		return false;
	}
}


function validImageFile($file)
{
	$img = $file;
	//サイズ判定
	if($img['size'] < MAX_IMAGE_SIZE)
	{
		//画像形式か判定
		if($img['type'] === 'image/png' || $img['type'] === 'image/jpeg' )
		{
			return $img;
		}
	}

	return false;
}

function uploadImageToServer($file)
{
	$img = $file;
	//画像形式か判定
	if(validImageFile($img))
	{
		//ファイルの形式取得
		$type = pathinfo($img['name'],PATHINFO_EXTENSION);
		//ファイルのハッシュ
		$hash_name = hash_file('sha1',$img['tmp_name']);
		//それぞれを連結して保存パスに用いる
		$uploaded_img_fullpath = IMAGE_DIR.$hash_name. '.' .$type;
		//一時ディレクトリから移動
		$move_dir = move_uploaded_file($img['tmp_name'],$uploaded_img_fullpath);
		//移動成功していたら保存パスを返す
		if($move_dir)
		{
			return $uploaded_img_fullpath;
		}
		return false;
	}
	else
	{
		return false;
	}
}

function h($str)
{
	return htmlspecialchars($str,ENT_QUOTES);
}

function sendMail($to,$title,$main_text)
{
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	//サニタイズ
	$address = h($to);
	$subject = h($title);
	$message = h($main_text);
	$header = 'From ' .ADDMIN_MAIL;
	if(!empty($address) && !empty($subject) && !empty($message))
	{
		//メールを送信して結果を返す
		$res = mb_send_mail($address,$subject,$message,$header);
		return	$res;
	}
	return false;
}

?>
