const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const ENTRIES = ["home", "base"];

Encore
    .setOutputPath('./Public/assets/')
    .setPublicPath('/assets')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild(["*.js", "*.css"])
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    .enableLessLoader()
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

for (let entry in ENTRIES) {
    Encore.addEntry(ENTRIES[entry], `./Assets/${ENTRIES[entry]}.js`);
}

module.exports = Encore.getWebpackConfig();