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

```bash
# リポジトリのクローン
git clone https://github.com/RyuzoNakamura/todo-list.git
cd todo-list

# .envファイルの準備
cp .env.example .env

# Sailのインストールと起動
composer require laravel/sail --dev
php artisan sail:install
./vendor/bin/sail up -d

# 依存パッケージのインストール
sail composer install
sail npm install

# マイグレーションの実行
sail artisan migrate

# フロントエンドのビルド
sail npm run dev
```
