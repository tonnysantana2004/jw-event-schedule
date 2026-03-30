const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemovePlugin = require('remove-files-webpack-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const { glob } = require('glob');
const path = require('path');

const pluginsToRemove = [
    'RtlCssPlugin'
];

async function getEntries() {

    const files = await glob(
        './resources/**/*',
        {
            ignore: ['**/blocks/**','**/_*.scss'],
            nodir: true
        })

    let customEntries = {};

    files.forEach(file => {
        
        // use only "/"
        const normalized = file.split(path.sep).join('/');

        // remove the base folder
        const relative = normalized.replace(/^\.\/resources\//, '')
        .replace('resources/', '')
        // rename the scss folder to css
        .replace('scss/','css/');

        // remove file extension
        const name = relative.replace(/\.(js|scss|png|webp|svg|jpg|php|json)$/, '');

        customEntries[name] = './' + normalized;

    });
    console.log(customEntries)
    return customEntries;

}

module.exports = async () => {

    const customEntries = await getEntries();
    
    return {
        ...defaultConfig,

        entry: {
            ...defaultConfig.entry(),
            ...customEntries,
        },

        optimization: {
            ...defaultConfig.optimization,
            splitChunks: {
                ...defaultConfig.optimization.splitChunks,
                cacheGroups: {
                    ...defaultConfig.optimization.splitChunks.cacheGroups,
                    style: {
                        ...defaultConfig.optimization.splitChunks.cacheGroups.style,
                        test: /[\\/]blocks[\\/].*[\\/]style(\.module)?\.(pc|sc|sa|c)ss$/, 
                        // Just apply the changes if 
                        // the file is inside a block folder
                    },
                    default: false,
                },
            },
        },

        plugins: [
            ...defaultConfig.plugins,
            new RemoveEmptyScriptsPlugin(),

            // Remove files asset.php
            new RemovePlugin({
                after: {
                    allowRootAndOutside: true,
                    test: [
                        {
                            folder: './build',
                            method: (absoluteItemPath) => {
                                return /\.asset\.php$/.test(absoluteItemPath) &&
                                    !/[\\/]blocks[\\/]/.test(absoluteItemPath);
                            },
                            recursive: true,
                        }
                    ],
                },
            }),
        ].filter((plugin) => !pluginsToRemove?.includes(plugin?.constructor?.name)), // remove unwanted plugins

        output: {
            ...defaultConfig.output,
            filename: '[name].js',
            clean: true
        },

        module: {
            ...defaultConfig.module,
            rules: [
                ...defaultConfig.module.rules,
                {   // remove cache string from images
                    test: /\.(png|jpg|jpeg|webp|avif|gif|svg|woff2)$/i,
                    type: 'asset/resource',
                    generator: {
                        filename: (pathData) => {
                            return pathData.filename
                                .replace(/^resources[\\/]/, '');
                        }
                    }
                }
            ]
        }
    }
};