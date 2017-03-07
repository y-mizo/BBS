<?php
// DB接続、定数、関数を読み込み
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
        $sql = "insert into users (name, email, created_at) values
                (:name, :email, now())";  // プレースホルダー
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // 新規登録後、速攻ログイン画面に飛ばす
        header('Location: login.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>新規登録画面</title>
    </head>
    <body>
        <h1>新規登録</h1>
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
            <input type="submit" value="登録する">
        </form>
        <a href="login.php">ログイン画面へ</a>
    </body>
</html>