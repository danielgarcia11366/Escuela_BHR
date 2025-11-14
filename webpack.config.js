const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
module.exports = {
  mode: 'development',
  entry: {
    'js/app' : './src/js/app.js',
    'js/inicio' : './src/js/inicio.js',
    'js/cursos/index' : './src/js/cursos/index.js',
    'js/personal/index' : './src/js/personal/index.js',
    'js/promociones/index' : './src/js/promociones/index.js',
    'js/promociones/historial' : './src/js/promociones/historial.js',
    'js/participantes/index' : './src/js/participantes/index.js',
    'js/auth/login' : './src/js/auth/login.js',
    'js/auth/registro' : './src/js/auth/registro.js',
    'js/auth/login' : './src/js/auth/login.js',

  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/build')
  },
  plugins: [
    new MiniCssExtractPlugin({
        filename: 'styles.css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.(c|sc|sa)ss$/,
        use: [
            {
                loader: MiniCssExtractPlugin.loader
            },
            'css-loader',
            'sass-loader'
        ]
      },
      {
        test: /\.(png|svg|jpe?g|gif)$/,
        type: 'asset/resource',
      },
    ]
  }
};