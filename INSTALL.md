# Albums and images test task

Requirements:

- PHP >= 5.6
- NodeJS with npm and Gulp installed
- Composer

How to install:

1. Unpack release source code and fixtures (`contrib.zip`) archives to same folder.
2. Set your database parameters in `app/config/config.yml`
3. Run `composer update`.
4. Run `php app/console assets:install --symlink web`.
5. Run `npm install`.
6. Run `gulp install`.
7. You can import fixtures either automatically or manually.
  1. If you want to import automatically, open */api/import-fixture* route. 
  2. If you want to import fixtures manually, import `contrib/albums.sql` script to database and copy contents of `contrib/web` folder to `web` folder.
8. Test application by opening */* route.

You can use either `gulp test-php` or `bin\phpunit --coverage-text -c app/phpunit.xml.dist src` to execute PHPUnit tests.

Since `app.php` controller is located in `web` folder, your routes will look like *http://domain.com/web/*,
*http://domain.com/web/api/import-fixture*, etc.
