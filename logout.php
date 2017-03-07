<?php
// http://d.hatena.ne.jp/Kappuccino/20080726/1217049706

session_start();

$_SESSION = array();  // セッション変数を全て解除

if (isset($_COOKIE[session_name()]))
{
    setcookie(session_name(), '', time()-86400, '/bbs/');
}

session_destroy();

header('Location: login.php');
