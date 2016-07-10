var gulp = require('gulp');
var shell = require('gulp-shell');

gulp.task('default', function() {
  // place code for your default task here
});

gulp.task('test-php', shell.task('bin\\phpunit --coverage-text -c app/phpunit.xml.dist src'));