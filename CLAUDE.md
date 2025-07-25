# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 概要

このプロジェクトは、Laravel 12 + React + Inertia.js + TypeScript + Tailwind CSS で構築されたショッピングリスト管理アプリケーションです。ユーザーがアカウントを作成し、ショッピングリストとアイテムを管理できます。

## 開発コマンド

### 環境構築
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
```

### 開発サーバー起動
```bash
composer run dev  # PHPサーバー、キュー、ログ、Viteを同時起動
```

または個別に起動:
```bash
php artisan serve
npm run dev
```

### テスト実行
```bash
composer run test
php artisan test
```

### コード品質チェック
```bash
npm run lint        # ESLint（自動修正）
npm run format      # Prettier（フォーマット）
npm run types       # TypeScript型チェック
vendor/bin/pint     # Laravel Pint（PHP）
```

### ビルド
```bash
npm run build       # 本番用ビルド
npm run build:ssr   # SSR対応ビルド
```

## アーキテクチャ

### データベース構造
- **users**: ユーザー情報
- **shopping_lists**: ショッピングリスト（user_idで所有者を管理）
- **items**: アイテム情報（user_idで作成者を管理）
- **item_shopping_list**: アイテムとショッピングリストの多対多中間テーブル（quantity, is_checked属性付き）

### モデル関係
- User hasMany ShoppingList
- User hasMany Item
- ShoppingList belongsToMany Item (with pivot: quantity, is_checked)
- Item belongsToMany ShoppingList

### フロントエンド構造
- **pages/**: Inertia.jsページコンポーネント
- **layouts/**: レイアウトコンポーネント（app-layout, auth-layout, settings-layout）
- **components/**: 再利用可能なUIコンポーネント
- **types/**: TypeScript型定義

### 主要機能
- ユーザー認証（Laravel Breeze）
- ショッピングリストのCRUD操作
- アイテムのCRUD操作
- アイテムをショッピングリストに追加（数量・チェック状態管理）
- ダークモード対応

## 技術スタック

### バックエンド
- Laravel 12（PHP 8.2+）
- Inertia.js（SPA体験）
- SQLite（開発用）

### フロントエンド
- React 19
- TypeScript
- Tailwind CSS 4
- Radix UI（UIコンポーネント）
- Lucide React（アイコン）

### 開発ツール
- Vite（ビルドツール）
- ESLint + Prettier（コード品質）
- Laravel Pint（PHPフォーマッター）
- Pest（PHPテストフレームワーク）

## 注意事項

### 認証
- 全ての主要機能は認証が必要（auth, verifiedミドルウェア）
- ユーザーは自分が作成したリソースのみアクセス可能

### データの一貫性
- アイテムは複数のショッピングリストで再利用可能
- アイテム作成時は`firstOrCreate`を使用して重複を防止
- ショッピングリスト更新時は`sync`を使用してピボットデータを管理

### TypeScript型定義
- 全ての型定義は`resources/js/types/`に配置
- サーバーサイドの構造と一致させる必要がある