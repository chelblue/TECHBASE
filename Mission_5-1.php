<?php
 // DB接続設定
 $dsn = 'データベース名';
 $user = 'ユーザー名';
 $password = 'パスワード';
 $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$delete=filter_input(INPUT_POST,'delete');
$edit=filter_input(INPUT_POST,'edit');
$enum=filter_input(INPUT_POST,'enum');
$name=filter_input(INPUT_POST,'name');
$comment=filter_input(INPUT_POST,'comment');

 //4-1で書いた「// DB接続設定」のコードの下に続けて記載する。
 //テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS お腹ペコリーヌ"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT"
.");";
//query関数で指定したSQL文をデータベースに対して発行してくれる役割
$stmt = $pdo->query($sql);

//新規投稿モード
if(!empty($name) && !empty($comment) && empty($enum)){
    $sql = $pdo -> prepare("INSERT INTO お腹ペコリーヌ (name, comment) VALUES (:name, :comment)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> execute();
    //bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなります。最適なものを適宜決めよう。
    
}
//編集モード
if(!empty($name) && !empty($comment) && !empty($enum)){
    //bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
    $id = $enum; //変更する投稿番号
    $sql = 'UPDATE お腹ペコリーヌ SET name=:name,comment=:comment WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    //bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなります。最適なものを適宜決めよう。
}
//削除モード
if(!empty($delete)){
    $id = $delete;
    $sql = 'delete from お腹ペコリーヌ where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

//投稿フォームに編集
$editnumber = "";
$editname = "";
$editcomment = "";
//編集対象番号が空でないなら
if(!empty($edit)){
    $id = $edit ; // idがこの値のデータだけを抽出したい、とする
    $sql = 'SELECT * FROM お腹ペコリーヌ WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        //以下は編集時に投稿フォームに表示するときに使う変数
        $editnumber = $row['id'];
        $editname = $row['name'];
        $editcomment = $row['comment'];   
     }
            
        
}
    

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
    <body>
<form action="" method="post">
        <input type="text" name="name" placeholder="名前" autocomplete="off" value="<?php echo $editname ; ?>"><br>
        <input type="text" name="comment" placeholder="コメント" autocomplete="off" value="<?php echo $editcomment ; ?>"><br>
        <input type="submit" name="submit"><br><br>
        <input type="number" name="delete" placeholder="削除対象番号" autocomplete="off"><br>
        <input type="submit" name="submit" value="削除"><br><br>
        <input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="submit" name="submit" value="編集"><br>
        <input type="hidden" name="enum" value="<?php echo $editnumber;?>"><br>
    </form>

    </body>
</html>
<?php
//$rowの添字（[ ]内）は、4-2で作成したカラムの名称に併せる必要があります。
$sql = 'SELECT * FROM お腹ペコリーヌ';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].'<br>';
    }
?>