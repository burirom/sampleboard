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

if(isset($_POST['Thread_title'])){
	$Threadtitle = $_POST['Thread_title'];
	
	if($Threadtitle!=""){
	try {
		//MySQLデータベースに接続する
		$pdo = new PDO($dsn, $user, $password);
		// プリペアドステートメントのエミュレーションを無効にする
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		// 例外がスローされる設定にする
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//インサート
		$insertsql = "INSERT INTO Thread_Tbl(Thread_title,Thread_date,Ins_Date) 
	VALUES (:Threadtitle,CAST( NOW() as date),CAST( NOW() as datetime));";

		// プリペアドステートメントを作る
		$stm1 = $pdo->prepare($insertsql);
		//プレースホルダーの値をバインドする
		$stm1->bindValue(':Threadtitle',$Threadtitle,PDO::PARAM_STR);
			
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
$searchsql = "select Thread_id,Thread_title,Thread_date from Thread_Tbl ORDER BY Thread_id;";

	// プリペアドステートメントを作る
	$stm2 = $pdo->prepare($searchsql);
	// SQLクエリを実行する
	$stm2->execute();
	// 結果の取得（連想配列で受け取る）
	$thread = $stm2->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
	$err =  '<span class="error">エラーがありました。</span><br>';
	$err .= $e->getMessage();
	exit($err);
}

foreach ($thread as $row){
	echo '<li>',$row["Thread_id"],'.<a href="chat.html?thread_id=',$row["Thread_id"],'">',$row["Thread_title"],'</a><div class="date">投稿日:',$row["Thread_date"],'</div></li>';
}




?>