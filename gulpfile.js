'use strict';
const gulp = require('gulp');
const pump = require('pump');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');

const styleDir = 'Assets/Stylesheets';
const scriptDir = 'Assets/Scripts';

//string|string[] of path(s) to file(s)
const stylesheets = [
    styleDir + '/src/variables.scss',
    styleDir + '/src/animations.scss',
    styleDir + '/src/normalize.scss',
    styleDir + '/src/typography.scss',
    styleDir + '/src/grid.scss',
    styleDir + '/src/general.scss',
    styleDir + '/src/navigation.scss',
    styleDir + '/src/form.scss',
    styleDir + '/src/pane.scss'
];

gulp.task('style', function () {
    pump([
        gulp.src(stylesheets),
        sourcemaps.init(),
        sass.sync({
            indentWidth: 4,
            indentType: 'space',
            outputStyle: 'expanded'
        }).on('error', sass.logError),
        autoprefixer({
            browsers: [
                'last 4 versions'
            ],
            cascade: true
        }),
        concat('style.css'),
        sourcemaps.write(),
        gulp.dest(styleDir)
    ]);
});

gulp.task('default', [
    'style'
]);

gulp.task('watch', function () {
    gulp.watch(stylesheets, ['style']);
});