
<html>
  <head>
    <meta charset="utf-8">
  </head>
<body>
  <?php
    $dsn = 'mysql:dbname=tb2****db;host=localhost';
    $user = 'tb-2*****8';
    $password = 'LK***5b';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS db5"//ファイルが存在しなければ
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"//AUTO_INCREMENT:自動で一番最後のID＋１を入れる　主キー（例：学籍番号）
    . "name char(32),"
    . "comment TEXT,"
    . "dates char(32),"
    . "password char(32)"
    .");";
    $stmt = $pdo->query($sql);

    /*
    $filename="mission_3-5.txt";
    if(file_exists($filename)){//ファイルが存在するとき
    }
    else{//ファイルが存在しないとき未宣言などのエラーが表示されないように
      $tmp=fopen($filename,"w");
      fclose($tmp);
      $num1=0;
    }
    $fp=file($filename);
    */

    //投稿フォーム処理
    //データがすべてきちんと入っていた時
    if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["post"])&&!empty($_POST["p_pass"])){
      $m_num=$_POST["mode"];
      //$num1=0;

      if(empty($m_num)){//モードが入力されてない、新規投稿のとき
        /*foreach ($fp as $value) {
          $x=explode("<>",$value);
          $num1=$x[0];
        }
        //$num1=count($fp);
        $num1=$num1+1;*/

        //$data=$num1."<>".$_POST["name"]."<>".$_POST["comment"]."<>".date("Y/m/d H:i:s")."<>".$_POST["p_pass"]."<>";//データフォーマット

        //file_put_contents($filename,$data."\n",FILE_APPEND);//FILE_APPEND:追記
        $sql = $pdo -> prepare("INSERT INTO db5 (name, comment,dates,password) VALUES (:name, :comment,:dates,:password)");
      	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
      	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':dates', $dates, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
      	$name = $_POST["name"];
      	$comment = $_POST["comment"];
        $dates= date("Y/m/d H:i:s");
        $password=$_POST["p_pass"];
      	$sql -> execute();
      }
      else{//モードが入力されている、編集投稿のとき
        /*if(!isset($str)){//$strがセットされていないとき
         $str="";
        }
        foreach ($fp as $value) {//ひとつづつテキストファイルの中身を取り出す
          $x=explode("<>",$value);//配列にする
          if($x[0]==$m_num){//編集番号と番号が一致しているとき
            $str=$str.$x[0]."<>".$_POST["name"]."<>".$_POST["comment"]."<>".date("Y/m/d H:i:s")."<>".$_POST["p_pass"]."<>"."\n";//編集したものに書き換え
          }
          else{//一致していないとき
            $str=$str.$value;//そのまま
          }
        }
          file_put_contents($filename,$str);
        */

        $id =$_POST["mode"]; //変更する投稿番号
      	$name =$_POST["name"];
      	$comment =$_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
        $dates=date("Y/m/d H:i:s");
        $password=$_POST["p_pass"];
      	$sql = 'update db5 set name=:name,comment=:comment,dates=:dates,password=:password where id=:id';
      	$stmt = $pdo->prepare($sql);
      	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
      	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':dates', $dates, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
      	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
      	$stmt->execute();
      }
    }
    //名前データが入っていないとき
    elseif(!empty($_POST["post"])&&empty($_POST["name"])){
      echo "名前を入力してください<br>";
    }
    //コメントデータが入っていないとき
    elseif(!empty($_POST["post"])&&empty($_POST["comment"])){
      echo "コメントを入力してください<br>";
    }
    elseif(!empty($_POST["post"])&&empty($_POST["p_pass"])){
      echo "パスワードを入力してください<br>";
    }

    //削除フォーム処理
    if(!empty($_POST["d_num"])&&!empty($_POST["delete"])){//番号入力と削除ボタンが押されたとき
      $delete_num=$_POST["d_num"];
      $d_pass=$_POST["d_pass"];
      $id = $delete_num;
    	$sql = 'delete from db5 where id=:id AND password=:password';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':password', $d_pass, PDO::PARAM_STR);
    	$stmt->execute();
    }
    elseif(!empty($_POST["delete"])&&empty($_POST["d_num"])){//削除番号の指定がないとき
      echo "削除番号を指定してください<br>";
    }
    elseif(!empty($_POST["delete"])&&empty($_POST["d_pass"])){//削除番号の指定がないとき
      echo "パスワードを入力してください<br>";
    }

    //編集フォーム処理
    if(!empty($_POST["e_num"])&&!empty($_POST["edit"])&&!empty($_POST["e_pass"])){
      $e_numb=$_POST["e_num"];
      /*foreach ($fp as $value) {
        $x=explode("<>",$value);
        if($x[0]==$e_numb){
          $p_name=$x[1];
          $p_comment=$x[2];
          $p_mode=$x[0];
        }
      }
      */
      $sql = 'SELECT * FROM db5';
    	$stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
        if($row['id']==$e_numb&&$_POST["e_pass"]==$row['password']){
          $p_name=$row['name'];
          $p_comment=$row['comment'];
          $p_pass=$row['password'];
          $p_mode=$row['id'];
        }
    	}
    }
    elseif(empty($_POST["e_num"])&&!empty($_POST["edit"])){
      echo "編集番号を入力してください<br>";
    }
    elseif(empty($_POST["e_pass"])&&!empty($_POST["edit"])){
      echo "パスワードを入力してください<br>";
    }

  ?>

  <form method="post">
    <h3>投稿フォーム</h3>
    <p>名前:<input type="text" name="name" value="<?php if(!isset($p_name)){ $p_name=""; } else{echo $p_name; } ?>"></p>
    <p>コメント:<input type="text" name="comment" value="<?php if(!isset($p_comment)){ $p_comment=""; } else{echo $p_comment; } ?>"></p>
    <p>パスワード:<input type="text" name="p_pass" value="<?php if(!isset($p_pass)){ $p_pass=""; } else{echo $p_pass; } ?>"></p><br>
    <input type="hidden" name="mode" value="<?php if(!isset($p_mode)){ $p_mode="";} else{echo $p_mode; } ?>">
    <input type="submit" name="post"><br>

    <h3>削除番号指定フォーム</h3>
    <p>削除対象番号:<input type="text" name="d_num"></p>
    <p>パスワード:<input type="text" name="d_pass"></p><br>
    <p>削除:<input type="submit" name="delete" ></p><br>

    <h3>編集番号指定フォーム</h3>
    <p>編集番号:<input type="text" name="e_num"></p>
    <p>パスワード:<input type="text" name="e_pass"></p><br>
    <p>編集:<input type="submit" name="edit"></p><br>
  </form>

  <h3>送信データ</h3>
  <?php
  /*$filename="mission_3-5.txt";
  $fp=fopen($filename,"r");
  $fp2=file($filename,FILE_IGNORE_NEW_LINES);//FILE_IGNORE_NEW_LINES:改行を無視？？
  foreach ($fp2 as $i) {
    // code...
    if(!empty($i)){
    $str2=explode("<>",$i);
    for($x=0;$x<5;$x++){
      echo $str2[$x]." ";
    }
      echo "<br>";
    }
  }*/

  $sql = 'SELECT * FROM db5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
    echo $row['dates'].',';
    echo $row['password'].'<br>';
	  echo "<hr>";
	}
  ?>
</body>
</html>
