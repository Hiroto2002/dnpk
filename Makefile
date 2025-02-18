# サーバーと TypeScript を同時に起動
up:
	docker-compose up -d --build
	docker-compose run -d --rm node npm run watch --prefix /usr/src/app/frontend

# TypeScript の手動ビルド
ts-build:
	docker-compose run --rm node npm run build --prefix /usr/src/app/frontend

# TypeScript の監視モード開始（手動実行用）
ts-watch:
	docker-compose run --rm node npm run watch --prefix /usr/src/app/frontend

# サーバーと TypeScript ビルドの監視を停止
down:
	docker-compose down

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
