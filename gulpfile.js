var gulp = require('gulp');
var shell = require('gulp-shell');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var coffee = require('gulp-coffee');
var gutil = require('gutil');

gulp.task('default', function() {});

gulp.task('install', [
    'install-backbone',
    'install-bootstrap',
    'install-marionette',
    'install-masonry',
    'install-jquery',
    'install-underscore',

    'install-app'
], function() {});

gulp.task('test-php', shell.task('bin\\phpunit --coverage-text -c app/phpunit.xml.dist src'));

gulp.task('install-backbone', function() {
    gulp.src('node_modules/backbone/**')
        .pipe(gulp.dest('web/bundles/backbone'));
});

gulp.task('install-bootstrap', function() {
    gulp.src('node_modules/bootstrap/dist/**')
        .pipe(gulp.dest('web/bundles/bootstrap'));
});

gulp.task('install-marionette', function() {
    gulp.src('node_modules/backbone.marionette/lib/**')
        .pipe(gulp.dest('web/bundles/marionette'));
});

gulp.task('install-masonry', function() {
    gulp.src('node_modules/masonry-layout/dist/**')
        .pipe(gulp.dest('web/bundles/masonry'));
});

gulp.task('install-jquery', function() {
    gulp.src('node_modules/jquery/dist/**')
        .pipe(gulp.dest('web/bundles/jquery'));
});

gulp.task('install-underscore', function() {
    gulp.src('node_modules/underscore/**')
        .pipe(gulp.dest('web/bundles/underscore'));
});

gulp.task('install-app', ['compile-sass-app', 'compile-coffee-app'], function() {});

gulp.task('compile-coffee-app', function() {
    gulp.src('src/AppBundle/Resources/public/**/*.coffee')
        .pipe(sourcemaps.init())
        .pipe(coffee({bare: true}).on('error', gutil.log))
        .pipe(uglify())
        .pipe(rename({extname: ".min.js"}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/bundles/app'));
});

gulp.task('compile-sass-app', function() {
    gulp.src('src/AppBundle/Resources/public/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(rename({extname: ".min.css"}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/bundles/app'));
});

gulp.task('app:watch', function () {
    gulp.watch('src/AppBundle/Resources/public/**/*.coffee', ['compile-coffee-app']);
    gulp.watch('src/AppBundle/Resources/public/**/*.scss', ['compile-sass-app']);
});