/**
 * Gruntfile for the Foundation theme.
 *
 * This file configures tasks to be run by Grunt
 * http://gruntjs.com/ for the current theme.
 *
 *
 * Requirements:
 * -------------
 * nodejs, npm, grunt-cli.
 *
 * Installation:
 * -------------
 * node and npm: instructions at http://nodejs.org/
 *
 * grunt-cli: `[sudo] npm install -g grunt-cli`
 *
 * node dependencies: run `npm install` in the root directory.
 *
 *
 * Usage:
 * ------
 * Call tasks from the theme root directory. Default behaviour
 * (calling only `grunt`) is to run the watch task detailed below.
 *
 *
 * Porcelain tasks:
 * ----------------
 * The nice user interface intended for everyday use. Provide a
 * high level of automation and convenience for specific use-cases.
 *
 * grunt css     Create the default CSS and lint the SCSS.
 *
 * grunt amd     Create the Asynchronous Module Definition JavaScript files.  See: MDL-49046.
 *               Done here as core Gruntfile.js currently *nix only.
 *
 * @package theme_foundation.
 * @author G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @author Based on code originally written by Andrew Nicols, Joby Harding, Bas Brands, David Scotson and many other contributors.
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

module.exports = function(grunt) { // jshint ignore:line
    var path = require('path'),
        tasks = {},
        cwd = process.env.PWD || process.cwd(), // jshint ignore:line
        DOMParser = require('xmldom').DOMParser,
        xpath = require('xpath'),
        semver = require('semver');

    // Verify the node version is new enough.
    var expected = semver.validRange(grunt.file.readJSON('package.json').engines.node);
    var actual = semver.valid(process.version); // jshint ignore:line
    if (!semver.satisfies(actual, expected)) {
        grunt.fail.fatal('Node version too old. Require ' + expected + ', version installed: ' + actual);
    }

    /* Windows users can't run grunt in a subdirectory, so allow them to set
       the root by passing --root=path/to/dir. */
    if (grunt.option('root')) {
        var root = grunt.option('root');
        if (grunt.file.exists(__dirname, root)) { // jshint ignore:line
            cwd = path.join(__dirname, root); // jshint ignore:line
            grunt.log.ok('Setting root to ' + cwd);
        } else {
            grunt.fail.fatal('Setting root to ' + root + ' failed - path does not exist');
        }
    }

    /**
     * Find thirdpartylibs.xml and generate an array of paths contained within
     * them (used to generate ignore files and so on).
     *
     * @return {array} The list of thirdparty paths.
     */
    var getThirdPartyPathsFromXML = function() {
        var thirdpartyfiles = grunt.file.expand('**/thirdpartylibs.xml');
        var libs = ['node_modules/', 'vendor/'];

        thirdpartyfiles.forEach(function(file) {
            var dirname = path.dirname(file);

            var doc = new DOMParser().parseFromString(grunt.file.read(file));
            var nodes = xpath.select("/libraries/library/location/text()", doc);

            nodes.forEach(function(node) {
                var lib = path.join(dirname, node.toString());
                if (grunt.file.isDir(lib)) {
                    // Ensure trailing slash on dirs.
                    lib = lib.replace(/\/?$/, '/');
                }

                // Look for duplicate paths before adding to array.
                if (libs.indexOf(lib) === -1) {
                    libs.push(lib);
                }
            });
        });
        return libs;
    };

    // PHP strings for exec task.
    var moodleroot = path.dirname(path.dirname(__dirname)), // jshint ignore:line
        dirrootopt = grunt.option('dirroot') || process.env.MOODLE_DIR || ''; // jshint ignore:line

    // Allow user to explicitly define Moodle root dir.
    if ('' !== dirrootopt) {
        moodleroot = path.resolve(dirrootopt);
    }

    var configfile = path.join(moodleroot, 'config.php');

    var decachephp = 'define(\'CLI_SCRIPT\', true);';
    decachephp += 'require(\'' + configfile + '\');';
    decachephp += 'purge_all_caches();';

    // Project configuration.
    grunt.initConfig({
        sass: {
            dist: {
                files: {
                    "style/fallback.css": "scss/preset/fallback.scss"
                }
            },
            options: {
                includePaths: ["scss/preset/"]
            }
        },
        stylelint: {
            scss: {
                options: {syntax: 'scss'},
                src: ['*/**/*.scss']
            },
            css: {
                src: ['*/**/*.css'],
                options: {
                    configOverrides: {
                        rules: {
                            // These rules have to be disabled in .stylelintrc for scss compat.
                            "at-rule-no-unknown": true,
                        }
                    }
                }
            }
        },
        exec: {
            decache: {
                cmd: 'php -r "' + decachephp + '"',
                callback: function(error) {
                    // The 'exec' process will output error messages, just add one to confirm success.
                    if (!error) {
                        grunt.log.writeln("Moodle cache reset.");
                    }
                }
            }
        },
        jshint: {
            options: {jshintrc: moodleroot + '/.jshintrc'},
            files: ['**/amd/src/*.js']
        },
        uglify: {
            dynamic_mappings: {
                files: grunt.file.expandMapping(
                    ['**/src/*.js', '!**/node_modules/**'],
                    '',
                    {
                        cwd: cwd,
                        rename: function(destBase, destPath) {
                            destPath = destPath.replace('src', 'build');
                            destPath = destPath.replace('.js', '.min.js');
                            destPath = path.resolve(cwd, destPath);
                            return destPath;
                        }
                    }
                )
            }
        }
    });

    /**
     * Generate ignore files (utilising thirdpartylibs.xml data)
     */
    tasks.ignorefiles = function() {
        // An array of paths to third party directories.
        var thirdPartyPaths = getThirdPartyPathsFromXML();
        // Generate .stylelintignore.
        var stylelintIgnores = [
            '# Generated by "grunt ignorefiles"',
            'style/fallback.css'
        ].concat(thirdPartyPaths);
        grunt.file.write('.stylelintignore', stylelintIgnores.join('\n'));
    };

    // Register tasks.
    grunt.loadNpmTasks("grunt-exec");
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-stylelint');
    grunt.registerTask("decache", ["exec:decache"]);

    // Register JS tasks.
    grunt.registerTask('ignorefiles', 'Generate ignore files for linters', tasks.ignorefiles);

    // Load core tasks.
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.registerTask("amd", ["jshint", "uglify", "decache"]);

    // Register CSS taks.
    grunt.registerTask('css', ['ignorefiles', 'stylelint:scss', 'sass', 'stylelint:css']);

    // Register the default task.
    grunt.registerTask('default', ['css']);
};
