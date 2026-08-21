// Import required packages
const gulp = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const concat = require("gulp-concat");
const cleanCSS = require("gulp-clean-css");
const sourcemaps = require("gulp-sourcemaps");
const rename = require("gulp-rename");
const uglify = require("gulp-uglify");
const eslint = require("gulp-eslint");

// File paths
const paths = {
  scss: ["./src/scss/**/*.scss", "!./src/scss/admin/**"], // All SCSS files except admin
  cssOutput: "./dist/css", // Destination folder for CSS
  js: "./src/js/**/*.js", // All JS files
  jsOutput: "./dist/js", // Destination folder for minified JS
  adminScss: "./src/scss/admin/**/*.scss", // All admin SCSS files
  images: "./src/images/**/*", // Placeholder for image paths
  imageOutput: "./dist/images", // Destination folder for processed images
  fonts: "./src/fonts/**/*", // Placeholder for font paths
  fontsOutput: "./dist/fonts", // Destination folder for fonts
};

// Compile SCSS to CSS, concatenate, and minify
function compileSCSS() {
  return gulp
    .src(paths.scss) // Source SCSS files excluding admin
    .pipe(sourcemaps.init()) // Initialize sourcemaps
    .pipe(sass().on("error", sass.logError)) // Compile SCSS
    .pipe(concat("style.css")) // Concatenate into a single file
    .pipe(cleanCSS()) // Minify the CSS
    .pipe(rename({ suffix: ".min" })) // Add .min suffix
    .pipe(sourcemaps.write("./")) // Write sourcemaps
    .pipe(gulp.dest(paths.cssOutput)); // Output the final file
}

// Compile admin SCSS into admin.css
function compileAdminSCSS() {
  return gulp
    .src(paths.adminScss)
    .pipe(sourcemaps.init())
    .pipe(sass().on("error", sass.logError))
    .pipe(concat("admin.css"))
    .pipe(cleanCSS())
    .pipe(rename({ suffix: ".min" }))
    .pipe(sourcemaps.write("./"))
    .pipe(gulp.dest(paths.cssOutput));
}

// Minify and concatenate JavaScript files
function minifyJS() {
  return gulp
    .src(paths.js) // Source all JS files
    .pipe(sourcemaps.init()) // Initialize sourcemaps
    .pipe(concat("scripts.js")) // Concatenate into a single file
    .pipe(uglify()) // Minify JavaScript
    .pipe(rename({ suffix: ".min" })) // Add .min suffix
    .pipe(sourcemaps.write("./")) // Write sourcemaps
    .pipe(gulp.dest(paths.jsOutput)); // Output the final file
}

// Process and optimize images
function processImages() {
  return gulp
    .src(paths.images) // Source all image files
    .pipe(gulp.dest(paths.imageOutput)); // Copy images to output folder
}

// Process and optimize fonts
function processFonts() {
  return gulp
    .src(paths.fonts) // Source all font files
    .pipe(gulp.dest(paths.fontsOutput)); // Copy fonts to output folder
}

// Lint JavaScript
function lintJS() {
  return gulp
    .src(["./src/js/scripts.js"]) // Exclude minified files
    .pipe(eslint()) // Run ESLint
    .pipe(eslint.format()) // Output linting results to the console
    .pipe(eslint.failAfterError()) // Fail task on linting error
    .on("error", function (err) {
      console.error("ESLint error:", err.message); // Log ESLint error
      this.emit("end"); // Prevent Gulp from crashing
    });
}

// Watch SCSS files for changes
function watchFiles() {
  gulp.watch(paths.scss, compileSCSS); // Watch and recompile on changes
  gulp.watch(paths.adminScss, compileAdminSCSS); // Watch admin SCSS files and recompile on changes
  gulp.watch(paths.js, minifyJS); // Watch JS files and minify on changes
  gulp.watch(paths.images, processImages); // Watch image files and process on changes
  gulp.watch(paths.fonts, processFonts); // Watch font files and process on changes
}

// Define default task
const defaultTask = gulp.series(
  gulp.parallel(
    compileSCSS,
    compileAdminSCSS,
    minifyJS,
    processImages,
    processFonts,
    lintJS
  ),
  watchFiles
);

// Export tasks
exports.default = defaultTask;
exports.compile = compileSCSS;
exports.processImages = processImages;
exports.minifyJS = minifyJS;
exports.watch = watchFiles;
exports.lintJS = lintJS;
exports.compileAdminSCSS = compileAdminSCSS;
exports.processFonts = processFonts;
