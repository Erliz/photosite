/**
 * Created by Stanislav Vetlovskiy <mrerliz@gmail.com> on 25.11.2014.
 */

var gulp = require('gulp'),
    concat = require('gulp-concat'),
    stylus = require('gulp-stylus'),
    uglify = require('gulp-uglify'),
    minifyCSS = require('gulp-minify-css'),

    appResourcesPath = 'src/Erliz/PhotoSite/Resources/public',
    config = {
        vendor: {
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
        },
        app: {
            admin: {
                js: {
                    top: [],
                    bottom: []
                },
                styl: [
                    appResourcesPath + '/stylus/admin/*.styl'
                ],
                css: []
            },
            front: {
                js: {
                    top: [],
                    bottom: []
                },
                styl: [
                    appResourcesPath + '/stylus/front/*.styl'
                ],
                css: []
            }
        }
    };

gulp.task('build-vendors', function () {
    gulp.src(config.vendor.js.bottom)
        .pipe(uglify())
        .pipe(concat('vendors_bottom.js'))
        .pipe(gulp.dest('web/static/js'));

    gulp.src(config.vendor.js.top)
        .pipe(uglify())
        .pipe(concat('vendors_top.js'))
        .pipe(gulp.dest('web/static/js'));

    gulp.src(config.vendor.css)
        .pipe(minifyCSS({keepBreaks: true}))
        .pipe(concat('vendors.css'))
        .pipe(gulp.dest('web/static/css'));

});

gulp.task('build-styl', function() {
    gulp.src(config.app.admin.styl)
        .pipe(stylus())
        .pipe(minifyCSS({keepBreaks: true}))
        .pipe(concat('admin.css'))
        .pipe(gulp.dest('web/static/css/'));
    
});

gulp.task('watch', function() {
    gulp.watch([appResourcesPath + '/**/*.styl'], ['build-styl']);
});
