const gulp = require( 'gulp' );
const sass = require( 'gulp-sass')(require( 'sass' ) );
const postcss = require( 'gulp-postcss' );
const autoprefixer = require( 'autoprefixer' );
const rename = require( 'gulp-rename' );
const concat = require( 'gulp-concat' );
const uglify = require( 'gulp-uglify' );

function compileAdminStyles() {
	return gulp.src( 'assets/source/sass/admin/*.scss' )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( postcss( [ autoprefixer() ] ) )
		.pipe( rename( 'personalized-api-fetcher-admin.css' ) )
		.pipe( gulp.dest( 'assets/css/admin' ) );
}

function compileFrontendStyles() {
	return gulp.src( 'assets/source/sass/frontend/*.scss' )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( postcss( [ autoprefixer() ] ) )
		.pipe( rename( 'personalized-api-fetcher.css' ) )
		.pipe( gulp.dest( 'assets/css/frontend' ) );
}

function processJS() {
	return gulp.src( 'assets/source/js/frontend/*.js' )
		.pipe( concat( 'personalized-api-fetcher.js' ) )
		.pipe( uglify() )
		.pipe( gulp.dest( 'assets/js/frontend' ) );
}

function watch() {
	gulp.watch( 'assets/source/sass/admin/**/*.scss', compileAdminStyles );
	gulp.watch( 'assets/source/sass/frontend/**/*.scss', compileFrontendStyles );
	gulp.watch( 'assets/source/js/frontend/*.js', processJS );
}

exports.compileAdminStyles = compileAdminStyles;
exports.compileFrontendStyles = compileFrontendStyles;
exports.processJS = processJS;
exports.watch = watch;