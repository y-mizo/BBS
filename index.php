<?php
require_once('config.php');
require_once('functions.php');

session_start();

// $_SESSION['id']という値を持っているかどうかでログインの有効性を判断
if (empty($_SESSION['id']))  // もしidが空なら
{
    header('Location: login.php');  // login.phpに飛ばす!
}



// submitボタンで送信された情報が「POST」だったらの処理
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    // CSRF(クロスサイトリクエストフォージェリ。シーサーフ)対策。
    // セッションのtokenとポストされたtokenが一致しない場合、
    // エラーメッセージを表示する。
    // 2016/10/31
    if ($_SESSION['token'] != $_POST['token'])
    {
        $errors['message'] = '不正なリクエストです';
    }
    else
    {
        $name = $_SESSION['name'];
        $message =$_POST['message'];
        $errors = array();  // バリデーションのエラーメッセージ

        // バリデーション
        if ($message == '')  // もしnameが空だったら
        {
            $errors['message'] = '※ メッセージが未入力です';
        }
    }
    // バリデーション突破後
    if (empty($errors))  // $errorsが空だったら(=エラーが無かったら)
    {
        $dbh = connectDb();
        $sql = "insert into posts (name, message, created_at, updated_at) values
                (:name, :message, now(), now())";  // プレースホルダー
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":message", $message);
        $stmt->execute();

        // 新規登録後、速攻ログイン画面に飛ばす
        header('Location: index.php');
        exit;
    }
}

// CSRF対策。tokenを設定
$token = session_id();
$_SESSION['token'] = $token;

$dbh = connectDb();
// 更新時間の新しい順に取得
$sql = "select * from posts order by updated_at desc";
$stmt = $dbh->prepare($sql);
$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// var_dump($posts);

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>会員制掲示板</title>
    </head>
    <body>
        <h1><?php echo h($_SESSION['name']) ?>さん、会員制掲示板へようこそ!</h1>
        <p><a href="logout.php">ログアウト</a></p>
        <p>一言どうぞ!</p>
        <form aciton="" method="post">
            <textarea name="message" cols="30" rows="5"></textarea>
                <?php if ($errors['message']) : ?>
                    <?php echo h($errors['message']) ?>
                <?php endif ?>
            <input type="submit" value="投稿する">
            <!-- CSRF対策。hiddenでvalue=tokenに -->
            <input type="hidden" name="token" value="<?php echo $token; ?>">
        </form>
        <!-- hr=horizontal rule 水平の罫線
        http://www.htmq.com/html/hr.shtml-->
        <hr>
        <h1>投稿されたメッセージ</h1>
        <?php if (count($posts)) : ?>
            <?php foreach ($posts as $post) : ?>
                <li style="list-style-type: none;">
                    [#<?php echo h($post['id']) ?>]
                    @<?php echo h($post['name']) ?><br>
                    <?php echo h($post['message']) ?><br>
                    <a href="edit.php?id=<?php echo h($post['id']) ?>">[編集]</a>
                    <a href="delete.php?id=<?php echo h($post['id']) ?>">[削除]</a>
                    <?php echo h($post['updated_at']) ?>
                    <hr>
                </li>
            <?php endforeach ?>
        <?php else : ?>
            投稿されたメッセージはありません
        <?php endif ?>
    </body>
</html>