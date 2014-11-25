/**
 * Created by Stanislav Vetlovskiy <mrerliz@gmail.com> on 25.11.2014.
 */

var gulp = require('gulp'),
    mainBowerFiles = require('main-bower-files'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify');



var jsTopFiles = [
    'bower_components/modernizr/modernizr.js'
];

var jsBottomFiles = [
    'bower_components/jquery/dist/jquery.min.js',
    'bower_components/jquery-ui/jquery-ui.min.js',
    'bower_components/jquery-tmpl/jquery-tmpl.min.js',
    'bower_components/messi/messi.min.js'
];

gulp.task('build-vendors', function() {
    gulp.src(jsBottomFiles)
        .pipe(uglify())
        .pipe(concat('vendors.js'))
        .pipe(gulp.dest('web/static/js'));

    gulp.src(jsTopFiles)
        .pipe(uglify())
        .pipe(concat('top.js'))
        .pipe(gulp.dest('web/static/js'));
});
