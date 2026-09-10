const fs = require('fs');
const glob = require('glob');
const path = require('path');

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
					const files = glob.sync(this.pattern).sort(); // orden alfabético

					const fragments = files.map((file) => {
						let content = fs.readFileSync(file, 'utf8').trim();

						if (content.toLowerCase().startsWith('<?php')) {
							content = content.slice(5).trim(); // saca "<?php"
						}
						if (content.endsWith('?>')) {
							content = content.slice(0, -2).trim(); // saca "?>"
						}

						return `// //////////////////////////////////////////// ${path.basename(file)} ---\n${content}`;
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

