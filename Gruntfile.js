module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        bowercopy: {
            options: {
                srcPrefix: 'bower_components',
                destPrefix: 'web'
            },
            stylesheets: {
                files: {
                    'css/bootstrap.min.css': 'bootstrap/dist/css/bootstrap.min.css',
                    'css/font-awesome.min.css': 'font-awesome/css/font-awesome.min.css',
                }
            },
            scripts: {
                files: {
                    'js/jquery.min.js': 'jquery/dist/jquery.min.js',
                    'js/bootstrap.min.js': 'bootstrap/dist/js/bootstrap.min.js'
                }
            },
            fonts: {
                files: {
                    'fonts': [
                        'bootstrap/dist/fonts',
                        'font-awesome/fonts'
                    ],
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-bowercopy');

    grunt.registerTask('default', ['bowercopy']);
}
