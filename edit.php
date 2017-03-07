<?php
// echo $_GET['id'];
require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDb();
$sql = "select * from posts where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

$row = $stmt->fetch();

// var_dump($row);

if (!$row)
{
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $message =$_POST['message'];
    $errors = array();  // バリデーションのエラーメッセージ

    // バリデーション
    if ($message == '')  // もしnameが空だったら
    {
        $errors['message'] = '※ メッセージが未入力です';
    }
    // バリデーション突破後
    if (empty($errors))  // $errorsが空だったら(=エラーが無かったら)
    {
        $dbh = connectDb();
        $sql = "update posts set message = :message, updated_at = now() where id = :id";  // プレースホルダー
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":message", $message);
        $stmt->execute();

        // 新規登録後、速攻ログイン画面に飛ばす
        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>編集画面</title>
    </head>
    <body>
        <h1>投稿内容を編集する</h1>
        <p><a href="index.php">戻る</a></p>
        <form aciton="" method="post">
        <!-- 編集前のメッセージをphpでエリア内に表示させる -->
            <textarea name="message" cols="30" rows="5"><?php echo h($row['message']) ?></textarea>
                <?php if ($errors['message']) : ?>
                    <?php echo h($errors['message']) ?>
                <?php endif ?>
            <input type="submit" value="編集する">
        </form>
    </body>
</html>