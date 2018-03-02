'use strict';

var gulp = require('gulp'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    autoprefixer = require('gulp-autoprefixer'),
    coffee = require('gulp-coffee'),
    sourcemaps = require('gulp-sourcemaps');

var mainDir = './wp-content/themes/Divi-child/'

gulp.task('sass', function () {
  return gulp.src('./wp-content/themes/Divi-child/sass/**/*.scss')
  .pipe(sourcemaps.init())
  .pipe(sass().on('error', sass.logError))
  .pipe(sourcemaps.write())
  .pipe(autoprefixer({
    browsers: ['last 8 versions'],
    cascade: false
  }))
  .pipe(gulp.dest('./wp-content/themes/Divi-child/'));
 });

gulp.task('coffee', function() {
  gulp.src(mainDir + 'scripts/coffee/*.coffee')
    .pipe(coffee({bare: true}).on('error', gutil.log))
    .pipe(gulp.dest(mainDir + 'scripts'))
});

gulp.task('scripts', function() {
  var scriptSrc = "./wp-content/themes/Divi-child/scripts/"
  return gulp.src([
      scriptSrc + 'page.js',
      scriptSrc + 'equal-height.js',
      scriptSrc + 'breadcrumb.js',
      scriptSrc + 'group-toggle.js',
      scriptSrc + 'nav.js',
      scriptSrc + 'large-hover.js',
      scriptSrc + 'toggle.js'
    ])
    .pipe(concat('./all.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./wp-content/themes/Divi-child/js/'));
});

// All watch tasks
gulp.task('watch', function () {
  gulp.watch('./wp-content/themes/Divi-child/sass/**/*.scss', ['sass']);
  gulp.watch((mainDir + 'scripts/coffee/*.coffee'), ['coffee']);
  //gulp.watch('./wp-content/themes/Divi-child/scripts/**/*.js', ['scripts']);
});

gulp.task('default', ['sass', 'coffee', 'scripts', 'watch']);
