const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    // Main entry
    .addEntry('app', './assets/app.js')

    // Util entries
    .addEntry('datatables', './assets/scripts/datatables.js')

    // Page Entries
    .addEntry('login', './assets/pages/login.js')
    .addEntry('dashboard', './assets/pages/dashboard.js')
    .addEntry('product', './assets/pages/product.js')
    .addEntry('productGroup', './assets/pages/productGroup.js')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .enableSassLoader()
    .autoProvidejQuery()

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

const config = Encore.getWebpackConfig();

config.module.rules.unshift({
    parser: {
        amd: false,
    }
});

module.exports = config;