var fs               = require('fs');
var crypto           = require('crypto');
var del              = require('del');
var gulp             = require('gulp');
var watch            = require('gulp-watch');
var concat           = require('gulp-concat');
var rename           = require('gulp-rename');
var notify           = require('gulp-notify');
var include          = require('gulp-include');
var less             = require('gulp-less');
var minify           = require('gulp-minify-css');
var uglify           = require('gulp-uglify');
var rev              = require('gulp-rev');
var sequence         = require('run-sequence');
var iconfont         = require('gulp-iconfont');
var consolidate      = require('gulp-consolidate');
var svgmin           = require('gulp-svgmin');
var resourcesDir     = 'resources/assets/';
var destDir          = 'public/static/';
var cssPath          = destDir + 'css';
var jsPath           = destDir + 'js';
var buildPath        = destDir + 'build';
var dirs             = fs.readdirSync(resourcesDir);
var moduleTasks      = [];
var moduleWatchTasks = [];

/**
 * MD5 hash a string and return the last 22 characters
 *
 * @param str
 * @returns {*}
 */
function md5(str)
{
    return crypto.createHash('md5').update(str).digest('hex').substring(-22, 22);
}
/*
 |--------------------------------------------------------------------------
 | Module Tasks and Watchers
 |--------------------------------------------------------------------------
 |
 | Creating individual tasks and watchers for each module. Each module has
 | two tasks, two watch tasks for js/css and one watch task for the
 | module. Each module's tasks are added to a global array of
 | tasks to be run on default, and before global watch.
 |
 */
dirs.forEach(function (moduleName/*, index, dirs*/)
{
    var jsTask     = moduleName + '-js';
    var cssTask    = moduleName + '-css';
    var scriptsDir = resourcesDir + moduleName + '/scripts/';
    var lessDir    = resourcesDir + moduleName + '/less/';

    /*
     |--------------------------------------------------------------------------
     | Global Module JS Task
     |--------------------------------------------------------------------------
     |
     | A task that can be run individually or as part of default/watch from cli.
     | This task merges all js includes into one file to the js directory in
     | the public directory of the app.
     |
     */
    gulp.task(jsTask, function ()
    {
        return gulp.src(scriptsDir + moduleName + '.js')
            .pipe(include())
            .pipe(gulp.dest(destDir + 'js/' + moduleName))
            //.pipe(notify('Finished ' + moduleName + ' JS'))
            ;
    });

    /*
     |--------------------------------------------------------------------------
     | Global Module CSS Task
     |--------------------------------------------------------------------------
     |
     | A task that can be run individually or as part of default/watch from cli.
     | This task merges all css imports into one file to the css directory in
     | the public directory of the app.
     |
     */
    gulp.task(cssTask, function ()
    {
        return gulp.src(lessDir + moduleName + '.less')
            .pipe(less())
            .pipe(gulp.dest(destDir + 'css/' + moduleName))
            //.pipe(notify('Finished ' + moduleName + ' CSS'))
            ;
    });

    // Add the individual js/css tasks to the global array of tasks
    moduleTasks.push(jsTask);
    moduleTasks.push(cssTask);

    /*
     |--------------------------------------------------------------------------
     | Global Module JS Watch Task
     |--------------------------------------------------------------------------
     |
     | A watcher set for the module's scripts directory. It monitors changes in
     | the scripts directory of the module for changes then runs the js task
     | when changes are detected.
     |
     */
    gulp.task(jsTask + '-watch', function ()
    {
        // Set a watcher on the module's scripts directory
        watch(scriptsDir, function ()
        {
            // When a change is detected run the module's js task
            gulp.start(jsTask);
        });
    });

    /*
     |--------------------------------------------------------------------------
     | Global Module CSS Watch Task
     |--------------------------------------------------------------------------
     |
     | A watcher set for the module's less directory. It monitors changes in
     | the less directory of the module for changes then runs the css task
     | when changes are detected.
     |
     */
    gulp.task(cssTask + '-watch', function ()
    {
        // Set a watcher on the module's less directory
        watch(lessDir, function ()
        {
            // When a change is detected run the module's css task
            gulp.start(cssTask);
        });
    });

    /*
     |--------------------------------------------------------------------------
     | Global Module Watch Task
     |--------------------------------------------------------------------------
     |
     | A watcher set for the module as a whole. It monitors changes in the less
     | and scripts directories of the module for changes then runs the
     | appropriate task when changes are detected.
     |
     */
    gulp.task(moduleName + '-watch', [jsTask + '-watch', cssTask + '-watch']);

    // Add the module watch task to the global watch tasks
    moduleWatchTasks.push(moduleName + '-watch');
});

/*
 |--------------------------------------------------------------------------
 | Global JS Minifier Task
 |--------------------------------------------------------------------------
 |
 | This task can be run individually or as part of the version task. It is
 | advised to use this task as part of the version task. This task will
 | minify all files in the public/js directory of the app using the
 | uglify plugin.
 |
 */
gulp.task('uglify-js', function ()
{
    // If the js min directory already exists
    if (fs.existsSync(jsPath + '/min/')) {
        // Destroy it with fire
        del(jsPath + '/min/');
    }

    return gulp.src(jsPath + '/**/')
        .pipe(uglify())
        .pipe(gulp.dest(jsPath + '/min'))
        ;
});

/*
 |--------------------------------------------------------------------------
 | Global CSS Minifier Task
 |--------------------------------------------------------------------------
 |
 | This task can be run individually or as part of the version task. It is
 | advised to use this task as part of the version task. This task will
 | minify all files in the public/css directory of the app using the
 | css minify plugin.
 |
 */
