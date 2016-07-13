# Albums and images test task

Requirements:

- PHP >= 5.6
- NodeJS with npm installed
- Composer

How to install:

1. Unpack release source code and fixtures to some folder, e.g. 'gallery'.
2. Set your database parameters in `app/config/config.yml`
3. Run `composer update`.
4. Run `php app/console assets:install --symlink web`.
5. Run `npm install`.
6. Run `gulp install`.
7. Open '/api/import-fixture' route to import fixtures into database

                                OR

Do it manually by importing `contrib/albums.sql` script.
8. Test application by opening '/' route.

You can run `gulp test-php` to execute PHP unit tests.

Since `app.php` controller is located in `web` folder, your routes will look like `http://domain.com/web/`,
`http://domain.com/web/api/import-fixture`, etc.