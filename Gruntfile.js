module.exports = function (grunt) {

	grunt.initConfig({
		'pkg': grunt.file.readJSON('package.json'),
		'clean': {
			'build': {
				'src': 'assets/dist'
			}
		},
		'copy': {
			'build': {
				'files': [
					{
						'cwd': 'assets/source/images',
						'src': ['**'],
						'dest': 'assets/dist/images',
						'expand': true
					}
				]
			}
		},
		'uglify': {
			'build': {
				'files': {
					'assets/dist/js/admin.min.js': [
						'assets/source/js/admin.js'
					],
					'assets/dist/js/tinymce-booking-form.min.js': 'assets/source/js/tinymce-booking-form.js',
					'assets/dist/js/front.min.js': [
						'assets/source/js/moment.js',
						'assets/source/js/pickaday.js',
						'assets/source/js/front.js'
					]
				},
				'options': {
					'banner': '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n',
					'sourceMap': true
				}
			}
		},
		'less': {
			'build': {
				'options': {
					'compress': true,
					'sourceMap': true
				},
				'files': {
					'assets/dist/css/admin.min.css': 'assets/source/less/admin.less',
					'assets/dist/css/front.min.css': 'assets/source/less/front.less',
				}
			}
		},
		'watch': {
			'less': {
				'files': ['assets/source/less/**'],
				'tasks': ['less:build']
			},
			'scripts': {
				'files': ['assets/source/js/*'],
				'tasks': ['uglify:build']
			},
			'images': {
				'files': ['assets/source/images/**'],
				'tasks': ['copy:build']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-copy');

	grunt.registerTask('build', ['clean', 'copy', 'less', 'uglify']);
};
