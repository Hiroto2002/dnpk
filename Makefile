# サーバーと TypeScript を同時に起動
up:
	docker compose up -d --build
	docker compose run -d --rm node npm run watch --prefix /usr/src/app/frontend

setup/mysql:
	docker exec -it mysql mysql -uroot -ppassword -e "CREATE DATABASE IF NOT EXISTS dnpk_dnpk_oes;"
	docker exec -i mysql mysql -uroot -ppassword dnpk_dnpk_oes < ./init.sql/dnpk_dnpk_oes.sql

# TypeScript の手動ビルド
ts-build:
	docker compose run --rm node npm run build --prefix /usr/src/app/frontend

# TypeScript の監視モード開始（手動実行用）
ts-watch:
	docker compose run --rm node npm run watch --prefix /usr/src/app/frontend
build/front:
	cd frontend/my-react-app && pnpm run build
build/front_watch:
	cd frontend/my-react-app && pnpm run build:watch

# サーバーと TypeScript ビルドの監視を停止
down:
	docker compose down

# ログ確認
logs:
	docker compose logs -f

# PHP コンテナに接続
php:
	docker exec -it php-apache bash

test/php:
	docker exec -it php-apache bash -c "composer dump-autoload && ./vendor/bin/phpunit"

# MySQL コンテナに接続
mysql:
	docker exec -it mysql mysql -u root -p

# コンテナの状態を確認
ps:
	docker ps

# すべてのコンテナ・イメージ・ボリュームを削除（注意！）
clean:
	docker compose down -v
	docker system prune -a -f
