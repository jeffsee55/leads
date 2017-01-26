var gulp = require('gulp');
var browserify = require('browserify');
var sass = require('gulp-sass');
var babelify = require('babelify');
var source = require('vinyl-source-stream');
var uglify = require('gulp-uglify');
var pump = require('pump');
var notify = require('gulp-notify');
var sourcemaps = require('gulp-sourcemaps');


gulp.task('sass', function () {
    return gulp.src('./resources/assets/sass/main.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed',
        }).on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./resources/dist/css'))
        .pipe(notify({
            message: "Gulp sass build complete!"
        }));
});

gulp.task('build', function () {
    browserify({
        entries: ['resources/assets/js/Datepicker.js', 'resources/assets/js/EndlessScroll.js', 'resources/assets/js/Popover.js'],
        extensions: ['.js'],
        debug: true
    })
    .transform(babelify, {presets: ["es2015", "react"]})
    .bundle()
    .pipe(source('bundle.js'))
    .pipe(gulp.dest('resources/dist/js'))
    .pipe(notify({
        message: "Gulp js build complete!"
    }));
});

gulp.task('compress', function (cb) {
    pump([
            gulp.src('resources/dist/js/*.js'),
            uglify(),
            gulp.dest('resources/dist/js/')
        ],
        cb
    )
    .pipe(notify({
        message: "Gulp compress complete!"
    }))
});

gulp.task('apply-prod-environment', function() {
    process.env.NODE_ENV = 'production';
});

gulp.task('production', ['apply-prod-environment', 'compress'], function() {
});

gulp.task('watch', function() {
    gulp.watch(['resources/assets/js/*.js'], ['build']);
    gulp.watch('resources/assets/sass/**/*.scss', ['sass']);
});
