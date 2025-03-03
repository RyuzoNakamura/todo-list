# Todo List <!-- omit in toc -->

todo リストを Laravel で実装しました。

## 目次 <!-- omit in toc -->

- [1. このプロジェクトについて](#1-このプロジェクトについて)
	- [環境](#環境)
- [2. 構成](#2-構成)
	- [2.1. モデル](#21-モデル)
	- [2.2. ビュー](#22-ビュー)
	- [2.3. コントローラ](#23-コントローラ)
- [3. 環境構築](#3-環境構築)
	- [事前準備](#事前準備)
	- [3.3. プロジェクトのセットアップ](#33-プロジェクトのセットアップ)

## 1. このプロジェクトについて

基本的な todo の追加、編集、更新、削除が可能な web アプリです。

todo は日付順にソートされ、次にタスクの優先度で高>中>低の順番でソートされます。

要するに todo の並び順は以下のようになります。

```
- 今日
    - 重要
    - すこし重要
    - 些事
- 明日
    - 重要
    - 些事
```

### 環境

Laravel Sail を使用しています。

```bash
- Laravel: 11.42.1
- PHP: 8.4.4
- Composer: 2.8.6
- MySPL: 8.0.32
- redis: 7.4.2
- Node.js: 22.14.0
- NPM: 11.1.0
```

## 2. 構成

### 2.1. モデル

-   Todo
-   User(breeze で自動的に作られたものです)

### 2.2. ビュー

-   todo
    -   index, edit
-   user
    -   profile(breeze)

### 2.3. コントローラ

-   TodoController
    -   index, store, edit, destroy を提供
    -   チェックボックスのトグル機能を別に実装

## 3. 環境構築

### 事前準備

-   docker desktop のインストール
-   wsl 等の linux 環境 が入っている
    -   mac/linux についてはわからないです

### 3.3. プロジェクトのセットアップ

1. プロジェクトをクローンしていい場所に `cd` します。

2. 以下を実行(コピペ Enter で OK です。)\
   途中「データベース作りますか？」と質問されますが、Yes で Enter して OK です。

```bash
# リポジトリのクローン
git clone https://github.com/RyuzoNakamura/todo-list.git
cd todo-list

# composerが入っているdockerコンテナをインストール
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

# .envファイルの準備
cp .env.example .env

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail artisan migrate:fresh --seed  # データベースをリセットして初期データを追加
./vendor/bin/sail npm run dev
```

3.  http://localhost/ にアクセス

ログインページのユーザー名およびパスワードはそれぞれ

-   ユーザー名

```
test@example.com
```

-   パスワード

```
password
```
