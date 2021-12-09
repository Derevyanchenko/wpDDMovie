const path = require('path');

module.exports = {
	mode: 'production',
	module: {
		rules: [{
			test:/\.js$/,
			exclude: /node_modules/,
			use: [
				'babel-loader',
			]
		}]
	},
	entry: './assets/src/index.js',
	output: {
		path: path.resolve(__dirname, './assets/dist'),
		filename: 'bundle.js'
	}
};
