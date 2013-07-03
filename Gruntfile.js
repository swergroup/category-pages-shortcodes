module.exports = function (grunt) {

  "use strict";

  grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),

    phpcs: {
      options: {
        bin: 'phpcs -p -s',
        standard: 'WordPress'
      },
      includes: {
        dir: 'lib/*.php'
      },
      main: {
        dir: './*.php'
      },
    },

    phpdocumentor: {
      uploadplus: {
        options: {
          bin: '/usr/bin/phpdoc --ignore=assets,node_modules,tests',
          directory: '.',
          target: 'docs/'
        },
        generate: {}
      }
    },

    phplint: {
      uploadplus: ['page2cat.php', 'lib/*.php'],
    },

    shell: {
      'phpunit': {
        command: 'WP_TESTS_DIR=~/Sites/wp/unit-tests phpunit',
        options: {
          stdout: true,
          failOnError: false,
        }
      },
      'phpdoc': {
        command: '/usr/bin/phpdoc --ignore=assets,node_modules,tests -d page2cat -t docs/',
        options: {
          stdout: true,
          failOnError: false
        }
      },
      'phpmd': {
        command: '/usr/bin/phpmd lib,page2cat.php text codesize,design,naming,unusedcode',
        options: {
          stdout: true,
          failOnError: false
        }
      }
    }

  });


    grunt.loadNpmTasks('grunt-phpcs');
    grunt.loadNpmTasks('grunt-phplint');
    grunt.loadNpmTasks('grunt-phpdocumentor');
    grunt.loadNpmTasks('grunt-shell');

    grunt.registerTask( 'docs' , [ 'shell:phpdoc' ] );
    grunt.registerTask( 'lint' , [ 'phpcs', 'phplint', 'shell:phpmd' ] );
    grunt.registerTask( 'test' , [ 'shell:phpunit' ] );
    grunt.registerTask( 'default' , [ 'lint', 'test' ] );

  };