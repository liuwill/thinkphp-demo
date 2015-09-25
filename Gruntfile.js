/**
 * Created by Administrator on 2015/7/14.
 */
module.exports = function(grunt) {

    // Time how long tasks take. Can help when optimizing build times
    //require('time-grunt')(grunt);

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        php: {
            execute: {
                options: {
                    hostname: '<%= pkg.dev_hostname %>',
                    port: '<%= pkg.dev_port %>',
                    base: 'deploy', // Project root
                    keepalive: true,
                    open: false
                }
            }
        },
        useminPrepare: {
            html: ['build/view/Admin/**/*.html']
        },
        filerev: {
            options: {
                encoding: 'utf8',
                algorithm: 'md5',
                length: 8
            },
            assets: {
                files: [
                    {src: 'build/scripts/*.{js,css}'},
                    {src: 'build/styles/*.{js,css}'}
                ]
            }
        },
        usemin: {
            /*css:{
                files:{
                    src:{'static/styles/*.css'}
                }
            },
            js: 'static/scripts/*.js',*/
            html:'build/view/Admin/**/*.html',
            options: {
                assetsDirs: ['build/scripts/','build/styles/'],
                patterns: {
                    html: [
                        [/(app\.js)/, 'Replacing reference to app.js'],
                        [/(login\.js)/, 'Replacing reference to login.js'],
                        [/(main\.css)/, 'Replacing reference to main.css'],
                        [/(global\.css)/, 'Replacing reference to global.css']
                        /*[/(\/static\/styles\/[\w-]+\.css)/, 'Replacing css in html'],
                        [/(\/static\/scripts\/[\w-]+\.js)/, 'Replacing js in html']*/
                    ]
                }
            }
        },
        clean: {
            build: {
                src: ["build","deploy"]
            }
        },
        htmlmin: {                                     // Task
          deploy: {                                      // Target
            options: {                                 // Target options
              removeComments: true,
              collapseWhitespace: true
            },
            files: {                                   // Dictionary of files
              'build/test/index.html': 'build/view/Admin/Index/index.html',     // 'destination': 'source'
              'build/test/login.html': 'build/view/Admin/Public/login.html'
            }
          }
        },
        copy: {
            bower:{
                files:[{
                expand: true,
                dot: true,
                cwd: 'deploy/static/lib/bootstrap/',
                dest: 'deploy/static/lib/fonts/',
                src: ['*.{eot,svg,ttf,woff,woff2}']
            }]},
            main:{
                files:[{
                    expand: true,
                    dot: true,
                    cwd: 'main/',
                    dest: 'deploy/thinkphp/',
                    src: ['**']
                },{
                    expand: true,
                    dot: true,
                    cwd: 'resources/images/',
                    dest: 'deploy/static/images',
                    src: ['**']
                }]
            },
            deploy:{
                files:[{
                    expand: true,
                    dot: true,
                    cwd: 'build/styles/',
                    dest: 'deploy/static/styles/',
                    src: ['**']
                },{
                    expand: true,
                    dot: true,
                    cwd: 'src/images/',
                    dest: 'deploy/static/images/',
                    src: ['**']
                },{
                    expand: true,
                    dot: true,
                    cwd: 'build/scripts/',
                    dest: 'deploy/static/scripts/',
                    src: ['**']
                },{
                    expand: true,
                    dot: true,
                    cwd: 'build/view/Admin/Index/',
                    dest: 'deploy/thinkphp/Application/Admin/View/Index/',
                    src: ['**']
                },{
                    expand: true,
                    dot: true,
                    cwd: 'build/view/Admin/Public/',
                    dest: 'deploy/thinkphp/Application/Admin/View/Public/',
                    src: ['**']
                }]
            },
            build: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: 'main/Application/Admin/View/Index/',
                    dest: 'build/view/Admin/Index/',
                    src: ['**']
                },{
                    expand: true,
                    dot: true,
                    cwd: 'main/Application/Admin/View/Public/',
                    dest: 'build/view/Admin/Public/',
                    src: ['**']
                }]
            }
        },
        cssmin: {
          build: {
            files: [{
              expand: true,
              cwd: 'build/css',
              src: ['*.css', '!*.min.css'],
              dest: 'build/styles',
              ext: '.css'
            }]
          }
        },
        autoprefixer: {
            options: {
                browsers: ['last 2 versions', 'ie 8', 'ie 9']
            },
            build:{
                files: [{
                    expand:true,
                    cwd:'build/sass',
                    src:'**/*.css',
                    dest: 'build/css'
                }]
            }
            /*dist : {
                files : { 'build/styles/!*.css' : 'public/styles/!*.css' }
            }*/
        },
        // Compiles Sass to CSS and generates necessary files if requested
        sass: {
            options: {
                sourceMap: true,
                outputStyle: 'compressed',
                banner: '/*! LiuWill <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                files: [{
                    expand: true,
                    cwd: 'src/sass',
                    src: ['*.{scss,sass}'],
                    dest: 'build/sass',
                    ext: '.css'
                }]
            }
        },
        /*watch: {
            //livereload: true,
            sass: {
                files: ['src/sass/{,* /}*.{scss,sass}'],
                tasks: ['sass:server','copy:install'],
                options: {
                    livereload: false
                }
            }
        },*/
        bower: {
            install: {
                options: {
                    targetDir: './deploy/static/lib',
                    layout: 'byComponent',
                    install: true,
                    verbose: false,
                    cleanTargetDir: true,
                    cleanBowerDir: false,
                    bowerOptions: {}
                }
            }
        },
        uglify: {
            options: {
                report: "min",
                banner: '/*! LiuWill <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                options: {
                    report: "min",
                    banner: '/*! LiuWill <%= grunt.template.today("yyyy-mm-dd") %> */\n'
                },
                files: [{
                    expand:true,
                    cwd:'src/scripts',
                    src: '**/*.js',
                    dest: 'build/scripts'
                }/*,{
                    'build/static/js/home/common-h5.min.js': ['src/js/home-h5.js','src/js/role-manage.js']
                }*/]
            }
        }
    });

    grunt.registerTask('prepare', ['clean:build']);
    
    grunt.registerTask('build', ['copy:build','sass:build',"autoprefixer:build","cssmin:build",'uglify:build',"filerev:assets","usemin:html"]);
    grunt.registerTask('deploy', ['copy:main','copy:deploy','bower:install','copy:bower']);
    grunt.registerTask('execute', ['php:execute']);
    
    grunt.registerTask('deploy_pack', ['prepare','build','deploy']);
    grunt.registerTask('execute_all', ['prepare','build','deploy','execute']);
    //grunt.registerTask('execute_full', ['clean:build','copy:build','sass:build',"autoprefixer:build","cssmin:build",'uglify:build',"filerev:assets","usemin:html",'copy:main','copy:deploy','bower:install','copy:bower','php:execute']);
};