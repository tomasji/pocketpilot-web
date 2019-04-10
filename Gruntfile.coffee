module.exports = (grunt) ->

	require("load-grunt-tasks")(grunt)
	sass = require("node-sass")

	grunt.initConfig

		browserify:
			dist:
				files:
					"www/js/app.js": "app/assets/js/Main.js",
					"www/js/map.js": "app/assets/js/Map.js"
				options:
					transform: [
						[
							"babelify",
							presets: [
								[
									"@babel/preset-env",
									targets:
										browsers: [
											"last 3 versions"
										]
								]
							]
						]
					]
					browserifyOptions:
						debug: true

		eslint:
			options:
				configFile: '.eslintrc.js',
			target: [ "app/assets/js/*" ]

		sass:
			options:
				implementation: sass,
				sourceMap: true
			dist:
				files:
					'www/css/main.css': 'app/assets/css/main.scss'

	grunt.registerTask("default", ["eslint", "browserify:dist", "sass"]);
