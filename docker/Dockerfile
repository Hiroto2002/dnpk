# PHP 8.2 + Apacheの公式イメージを使用
FROM php:8.2-apache

# 必要なPHP拡張をインストール
RUN docker-php-ext-install pdo pdo_mysql

# Apacheのmod_rewriteを有効化
RUN a2enmod rewrite

# 設定ファイルを追加
COPY default.conf /etc/apache2/sites-available/000-default.conf

# 作業ディレクトリを設定
WORKDIR /var/www/html

# ルートにプロジェクト全体をマウント
COPY ../ /var/www/html/

# ポート80を公開
EXPOSE 80

# コンテナ起動時にApacheを実行
CMD ["apache2-foreground"]

