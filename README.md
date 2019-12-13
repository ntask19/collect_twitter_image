# Twitterで検索した画像を自動DLするツール

対象の画像を過去7日間に渡って自動でDLしてくれます。

## 準備

TwitterOAuthとdotenvのインストール

```
composer install
```

.envファイルにTwitterのAPIキー情報を記載する。APIキーの必要情報は[こちら](https://developer.twitter.com/apps)で確認できる。

```
cp .env.example .env
```

## ダウンロードの実行

```
php exec_dl.php ハリネズミ
```