gulp.task('minify-css', function ()
{
    // If the css min directory already exists
    if (fs.existsSync(cssPath + '/min/')) {
        // Destroy it with fire
        del(cssPath + '/min/');
    }

    return gulp.src(cssPath + '/**/')
        .pipe(minify())
        .pipe(gulp.dest(cssPath + '/min'))
        ;
});

/*
 |--------------------------------------------------------------------------
 | Global Make Build Task
 |--------------------------------------------------------------------------
 |
 | This task can be run individually or as part of the version task. It is
 | advised to use this task as part of the version task. This task will
 | version all files in the public/[js,css]/min directories and create
 | a build directory. It will also create a manifest file for the
 | app to use to determine the correct versioned js/css file to
 | serve.
 |
 */
gulp.task('make-build', function ()
{
    if (fs.existsSync(buildPath)) {
        del(buildPath);
    }

    return gulp.src([
        cssPath + '/min/**/',
        jsPath + '/min/**/'
    ])
        .pipe(gulp.dest(buildPath))
        .pipe(rev())
        .pipe(gulp.dest(buildPath))
        .pipe(rev.manifest())
        .pipe(gulp.dest(buildPath))
        ;
});

/*
 |--------------------------------------------------------------------------
 | Global Version Task
 |--------------------------------------------------------------------------
 |
 | This task is meant to be run individually and NOT part of any other task.
 | This task will run the minify css/js tasks concurrently, then when they
 | both complete it will run the make build task.
 |
 */
gulp.task('version', function ()
{
    sequence(['minify-css', 'uglify-js'], 'make-build');
});

/*
 |--------------------------------------------------------------------------
 | Global Optimize Icons Task
 |--------------------------------------------------------------------------
 |
 | This task is meant to be run individually and NOT part of any other task.
 | This task is used to optimize the icons found in the assets/global/icons
 | directory prior to using the icons in a font. It is meant to be run
 | once per file. Exceeding this number may break svg icons.
 |
 */
gulp.task('optimize-icons', function ()
{
    var path = resourcesDir + 'global/';

    return gulp.src([path + 'icons/*.svg'])
        .pipe(svgmin({
            plugins: [
                {
                    removeDoctype: true
                },
                {
                    removeComments: true
                },
                {
                    removeViewBox: true
                },
                {
                    removeUselessStrokeAndFill: true
                }
            ]
        }))
        .pipe(gulp.dest(path + 'icons/'))
        ;
});

/*
 |--------------------------------------------------------------------------
 | Global Icons Font Task
 |--------------------------------------------------------------------------
 |
 | This task is meant to be run individually and NOT part of any other task.
 | This task is used to create the four web fonts necessary for a font icon
 | set. This is the task used to create the fonts and css file for the
 | icons used across the site. When new icons are added this task
 | should be run.
 |
 */
gulp.task('icon-font', function ()
{
    // The global module directory
    var path         = resourcesDir + 'global/';
    // Set the name for the fonts files
    var fontName     = 'icons';
    // Get all the files in the icons directory
    var files        = fs.readdirSync(path + 'icons/');
    // Variable to house the aggregated contents of all icons
    var contents     = '';
    // The file name for the fonts
    var fontFileName = '';

    console.log('files', files);

    // Iterate through each file
    files.forEach(function (file)
    {
        // If it is an svg
        if (file.indexOf('.svg') !== -1) {
            // Add its contents to the variable
            contents += fs.readFileSync(path + 'icons/' + file);
        }
    });

    // Set the font name as the md5 of the contents
    // This ensures the file name is always the same
    // if nothing changes
    fontFileName = md5(contents);

    return gulp.src([path + 'icons/*.svg'])
    // Create fonts from the icons
        .pipe(iconfont({
            fontName          : fontFileName,
            centerHorizontally: true,
            normalize         : true,
            fontHeight        : 1000
        }))
        // Get the codepoints
        .on('codepoints', function (codepoints/*, options*/)
        {
            console.log(codepoints);
            // Using lodash consolidate all the codepoints
            // and create a new less file in the
            // global/less directory
            return gulp.src(path + 'lodash/' + fontName + '.lodash')
                .pipe(consolidate('lodash', {
                    glyphs      : codepoints,
                    fontName    : fontName,
                    fontFileName: fontFileName,
                    fontPath    : destDir + 'fonts/' + fontName + '/',
                    className   : 'icon'
                }))
                .pipe(rename({
                    extname: '.less'
                }))
                .pipe(gulp.dest(path + '/less/'))
                ;
        })
        // Save the fonts to the public/static/fonts directory
        .pipe(gulp.dest(destDir + 'fonts/' + fontName + '/'))
        ;
});

/*
 |--------------------------------------------------------------------------
 | Global Watch Task
 |--------------------------------------------------------------------------
 |
 | This task is meant to be run individually and NOT part of any other task.
 | This task is used to create watchers for every module so as to not have
 | to specify a module to watch. This task is slower than watching on a
 | specific module, but gives the peace of mind to the developers to
 | not have to change watch tasks as they move from module to
 | module.
 |
 */
gulp.task('watch', function ()
{
    sequence(moduleTasks, moduleWatchTasks);
});

/*
 |--------------------------------------------------------------------------
 | Global Default Task
 |--------------------------------------------------------------------------
 |
 | This task is meant to be run individually and NOT part of any other task.
 | This task is used to run all the modules together and create all
 | necessary static assets in the public/static directory of the
 | app.
 |
 */
gulp.task('default', moduleTasks);
