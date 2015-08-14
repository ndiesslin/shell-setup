'use strict';

var gulp = require('gulp'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    coffee = require('gulp-coffee');

var mainDir = './wp-content/themes/Divi-child/'

gulp.task('sass', function () {
  gulp.src('./wp-content/themes/Divi-child/sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({outputStyle: 'compressed'}))
    .pipe(gulp.dest('./wp-content/themes/Divi-child/'));
});

gulp.task('coffee', function() {
  gulp.src(mainDir + 'scripts/coffee/*.coffee')
    .pipe(coffee({bare: true}).on('error', gutil.log))
    .pipe(gulp.dest(mainDir + 'scripts'))
});

gulp.task('scripts', function() {
  var scriptSrc = "./wp-content/themes/Divi-child/scripts/"
  return gulp.src([scriptSrc + 'custom.js', scriptSrc + 'plugin-ex.js', scriptSrc + 'scripts.js', scriptSrc + 'nav.js' ])
    .pipe(concat('./all.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./wp-content/themes/Divi-child/js/'));
});

// All watch tasks
gulp.task('watch', function () {
  gulp.watch('./wp-content/themes/Divi-child/sass/**/*.scss', ['sass']);
  gulp.watch((mainDir + 'scripts/coffee/*.coffee'), ['coffee']);
  gulp.watch('./wp-content/themes/Divi-child/scripts/**/*.js', ['scripts']);
});

gulp.task('default', ['sass', 'coffee', 'scripts', 'watch']);
