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
                    bottom: [
                        //upload
                        //'bower_components/blueimp-tmpl/js/tmpl.min.js',
                        'bower_components/blueimp-load-image/js/load-image.all.min.js',
                        'bower_components/blueimp-canvas-to-blob/js/canvas-to-blob.min.js',
                        'bower_components/blueimp-gallery/js/jquery.blueimp-gallery.min.js',
                        'bower_components/blueimp-bootstrap-image-gallery/js/blueimp-bootstrap-image-gallery.min.js',
                        'bower_components/jquery-file-upload/js/jquery.fileupload.js',
                        'bower_components/jquery-file-upload/js/jquery.fileupload-process.js',
                        'bower_components/jquery-file-upload/js/jquery.fileupload-image.js',
                        'bower_components/jquery-file-upload/js/jquery.fileupload-validate.js',
                        'bower_components/jquery-file-upload/js/jquery.fileupload-ui.js',
                        //'bower_components/bower_components/jquery-file-upload/js/vendor/jquery.ui.widget.js',

                    ]
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

    gulp.src(config.app.admin.js.bottom)
        .pipe(uglify())
        .pipe(concat('vendors_fileupload.js'))
        .pipe(gulp.dest('web/static/js'));

    gulp.src('bower_components/bootstrap/fonts/*')
        .pipe(gulp.dest('web/static/fonts/'))
});

gulp.task('build-app', function(){

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
