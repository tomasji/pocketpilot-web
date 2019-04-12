const path = require('path')
const WebpackAssetsManifest = require('webpack-assets-manifest')
const CleanWebpackPlugin = require('clean-webpack-plugin')

module.exports = {
	entry: {
		main: './app/assets/js/Main.js',
		map: './app/assets/js/Map.js',
		qr: './app/assets/js/QR.js'
	},
	output: {
		path: path.join(path.resolve(), 'www/dist'),
		filename: '[name].[contenthash:8].js'
	},
	optimization: {
		runtimeChunk: true,
		splitChunks: {
			chunks: 'all',
			maxInitialRequests: Infinity,
			minSize: 0,
			cacheGroups: {
				vendor: {
					test: /[\\/]node_modules[\\/]/,
					name(module) {
						// get the name. E.g. node_modules/packageName/not/this/part.js
						// or node_modules/packageName
						const packageName = module.context.match(/[\\/]node_modules[\\/](.*?)([\\/]|$)/)[1]

						// npm package names are URL-safe, but some servers don't like @ symbols
						return `npm.${packageName.replace('@', '')}`
					}
				}
			}
		}
	},
	module: {
		rules: [
			{
				test: /\.js?$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
				options: {
					'presets': [
						[
							'@babel/preset-env', {
								'targets': {
									'browsers': ['last 2 versions']
								}
							}
						]
					]
				}
			},
			{
				rules: [{
					test: /\.scss$/,
					use: [
						'style-loader',
						'css-loader',
						'sass-loader'
					]
				}]
			},
			{
				test: /\.(jpe?g|png|gif|svg)$/i,
				use: [
					'file-loader?name=images/[name].[ext]'
				]
			}
		]
	},
	plugins: [
		new WebpackAssetsManifest({
			entrypoints: true
		}),
		new CleanWebpackPlugin()
	]
}
