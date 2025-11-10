## 環境構築
**Dockerビルド**
1. `git clone git@github.com:kamihshi0422/free-market.git`
cd free-market
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを コピーして「.env」を作成し、DBの設定を変更
```
cp .env.example .env
```
``` text
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
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

cd src
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

git にまとめてあげる
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg
https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg

**Stripe 設定**
1. https://stripe.com/jp にサインアップ
2. ダッシュボードの「開発者」→「APIキー」から以下を取得
  - 公開可能キー (STRIPE_KEY)
  - シークレットキー (STRIPE_SECRET)
3. .env に設定
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
`.env.example` をコピーして `.env.testing` を作成し、DBやメール設定を変更します。
```bash
cp .env.example .env.testing
```
```
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_FROM_NAME="Laravel Test"

ダッシュボードの「開発者」→「APIキー」から以下を取得
STRIPE_KEY=pk_test_XXXXXXXXXXXX
STRIPE_SECRET=sk_test_XXXXXXXXXXXX
```
1. テスト専用マイグレーション
``` bash
php artisan migrate:fresh --env=testing
```
2. テスト専用シーディング
``` bash
php artisan db:seed --env=testing
```
3. テスト実行
``` bash
php artisan test --env=testing


- または特定のテスト
php artisan test tests/Feature/ProductListTest.php
```