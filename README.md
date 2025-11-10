## 環境構築
**Dockerビルド**
1. `git clone git@github.com:kamihshi0422/free-market.git`
2. `cd free-market`
3. DockerDesktopアプリを立ち上げる
4. `docker-compose up -d --build`
5. `code .`

**Laravel環境構築**
1. 「.env.example」ファイルを コピーして「.env」を作成し、DBの設定を変更
- `cp src/.env.example src/.env`
``` text
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
- 設定反映 (phpコンテナ内)
- `docker-compose exec php bash`
```bash
cd /var/www
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
```
2. composerのインストール
```bash
mkdir -p bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
composer install
```
3. 画像ディレクトリ準備
- `mv img products_images src/storage/app/public`
- `mkdir src/storage/app/public/user_images`

- `docker-compose exec php bash`
``` bash
php artisan storage:link
```

5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

8. 権限トラブル対応（必要な場合）
```bash
exit
```
- `cd src`
- `sudo chown -R $USER:www-data storage bootstrap/cache`
- `sudo chmod -R 775 storage bootstrap/cache`

**Stripe 設定**
1. https://stripe.com/jp にサインアップ
2. ダッシュボードの「開発者」→「APIキー」から以下を取得
  - 公開可能キー (STRIPE_KEY)
  - シークレットキー (STRIPE_SECRET)
3. 上記で取得したAPIキーを.env に設定
``` text
STRIPE_KEY=pk_test_XXXXXXXXXXXX
STRIPE_SECRET=sk_test_XXXXXXXXXXXX
```

## テスト用環境設定
1. `.env` をコピーして `.env.testing` を作成し、DBやメール設定を変更します。
- `cp src/.env src/.env.testing`
```text
APP_ENV=test

DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root
```
2. テスト用DBの作成
```bash
docker exec -it free-market-mysql-1 bash
mysql -u root -proot
```
- 下記をmysqlコンテナ内で一行ずつ実施します。
```
CREATE DATABASE demo_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON demo_test.* TO 'root'@'%';
FLUSH PRIVILEGES;
EXIT;
```
```bash
exit
```
- `docker-compose exec php bash`
- .env.testingを変更してphpコンテナ内で以下を実施
```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
```
3. テスト専用マイグレーション
``` bash
php artisan migrate:fresh --env=testing
```
2. テスト専用シーディング
``` bash
php artisan db:seed --env=testing
```
3. テスト実行
``` bash
php artisan test tests/Feature --env=testing
```
- または特定のテスト
``` bash
php artisan test tests/Feature/ProductListTest.php
```
## URL
- トップ画面 ：http://localhost/
- 会員登録画面 :http://localhost/register
- phpMyAdmin:：http://localhost:8080/
- MailHog ：http://localhost:8025/

## 追加機能の説明
**コーチの確認・許可のもと、機能を加えています**
- 未承認ユーザーが認証が必要なアクションを行いログインした場合、元の画面に遷移する
  - 例：商品詳細画面で「いいね」後ログイン → 商品詳細画面に戻る
  - 例：マイページボタン → ログイン後マイページに遷移
- 商品出品画面、プロフィール編集、送付先住所変更のエラーメッセージは要件定義にないため独自実装
- 購入済商品の挙動：
  - 詳細画面は表示可能
  - 購入手続きボタンは非表示

## 使用技術(実行環境)
- PHP8.1 (php-fpm)
- Laravel 8.83.8
- MySQL 8.0.26
- nginx 1.21.1
- Docker / Docker Compose

## ER 図
![ER図](./ER.drawio.png)