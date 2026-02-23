# products

**Laravel環境構築**

1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
   DB_HOST=mysql
   DB_DATABASE=laravel_db
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_pass

5 ER図形は以下URLに添付
https://docs.google.com/spreadsheets/d/10bp7dVOadVNT32Nx7OH0xThtpKbl3GowxrHL22ON_ak/edit?gid=1988632272#gid=1988632272

6マイグレーションの実行
php artisan migrate

7シーディングの実行
php artisan db:seed

8Dockerビルド
・git clone git@github.com:coachtech-material/php.git
・docker-compose up -d --build

9 開発閑居
商品一覧
http://localhost/products
