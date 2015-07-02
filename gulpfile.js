/*
 npm install --save-dev gulp
 npm install gulp-ruby-sass gulp-sass gulp-watch gulp-jshint gulp-plumber gulp-autoprefixer gulp-include gulp-minify-css gulp-jshint gulp-concat gulp-uglify gulp-imagemin gulp-notify gulp-rename gulp-livereload gulp-cache del jshint-stylish --save-dev
 */

var gulp = require('gulp'),
  plumber = require('gulp-plumber'),
  watch = require('gulp-watch'),
  livereload = require('gulp-livereload'),
  minifycss = require('gulp-minify-css'),
  concat = require('gulp-concat'),
  jshint = require('gulp-jshint'),
  notify = require('gulp-notify'),
  include = require('gulp-include'),
  autoprefixer = require('gulp-autoprefixer'),
  imagemin = require('gulp-imagemin'),
  stylish = require('jshint-stylish'),
  uglify = require('gulp-uglify'),
  rename = require('gulp-rename'),
  sass = require('gulp-sass');

var onError = function (err) {
  console.log('An error occurred:', err.message);
  this.emit('end');
};

// Lint Task
gulp.task('lint', function () {
  return gulp.src('./assets/js/components/*.js')
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('jshint-stylish'));
});

gulp.task('scss', function () {
  return gulp.src('./assets/css/scss/project.scss')
    .pipe(plumber({errorHandler: onError}))
    .pipe(sass())
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(rename('all.css'))
    .pipe(gulp.dest('./assets/css/'))
    .pipe(minifycss())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('./assets/css/'))
    .pipe(livereload());
});

gulp.task('admin-scss', function () {
  return gulp.src('./assets/css/scss/project-admin.scss')
    .pipe(plumber({errorHandler: onError}))
    .pipe(sass())
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(rename('all-admin.css'))
    .pipe(gulp.dest('./assets/css/'))
    .pipe(minifycss())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('./assets/css/'))
    .pipe(livereload());
});


// Compile Scripts
gulp.task('scripts', function () {
  return gulp.src('./assets/js/components/*.js')
    .pipe(concat('all.js'))
    .pipe(gulp.dest('./assets/js/'))
    .pipe(rename('all.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./assets/js/'));
});

gulp.task('images', function () {
  return gulp.src('./assets/images/original/*')
    .pipe(imagemin({optimizationLevel: 3, progressive: true, interlaced: true}))
    .pipe(gulp.dest('./assets/images/'));
  //.pipe(notify({ message: 'Images task complete' }));
});

gulp.task('watch', function () {
  livereload.listen();
  gulp.watch('./assets/css/scss/*.scss', ['scss']);
  gulp.watch('./assets/css/scss/partials/*.scss', ['scss']);
  gulp.watch('./assets/js/components/*.js', ['lint', 'scripts']);
  gulp.watch('./*.php').on('change', function (file) {
    livereload.changed(file);
  });
  gulp.watch('./partials/*.php').on('change', function (file) {
    livereload.changed(file);
  });
});

gulp.task('default', ['lint', 'scss', 'admin-scss'], function () {
});