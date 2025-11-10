## 環境構築
**Dockerビルド**
1. `git clone git@github.com:kamihshi0422/free-market.git`
2. `cd free-market`
3. DockerDesktopアプリを立ち上げる
4. `docker-compose up -d --build`
5. `code .`

**Laravel環境構築**
1. 「.env.example」ファイルを コピーして「.env」を作成し、DBの設定を変更
`cp src/.env.example src/.env`
``` text
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

3. 商品画像の移動と、画像保存用ファイルの作成・紐づけ・権限付与
`mv img products_images src/storage/app/public`
`mkdir src/storage/app/public/user_images`
`docker-compose exec php bash`
``` bash
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
php artisan storage:link
```

4. `composer install`

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

8.　（  エラーが出る場合）
`cd src`
`sudo chown -R $USER:www-data storage bootstrap/cache`
`sudo chmod -R 775 storage bootstrap/cache`

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

## 使用技術(実行環境)
- PHP8.1 (php-fpm)
- Laravel 8.83.8
- MySQL 8.0.26
- nginx 1.21.1
- Docker / Docker Compose

## ER 図
![ER図](./ER.drawio.png)

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

## テスト用環境設定
1. `.env` をコピーして `.env.testing` を作成し、DBやメール設定を変更します。
`cp .env.example .env.testing`
```text
APP_ENV=test

DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root
```
2. テスト用DBの作成
```bash
docker exec -it free-market-mysql-1 bash
mysql -u root -p
```
- パスワードはrootと入力してenter。
- 下記をmysqlコンテナ内で一行ずつ実施します。
```
CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON　demo_test.* TO 'root'@'%';
FLUSH PRIVILEGES;
EXIT;
```
`docker-compose exec php bash`

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