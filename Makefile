# サーバー起動時に TypeScript のウォッチモードも同時に開始
up:
	docker-compose up -d --build
	docker-compose run -d --rm node tsc --watch

# サーバーと TypeScript ビルドの監視を停止
down:
	docker-compose down

# TypeScript の手動ビルド
ts-build:
	docker-compose run --rm node tsc

# TypeScript の監視モード開始（単独実行用）
ts-watch:
	docker-compose run --rm node tsc --watch

# ログ確認
logs:
	docker-compose logs -f

# PHP コンテナに接続
php:
	docker exec -it php-apache bash

# MySQL コンテナに接続
mysql:
	docker exec -it mysql mysql -u root -p

# コンテナの状態を確認
ps:
	docker ps

# すべてのコンテナ・イメージ・ボリュームを削除（注意！）
clean:
	docker-compose down -v
	docker system prune -a -f
