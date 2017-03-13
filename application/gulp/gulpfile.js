// Environment
try {
	var DS = /^win/.test(process.platform) ? '\\' : '/',
		base = __dirname.split(DS);
	base.length -= 2;
	base = base.join(DS);
	var src = __dirname + DS + 'src' + DS,
		dest = base + DS + 'htdocs' + DS + 'assets' + DS;
}
catch(e){
	var DS = '/',
		src = 'src/',
		dest = '../../htdocs/assets/';
}

// Include gulp
var gulp = require('gulp');
// Include plugins
var plumber = require('gulp-plumber'),
	sass = require('gulp-sass'),
	concat = require('gulp-concat'),
	rename = require('gulp-rename'),
	autoprefixer = require('gulp-autoprefixer'),
	cssnano = require('gulp-cssnano'),
	uglify = require('gulp-uglify');

// Compile Our Sass
gulp.task('css', function() {
	var files = src + 'sass' + DS + '*.scss';
	console.log('css files = ' + files);
	console.log('css dest = ' + dest);
	return gulp.src(files)
		// error handling
		.pipe(plumber())
		// source
		.pipe(sass({
			outputStyle: 'expanded',
			sourceComments: true
		}))
		// autoprefixer
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		.pipe(gulp.dest(dest))
		// minify-css
		.pipe(rename(function (path) {path.basename += '.min'}))
		.pipe(cssnano())
		// output
		.pipe(gulp.dest(dest));
});

gulp.task('js', function () {
	var files = [src + 'scripts' + DS + 'polyfill.js', src + 'scripts' + DS + 'jquery-1.12.0.js', src + 'scripts' + DS + '*.js'];
	console.log('js files = ' + files.join(', '));
	console.log('js dest = ' + dest);
	return gulp.src(files)
		// error handling
		.pipe(plumber())
		// combine
		.pipe(concat('main.js'))
		.pipe(gulp.dest(dest))
		// minify
		.pipe(uglify())
		.pipe(rename(function (path) {path.basename += '.min'}))
		// output
		.pipe(gulp.dest(dest));
});

// Default Task
gulp.task('default', ['css','js','watch']);

gulp.task('watch', function() {
  return gulp
	// Watch the input folder for change,
	// and run `sass` task when something happens
	.watch(src + 'sass' + DS + '**' + DS + '*.scss', ['css'])
	// When there is a change,
	// log a message in the console
	.on('change', function(event) {
	  console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
	});
});
