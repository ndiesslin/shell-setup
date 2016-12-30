'use strict';

var gulp = require('gulp'),
    browserSync = require('browser-sync').create(),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    autoprefixer = require('gulp-autoprefixer'),
    coffee = require('gulp-coffee');

var mainDir = './wp-content/themes/Divi-child/'

// Static Server + watching scss/html files
gulp.task('browser-sync', function() {
  // var files = [
  // './*.css',
  // './*.php'
  // ];

  // browserSync.init({
  // proxy: "localhost/minneapolis-heart-institute-foundation",
  // notify: false
  // });
});


gulp.task('sass', function () {
  gulp.src('./wp-content/themes/Divi-child/sass/**/*.scss')
    .pipe(sass({
      // Install Susy Grids
      includePaths: [
        'node_modules/susy/sass'
      ],
      outputStyle: 'nested'
    }).on('error', sass.logError))
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
  // var scriptSrc = "./wp-content/themes/Divi-child/scripts/"
  // return gulp.src([scriptSrc + 'custom.js', scriptSrc + 'plugin-ex.js', scriptSrc + 'scripts.js', scriptSrc + 'nav.js', scriptSrc + 'large-hover.js' ])
  //   .pipe(concat('./all.js'))
  //   .pipe(uglify())
  //   .pipe(gulp.dest('./wp-content/themes/Divi-child/js/'));
});

// All watch tasks
gulp.task('watch', function () {
  gulp.watch('./wp-content/themes/Divi-child/sass/**/*.scss', ['sass']);
  gulp.watch((mainDir + 'scripts/coffee/*.coffee'), ['coffee']);
  //gulp.watch('./wp-content/themes/Divi-child/scripts/**/*.js', ['scripts']);
});

gulp.task('default', ['sass', 'coffee', 'scripts', 'watch', 'browser-sync']);
