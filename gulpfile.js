'use strict';

var gulp = require('gulp'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    minifyCss = require('gulp-minify-css'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    coffee = require('gulp-coffee');

var mainDir = './wp-content/themes/Divi-child/'

gulp.task('sass', function () {
  gulp.src('./wp-content/themes/Divi-child/sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./wp-content/themes/Divi-child/compiled-styles'));
});

gulp.task('minify-css', function() {
  return gulp.src('./wp-content/themes/Divi-child/compiled-styles/*.css')
    .pipe(minifyCss({compatibility: 'ie8'}))
    .pipe(gulp.dest('./wp-content/themes/Divi-child/'));
});

gulp.task('coffee', function() {
  gulp.src(mainDir + 'scripts/coffee/*.coffee')
    .pipe(coffee({bare: true}).on('error', gutil.log))
    .pipe(gulp.dest(mainDir + 'scripts'))
});

gulp.task('scripts', function() {
  var scriptSrc = "./wp-content/themes/Divi-child/scripts/"
  return gulp.src([ scriptSrc + 'plugin-ex.js', scriptSrc + 'scripts.js', scriptSrc + 'nav.js' ])
    .pipe(concat('./all.js'))
    .pipe(gulp.dest('./wp-content/themes/Divi-child/compiled-scripts'));
});

gulp.task('js-compress', function() {
  return gulp.src('./wp-content/themes/Divi-child/compiled-scripts/all.js')
    .pipe(uglify())
    .pipe(gulp.dest('./wp-content/themes/Divi-child/js/'));
});

// All watch tasks
gulp.task('watch', function () {
  gulp.watch('./wp-content/themes/Divi-child/sass/**/*.scss', ['sass', 'minify-css']);
  gulp.watch((mainDir + 'scripts/coffee/*.coffee'), ['coffee']);
  gulp.watch('./wp-content/themes/Divi-child/scripts/**/*.js', ['scripts']);
  gulp.watch('./wp-content/themes/Divi-child/compiled-scripts/all.js', ['js-compress']);
});

gulp.task('default', ['sass', 'minify-css', 'coffee', 'scripts', 'js-compress', 'watch']);
