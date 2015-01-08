/**
 * ACHTUNG: BEI REMOTE_SERVER AN PORT WEITERLEITUNG DENKEN MITTELS
 * netsh interface portproxy add v4tov4 listenport=35729 listenaddress=127.0.0.1 connectport=35729 connectaddress=192.168.0.254
 */

//Configuration
var cssFiles = 'public/css/**/*.css';
var jsFiles =  'public/js/src/*.js';
var viewFiles =  'application/views/**/*.phtml';
var jsVendorFiles = 'public/js/vendors/*.js';
var production = false;

//Required by Gulp
var gulp = require('gulp'),
    livereload = require('gulp-livereload'),
    rename = require('gulp-rename'),
    run = require('gulp-run'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    debug = require('gulp-debug'),
    gulpif = require('gulp-if'),
    path = require('path'),
    plumber = require('gulp-plumber'),
    filter = require("gulp-filter"),
    sourcemaps = require('gulp-sourcemaps');

//Starts server to inform about livereloads
livereload.listen();

/**
 * Setz produktiv-Umgebung
 */
gulp.task("production", function () {
    production = false;
});
/**
 * Komprimiert Dateien vor
 */
gulp.task("jsPrepare", function () {
    return gulp.src(jsFiles)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(
            gulpif(production,
                uglify({preserveComments: "some"})
            )
        )
        .pipe(rename({suffix: '.min'}))
        .pipe(
            gulpif(!production,
                sourcemaps.write()
            )
        )
        .pipe(gulp.dest("./public/js/dist"))
        .pipe(livereload());
});

/**
 * Füge Vendor-dateien zusammen
 */
gulp.task("concatJSvendors", function () {
    var orderdFiles = filter("{0..9}{0..9}*.js");
    var unorderdFiles = filter("[A-Za-z]*.js");

    return gulp.src(jsVendorFiles)
        .pipe(plumber())
        .pipe(orderdFiles)
        .pipe(concat("vendors.js"))
        .pipe(orderdFiles.restore())
        //normale-vendor-Dateien
        .pipe(unorderdFiles)
        .pipe(
        gulpif(production,
            uglify({preserveComments: "some"})
        )
    )
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest("./public/js/dist"))
        .pipe(livereload());
});

//Refresh
var refresh = function (f) {
    livereload.changed(f);
};

/**
 * Watchers
 */
gulp.task('watch', function () {
    //Starte Compass
    run("while true; do compass compile public --time --quiet; sleep .5; done").exec();

    // Watch any files in dist/, reload on change
    gulp.watch(cssFiles, refresh);
    gulp.watch(viewFiles, refresh);
    gulp.watch(jsFiles, ["jsPrepare"]);
    gulp.watch(jsVendorFiles, ["concatJSvendors"]);

});