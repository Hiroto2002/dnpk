# Repository Guidelines

## プロジェクト構成（Project Structure）

- PHP バックエンドはリポジトリ直下（例: `index.php`, `order.php`, `DbManager.php`）。ドメインモデルは PSR-4 に従い `Domain/Models/`。
- PHP テストは `tests/`（例: `AmountTest.php`）。
- TypeScript ユーティリティは `frontend/`、React アプリは `frontend/my-react-app/`。
- 静的資産は `css/` や各 `public/`、Docker は `docker/`、DB 初期化は `init.sql/`。
- 本番環境は xammp に apache と php 7 が入っている

## ビルド・実行・開発（Build/Test/Dev）

- `make up`: Docker で PHP/MySQL/Node ウォッチャーを起動（`http://localhost:8081`）。
- `make setup/mysql`: DB `dnpk_dnpk_oes` を作成し `init.sql/` を流し込み。
- `make down` / `make logs`: スタック停止 / ログの追跡。
- `make ts-build` / `make ts-watch`: `frontend/` の TypeScript をビルド/監視。
- `make test/php`: `php-apache` コンテナ内で PHPUnit 実行。
- React: `cd frontend/my-react-app && pnpm dev`（または `npm run dev`）、`pnpm run build` で `dist/` 生成。
- React のビルド成果物は `react_app.php` から配信。`pnpm run build`（または `make build/front`）実行後に `http://localhost:8081/react_app.php` を開くと、`frontend/my-react-app/dist/index.html` を PHP で読み込んだ SPA が表示される。

## コーディング規約（Style & Naming）

- TS/JS: 2 スペース。React アプリは ESLint 同梱（`pnpm lint`）。
- 命名: ルートの PHP はスネーク/ローワー、React コンポーネントは `PascalCase.tsx`、テストは `*Test.php` / `*.test.ts`。
- 最低限のコードで可読性を確保。冗長なコメントは避け、自己説明的な命名を優先。
- 極力関数型プログラミングを意識し、副作用を最小限に。
- 極力分割統治を意識し、単一責任原則に従う。
- コードの重複を避け、再利用性を高める。

## テスト指針（Testing）

- PHP: PHPUnit 9 系。`tests/` に配置し `*Test.php` 命名。`make test/php` で実行。
- TS: `frontend/` で Jest（ts-jest）。`*.test.ts` 命名、`npm test` を実行。
- AAA（Arrange/Act/Assert）を意識し、最小限のフィクスチャを併置。

## コミット/PR（Commits & PRs）

- コミットは履歴に倣い Conventional Commits（例: `feat:`, `fix:`, `refactor:`）。本文は命令形・範囲を明確に。
- PR: 目的、関連 Issue（例: `Closes #123`）、確認手順、UI 変更はスクリーンショット。ローカルでビルド/テストを通過させること。

## セキュリティ/設定（Security & Config）

- `.env` は Docker で読み込み。秘密情報はコミットしない。共有時は認証情報をローテート。
- MySQL データはボリュームに永続化。状態初期化が必要な場合のみ `make clean` を使用。

## 指針

- 日本語で答えること
- php からフロント部分を frontend/my-react-app に移行中
- php のコードは三層アーキテクチャとドメインモデルを使ったデザインパターンに移行予定
- TDD による開発を前提としている
- frontend に React を置き、build 成果物を php ファイルで読み込む形で SPA として動作させる
