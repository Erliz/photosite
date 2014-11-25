/**
 * Created by Stanislav Vetlovskiy <mrerliz@gmail.com> on 25.11.2014.
 */

var gulp = require('gulp'),
    mainBowerFiles = require('main-bower-files'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    minifyCSS = require('gulp-minify-css'),
    config = {
        js: {
            top: [
                'bower_components/modernizr/modernizr.js'
            ],
            bottom: [
                // index
                'bower_components/jquery/dist/jquery.min.js',
                'bower_components/jquery-ui/jquery-ui.min.js',
                'bower_components/messi/messi.min.js',
                // admin
                'bower_components/jquery-tmpl/jquery-tmpl.min.js',
                'bower_components/bootstrap/dist/js/bootstrap.min.js'
            ]
        },
        css: [
            'bower_components/bootstrap/dist/css/bootstrap.min.css',
            'bower_components/bootstrap/dist/css/bootstrap-theme.min.css',
            'bower_components/messi/messi.min.css'
        ]
    };

gulp.task('build-vendors', function () {
    gulp.src(config.js.bottom)
        .pipe(uglify())
        .pipe(concat('vendors_bottom.js'))
        .pipe(gulp.dest('web/static/js'));

    gulp.src(config.js.top)
        .pipe(uglify())
        .pipe(concat('vendors_top.js'))
        .pipe(gulp.dest('web/static/js'));

    gulp.src(config.css)
        .pipe(minifyCSS({keepBreaks: true}))
        .pipe(concat('vendors.css'))
        .pipe(gulp.dest('web/static/css'));

});


