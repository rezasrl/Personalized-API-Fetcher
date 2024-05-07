const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const rename = require('gulp-rename');
const eslint = require('gulp-eslint');
const stylelint = require('gulp-stylelint');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');

function compileAdminStyles() {
    return gulp.src('assets/source/sass/admin/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss([autoprefixer()]))
        .pipe(rename('personalized-api-fetcher-admin.css'))
        .pipe(gulp.dest('assets/css/admin'));
}

function compileFrontendStyles() {
    return gulp.src('assets/source/sass/frontend/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss([autoprefixer()]))
        .pipe(rename('personalized-api-fetcher.css'))
        .pipe(gulp.dest('assets/css/frontend'));
}

function lintJS() {
    return gulp.src(['**/*.js', '!node_modules/**'])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
}

function lintSCSS() {
    return gulp.src('assets/source/sass/**/*.scss')
        .pipe(stylelint({
            failAfterError: true,
            reporters: [{ formatter: 'string', console: true }]
        }));
}

function processJS() {
    return gulp.src('assets/source/js/frontend/*.js')
        .pipe(concat('personalized-api-fetcher.js'))
        .pipe(uglify())
        .pipe(gulp.dest('assets/js/frontend'));
}

function watch() {
    gulp.watch('assets/source/sass/admin/**/*.scss', compileAdminStyles);
    gulp.watch('assets/source/sass/frontend/**/*.scss', compileFrontendStyles);
    gulp.watch(['**/*.js', '!node_modules/**'], lintJS);
    gulp.watch('assets/source/sass/**/*.scss', lintSCSS);
    gulp.watch('assets/source/js/frontend/*.js', processJS);
}

exports.compileAdminStyles = compileAdminStyles;
exports.compileFrontendStyles = compileFrontendStyles;
exports.lintJS = lintJS;
exports.lintSCSS = lintSCSS;
exports.processJS = processJS;
exports.watch = watch;