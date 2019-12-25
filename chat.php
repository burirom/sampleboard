<?php

// データベースユーザ
$user = 'root';
//パスワード
$password = 'root';
// 利用するデータベース
$dbName = 'Bulletin_board';
// MySQLサーバ
$host = 'localhost:3306';
// MySQLのDSN文字列
$dsn = "mysql:host=mysql;dbname={$dbName};charset=utf8";


$thread_id = $_POST['Thread_id'];
if(isset($_POST['chat_name'])&&isset($_POST['chat_content'])){
	$Chat_Name =  $_POST['chat_name'];
    $Chat_Content = $_POST['chat_content'];
    
    
    if($_POST['chat_name']!="" && $_POST['chat_content']!=""){
	
	
	try {
		//MySQLデータベースに接続する
		$pdo = new PDO($dsn, $user, $password);
		// プリペアドステートメントのエミュレーションを無効にする
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		// 例外がスローされる設定にする
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//インサート
		$insertsql = "INSERT INTO Chat_Tbl(Thread_id,Chat_name,Chat_Content,Chat_Date,Ins_Date) 
	VALUES (:thread_id,:Chat_Name,:Chat_Content,CAST( NOW() as date),CAST( NOW() as datetime));";

		// プリペアドステートメントを作る
		$stm1 = $pdo->prepare($insertsql);
		//プレースホルダーの値をバインドする
		$stm1->bindValue(':thread_id',$thread_id,PDO::PARAM_INT);
		$stm1->bindValue(':Chat_Name',$Chat_Name,PDO::PARAM_STR);
		$stm1->bindValue(':Chat_Content',$Chat_Content,PDO::PARAM_STR);
		
		// SQLクエリを実行する
		$stm1->execute();

	} catch (Exception $e) {
		$err =  '<span class="error">エラーがありました。</span><br>';
		$err .= $e->getMessage();
		exit($err);
	}
    }
		
	
	
}



try {
	//MySQLデータベースに接続する
	$pdo = new PDO($dsn, $user, $password);
	// プリペアドステートメントのエミュレーションを無効にする
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 例外がスローされる設定にする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//Thread_Tblからスレッド名と日付を取得
	$searchsql = "select Chat_id,Chat_name,Chat_Content,Chat_Date from Chat_Tbl WHERE Thread_id = :thread_id;";

	// プリペアドステートメントを作る
	$stm2 = $pdo->prepare($searchsql);
	//プレースホルダーの値をバインドする
	$stm2->bindValue(':thread_id',$thread_id,PDO::PARAM_INT);
	// SQLクエリを実行する
	$stm2->execute();
	// 結果の取得（連想配列で受け取る）
	$chat = $stm2->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
	$err =  '<span class="error">エラーがありました。</span><br>';
	$err .= $e->getMessage();
	exit($err);
}

foreach ($chat as $row){
	echo '<li class="row"><div class="col-sm-12 col-lg-12">',$row["Chat_Content"],'</div><div class="date col-sm-12">投稿日：',$row["Chat_Date"],'</div><div class="contributor col-sm-12">投稿者：',$row["Chat_name"],'</div></li>';
}




?>