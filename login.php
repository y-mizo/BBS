<?php
session_start();

// $_SESSION['id']という値を持っているかどうかでログインの有効性を判断
if (!empty($_SESSION['id']))  // もしidがあれば
{
    header('Location: index.php');  // index.phpに飛ばす!
}

require_once('config.php');
require_once('functions.php');

// submitボタンで送信された情報が「POST」だったらの処理
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST['name'];
    $email =$_POST['email'];
    $errors = array();  // バリデーションのエラーメッセージ

    // バリデーション
    if ($name == '')  // もしnameが空だったら
    {
        $errors['name'] = '※ ユーザネームが未入力です';
    }
    if ($email == '')  // もしemailが空だったら
    {
        $errors['email'] = '※ メールアドレスが未入力です';
    }
    // バリデーション突破後
    if (empty($errors))  // $errorsが空だったら(=エラーが無かったら)
    {
        $dbh = connectDb();
        $sql = "select * from users where name = :name and email = :email";  // テーブルの中に該当レコードがあるか
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $row = $stmt->fetch();  // レコードの取り出し

        // var_dump($row);

    if ($row)  // 該当レコードがあったら
    {
        $_SESSION['id'] = $row['id'];  // セッションのidにレコードのidを持たせる
        $_SESSION['name'] = $row['name'];
        header('Location: index.php');  // index.phpに飛ばす
        exit;
    }
    else  // もし該当レコードがなかったら
    {
        echo 'ユーザネームかメールアドレスが間違っています';
    }

    }
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ログイン画面</title>
    </head>
    <body>
        <h1>ログイン</h1>
        <form aciton="" method="post">
            <p>
            ユーザネーム: <input type="text" name="name">
            <?php if ($errors['name']) : ?>
                <?php echo h($errors['name']) ?>
            <?php endif ?>
            </p>
            <p>
            メールアドレス: <input type="text" name="email">
            <?php if ($errors['email']) : ?>
                <?php echo h($errors['email']) ?>
            <?php endif ?>
            </p>
            <input type="submit" value="ログイン">
        </form>
        <a href="signup.php">新規登録はこちら!</a>
    </body>
</html>