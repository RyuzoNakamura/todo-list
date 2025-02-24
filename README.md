# Todo List <!-- omit in toc -->

todo リストを Laravel で実装しました。

## 目次 <!-- omit in toc -->

- [1.1. 前提条件](#11-前提条件)
	- [1.1.1. Windows 環境の場合](#111-windows-環境の場合)
	- [1.1.2. Linux/Mac の場合](#112-linuxmac-の場合)
- [1.2. 開発環境のセットアップ手順](#12-開発環境のセットアップ手順)

## 1.1. 前提条件

### 1.1.1. Windows 環境の場合

1. WSL2 のインストール

```bash
# PowerShellを管理者として実行
wsl --install
```

2. Docker Desktop のインストール

-   [Docker Desktop for Windows](https://www.docker.com/products/docker-desktop/)からインストーラーをダウンロード
-   インストール時に「Use WSL 2 instead of Hyper-V」オプションを選択

3. VS Code の設定

-   [Remote Development](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.vscode-remote-extensionpack)拡張機能をインストール
-   WSL2 上でプロジェクトを開く

### 1.1.2. Linux/Mac の場合

-   Docker のインストール
-   Docker Compose のインストール

## 1.2. 開発環境のセットアップ手順

全て wsl2 環境で行う前提です。

1. プロジェクトをクローンしていい場所に `cd` します。

2. 以下を実行

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

```

3. .env ファイルを開き、以下のように修正

```.env
# DB_HOST = 127.0.0.1
↓
DB_HOST = sqlite
```

4. ターミナルで以下を実行

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail nepm run dev
```

5. http://localhost/ にアクセス
