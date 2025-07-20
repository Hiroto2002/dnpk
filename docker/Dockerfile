FROM php:7.3.5-apache

# 必要なPHP拡張をインストール
RUN docker-php-ext-install pdo pdo_mysql

# 1. aptの参照先をアーカイブに変更し、不要な行を削除
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list && \
    sed -i 's/security.debian.org/archive.debian.org/g' /etc/apt/sources.list && \
    sed -i '/stretch-updates/d' /etc/apt/sources.list

# 2. 競合する可能性のあるBuster等の設定ファイルを削除
RUN rm -f /etc/apt/sources.list.d/*

# 3. GPGキーの検証を無視してパッケージをインストール
RUN apt-get update -o Acquire::Check-Valid-Until=false -o Acquire::Check-Date=false && \
    apt-get install -y --no-install-recommends --allow-unauthenticated \
      zip \
      unzip \
      git \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Apacheのmod_rewriteを有効化
RUN a2enmod rewrite

# Composer をインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

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

