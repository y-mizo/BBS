#PHPで会員制掲示板基礎

##概要
登録済ユーザーのみが書き込みできる掲示板。
登録ユーザーは全員操作権限を持つ。
* ユーザーの新規登録
* ログイン機能(バリデーション)
* メッセージの投稿・編集・削除

## 要件
* PHP 5.6 以上
* MySQL 5 以上

## インストール方法
```
$ git clone https://github.com/y-mizo/BBS.git
```

## データベースのセットアップ
commands.sqlを参考にデータベースを作成する

## 動作確認
下記URLにアクセスすると、loginページにジャンプする。
新規登録を行い、ログインして確認する。
```
http://YOUR_HOSTNAME/bbs
```