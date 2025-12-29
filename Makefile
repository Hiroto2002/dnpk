# サーバーと TypeScript を同時に起動
REACT_WATCH_PID := .react_build_watch.pid
REACT_WATCH_LOG := react_build_watch.log

up:
	docker compose up -d --build
	docker compose run -d --rm node npm run watch --prefix /usr/src/app/frontend
	@if [ -f $(REACT_WATCH_PID) ] && kill -0 `cat $(REACT_WATCH_PID)` 2>/dev/null; then \
		echo "React build watcher already running (PID `cat $(REACT_WATCH_PID)`)"; \
	else \
		echo "Starting React build watcher..."; \
		(pnpm --dir frontend/my-react-app run build:watch > $(REACT_WATCH_LOG) 2>&1 & echo $$! > $(REACT_WATCH_PID)); \
		echo "React build watcher started (PID `cat $(REACT_WATCH_PID)`). Logs: $(REACT_WATCH_LOG)"; \
	fi

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
	@if [ -f $(REACT_WATCH_PID) ]; then \
		echo "Stopping React build watcher..."; \
		kill `cat $(REACT_WATCH_PID)` 2>/dev/null || true; \
		rm -f $(REACT_WATCH_PID); \
	fi

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
