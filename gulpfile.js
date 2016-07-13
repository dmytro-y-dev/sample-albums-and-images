var gulp = require('gulp');
var shell = require('gulp-shell');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var coffee = require('gulp-coffee');
var gutil = require('gutil');
var concat = require('gulp-concat');

gulp.task('default', function() {});

gulp.task('install', ['install-deps', 'install-app'], function() {});

gulp.task('install-deps', [
        'install-backbone',
        'install-bootstrap',
        'install-marionette',
        'install-masonry',
        'install-jquery',
        'install-underscore',
        'install-imagesloaded'
], function() {
    // Concat dependencies into single file
    gulp.src([
        'web/bundles/jquery/jquery.min.js',
        'web/bundles/underscore/underscore-min.js',
        'web/bundles/backbone/backbone-min.js',
        'web/bundles/marionette/backbone.marionette.min.js',
        'web/bundles/bootstrap/js/bootstrap.min.js',
        'web/bundles/masonry/masonry.pkgd.min.js',
        'web/bundles/imagesloaded/imagesloaded.pkgd.min.js',
        'web/bundles/fosjsrouting/js/router.js'
    ])
        .pipe(sourcemaps.init())
        .pipe(concat({path: 'deps.min.js'}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/bundles/app/js'));
});

gulp.task('test-php', shell.task('bin\\phpunit --coverage-text -c app/phpunit.xml.dist src', {}));

gulp.task('install-backbone', function() {
    return gulp.src('node_modules/backbone/**')
        .pipe(gulp.dest('web/bundles/backbone'));
});

gulp.task('install-bootstrap', function() {
    return gulp.src('node_modules/bootstrap/dist/**')
        .pipe(gulp.dest('web/bundles/bootstrap'));
});

gulp.task('install-marionette', function() {
    return gulp.src('node_modules/backbone.marionette/lib/**')
        .pipe(gulp.dest('web/bundles/marionette'));
});

gulp.task('install-masonry', function() {
    return gulp.src('node_modules/masonry-layout/dist/**')
        .pipe(gulp.dest('web/bundles/masonry'));
});

gulp.task('install-jquery', function() {
    return gulp.src('node_modules/jquery/dist/**')
        .pipe(gulp.dest('web/bundles/jquery'));
});

gulp.task('install-underscore', function() {
    return gulp.src('node_modules/underscore/**')
        .pipe(gulp.dest('web/bundles/underscore'));
});

gulp.task('install-imagesloaded', function() {
    return gulp.src('node_modules/imagesloaded/imagesloaded*')
        .pipe(gulp.dest('web/bundles/imagesloaded'));
});

gulp.task('install-app', ['compile-sass-app', 'compile-coffee-app'], function() {});

gulp.task('compile-coffee-app', function() {
    gulp.src('src/AppBundle/Resources/coffee/*.coffee')
        .pipe(sourcemaps.init())
        .pipe(concat({path: 'app.min.js'}))
        .pipe(coffee({bare: true}).on('error', gutil.log))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/bundles/app/js'));
});

gulp.task('compile-sass-app', function() {
    gulp.src('src/AppBundle/Resources/scss/*.scss')
        .pipe(sourcemaps.init())
        .pipe(concat({path: 'app.min.css'}))
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/bundles/app/css'));
});

gulp.task('app:watch', function () {
    gulp.watch('src/AppBundle/Resources/coffee/*.coffee', ['compile-coffee-app']);
    gulp.watch('src/AppBundle/Resources/scss/*.scss', ['compile-sass-app']);
});