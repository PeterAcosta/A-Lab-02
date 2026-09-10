const fs = require('fs');
const glob = require('glob');
const path = require('path');
const { execFileSync } = require('child_process');

class PhpFunctionsBundlePlugin {
    constructor({ pattern, outputFilename }) {
        this.pattern = pattern;
        this.outputFilename = outputFilename;
    }

    apply(compiler) {
        compiler.hooks.thisCompilation.tap('PhpFunctionsBundlePlugin', (compilation) => {
            compilation.hooks.processAssets.tap(
                {
                    name: 'PhpFunctionsBundlePlugin',
                    stage: compiler.webpack.Compilation.PROCESS_ASSETS_STAGE_ADDITIONAL,
                },
                () => {
                    const files = glob.sync(this.pattern).sort();

                    const fragments = files.map((file) => {
                        // tokeniza el archivo de verdad y saca comentarios + whitespace innecesario
                        let content = execFileSync('php', [
                            '-r', 'echo php_strip_whitespace($argv[1]);', '--', file,
                        ]).toString().trim();

                        if (content.toLowerCase().startsWith('<?php')) {
                            content = content.slice(5).trim();
                        }
                        if (content.endsWith('?>')) {
                            content = content.slice(0, -2).trim();
                        }

                        return `// //////////////////// ${path.basename(file)} ---\n${content}`;
                    });

                    const finalContent = '<?php\n\n' + fragments.join('\n\n');

                    compilation.emitAsset(
                        this.outputFilename,
                        new compiler.webpack.sources.RawSource(finalContent)
                    );

                    files.forEach((file) => compilation.fileDependencies.add(path.resolve(file)));
                }
            );
        });
    }
}

module.exports = PhpFunctionsBundlePlugin;