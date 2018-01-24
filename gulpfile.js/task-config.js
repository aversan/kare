module.exports = {
  html: true,
  images: true,
  fonts: true,
  static: true,
  svgSprite: true,
  ghPages: true,
  stylesheets: true,

  javascripts: {
    entry: {
      // files paths are relative to
      // javascripts.dest in path-config.json
      app: ['./app.js'],
      catalog: ['./catalog.js'],
      product: ['./product.js'],
      checkout: ['./checkout.js'],
    },
  },

  browserSync: {
    // proxy: 'http://kare.dev.rockettool.ru/',
    files: ['public/**'],
    serveStatic: ['public'],
    rewriteRules: [
      {
        match: new RegExp(/local\/templates\/kare_redisign\/css\/app\.css/),
        fn() {
          return 'stylesheets/app.css';
        },
      },
    ],
    server: {
      // should match `dest` in
      // path-config.json
      baseDir: 'public'
    }
  },

  production: {
    rev: false,
  },
};
