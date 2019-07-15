var gulp          = require('gulp');
var browserSync   = require('browser-sync').create();
var $             = require('gulp-load-plugins')();
var autoprefixer  = require('autoprefixer');
var rename        = require('gulp-rename');
var uglify        = require('gulp-uglify');
var concat        = require('gulp-concat');
var imagemin      = require('gulp-imagemin');
var cssmin        = require('gulp-cssmin');
var sourcemaps    = require('gulp-sourcemaps');
var gutil         = require('gulp-util');

var template_name = 'cc-commoners-2019';
var sassPaths = [
  'node_modules/foundation-sites/scss',
  'node_modules/motion-ui/src'
];

var js = {
  //JS Dependencies
  fileList: [
    'node_modules/foundation-sites/dist/js/plugins/foundation.core.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.util.mediaQuery.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.util.keyboard.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.util.triggers.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.util.box.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.util.motion.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.util.nest.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.sticky.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.tabs.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.equalizer.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.tooltip.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.dropdown.js',
    'node_modules/swipebox/src/js/jquery.swipebox.js',
    'node_modules/slick-carousel/slick/slick.js',
  ],
  //CSS Dependencies
  styles: [
    'node_modules/swipebox/src/css/swipebox.css',
    'node_modules/slick-carousel/slick/slick.css',
    'node_modules/slick-carousel/slick/slick-theme.css',
  ]
};
/* Build single JS dependencies file  */
gulp.task('build-js', function () {
  return gulp.src(js.fileList)
    .pipe(sourcemaps.init())
    .pipe(concat('dependencies.js'))
    .pipe(uglify().on('error', gutil.log))
    //.pipe( sourcemaps.write( '/' ) )
    .pipe(gulp.dest('../themes/' + template_name + '/assets/js'));
});
/* Theme Image optimization */
gulp.task('imgmin', function () {
  gulp.src('../themes/' + template_name + '/img/*')
    .pipe(imagemin())
    .pipe(gulp.dest('../themes/' + template_name + '/assets/img/'))
});
/* Build single CSS dependencies file */
gulp.task('build-css', function () {
  return gulp.src(js.styles)
    .pipe(sourcemaps.init())
    .pipe(concat('dependencies.css'))
    .pipe(cssmin())
    //.pipe( sourcemaps.write( '/' ) )
    .pipe(gulp.dest('../themes/' + template_name + '/assets/css'));
});
function sass() {
  return gulp.src('scss/app.scss')
    .pipe($.sass({
      includePaths: sassPaths,
      //outputStyle: 'compressed' // if css compressed **file size**
    })
      .on('error', $.sass.logError))
    .pipe($.postcss([
      autoprefixer()
    ]))
    //.pipe(gulp.dest('css'))
    //.pipe(browserSync.stream());
    .pipe(rename('style.css'))
    .pipe(gulp.dest('../themes/' + template_name +'/assets/css'))
    .pipe(gulp.dest('./css'));
};

function serve() {
  browserSync.init({
    server: "./"
  });

  gulp.watch("scss/*.scss", sass);
  gulp.watch("*.html").on('change', browserSync.reload);
}
function watch() {
  gulp.watch("scss/*.scss", sass)
}
gulp.task('sass', sass);
gulp.task('watch', watch);
gulp.task('serve', gulp.series('sass', serve));
gulp.task('default', gulp.series('sass', serve));
