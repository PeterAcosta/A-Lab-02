
const { src , dest , watch , series , parallel } = require('gulp');
const uglify = require('gulp-uglify');
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');
const concat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');
const htmlmin = require('gulp-html-minifier2');
const replace = require('gulp-replace');
const insert = require('gulp-insert');
const removeEmptyLines = require('gulp-remove-empty-lines');
const trimlines = require('gulp-trimlines');
const header = require('gulp-header');



const distoPath       = 'www/'
const concatFileName = '_main'
const concatPHPFunctionFileName = '_functions'

const CSSfiles       = 'sources/CSS/*.css'
const JSfiles        = 'sources/JS/*.js'
const HTMLfiles      = 'sources/HTML/*.html'
const PHPfunctions   = 'sources/PHP-functions/'
const PHPclasses     = 'sources/PHP-classes/'


// CSS 
function min_css(){
    return src( CSSfiles )
        .pipe(sourcemaps.init())
        .pipe(concat( concatFileName + '.css' ))
        .pipe(cleanCSS())
        .pipe(rename({ extname: '.min.css' }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest( distoPath ));
}

// JS
function min_js(){
    return src( JSfiles )
        .pipe(sourcemaps.init())
        .pipe(concat( concatFileName + '.js' ))
        .pipe(uglify())
        .pipe(rename({ extname: '.min.js' }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest( distoPath ));
}


// HTML minify :  https://github.com/kangax/html-minifier
function min_html(){
    return src( HTMLfiles )
        .pipe(htmlmin(
            {
                collapseWhitespace: true,
                minifyCSS: true,
                minifyJS:true,
                removeComments:true
            }))
        .pipe(dest( distoPath ));
}


//// PHP -> muchos archivos a 1 
function min_php_functions(){
    return src( PHPfunctions + '*.php' )
        .pipe(replace('<?php', ''))
        .pipe(trimlines())                          // quita espacios y tabs al principio
        .pipe(replace(/^#(.*)/gm, ''))              // quita comentarios simple linea " # ..."
        .pipe(replace(/\/\*[\s\S]*?\*\//g, ''))     // quita comentarios multilinea   "/* .... */"
        .pipe(replace(/\/\/.*/g, ''))               // quita comentarios simple linea "// ...."
        .pipe(removeEmptyLines())                   // quita las lineas vacias

        // Reemplaza comentario "#"  y "//"  al final de una linea de codigo 
        .pipe(replace(/(.+?)(;\s*#+|;\s*\/\/).*(\n|$)/g, function (match, group1) {return group1 + ';\n';}))

        .pipe(replace(/;\s*\n}\s*\n/g, ';}\n'))     // Reemplaza "....; \n }"   por ";}"
        .pipe(replace(/}\s*\n}/g, '}}'))            // Reemplaza "....} \n }"   por "}}"
        .pipe(replace(/}\s*\n}/g, '}}'))            // Reemplaza "....} \n }"   por "}}"
        .pipe(replace(/ ;/g, ';'))                  // Realiza la sustitución para " ;"  por ";"
        .pipe(replace(/\n\s*{/g, '{'))              // Reemplaza salto de línea seguido de '{'
        .pipe(replace(/\) \{/g, '){'))              // Realiza la sustitución ") {"  por "){"

        .pipe(trimlines())                          // quita espacios y tabs al principio
        .pipe(removeEmptyLines())                   // quita las lineas vacias
        .pipe(replace(/function\s/g, '\nfunction '))
        .pipe(header(`\n#---------------------------------------------------------- source: ${PHPfunctions}<%= file.basename %> \n`))
        .pipe(concat( concatPHPFunctionFileName + '.php' ))    // Concatena los archivos 
        .pipe(insert.prepend('<?php\n'))            // Agrega el tag "<?php" al inicio del archivo
        .pipe(dest( distoPath ));
}




//// PHP -> 1  a 1 
function min_php_classes(){
    return src( PHPclasses + '*.php' )
        .pipe(trimlines())                          // quita espacios y tabs al principio
        .pipe(replace(/^#(.*)/gm, ''))              // comentarios simple linea " # ..."
        .pipe(replace(/\/\*[\s\S]*?\*\//g, ''))     // comentarios multilinea   "/* .... */"
        .pipe(replace(/\/\/.*/g, ''))               // comentarios simple linea "// ...."
        .pipe(removeEmptyLines())                   // quita las lineas vacias

        // Reemplaza comentario "#"  y "//"  al final de una linea de codigo 
        .pipe(replace(/(.+?)(;\s*#+|;\s*\/\/).*(\n|$)/g, function (match, group1) {return group1 + ';\n';}))

        .pipe(replace(/;\s*\n}\s*\n/g, ';}\n'))     // Reemplaza "....; \n }"   por ";}"
        .pipe(replace(/}\s*\n}/g, '}}'))            // Reemplaza "....} \n }"   por "}}"
        .pipe(replace(/}\s*\n}/g, '}}'))            // Reemplaza "....} \n }"   por "}}"
        .pipe(replace(/ ;/g, ';'))                  // Realiza la sustitución para " ;"  por ";"
        .pipe(replace(/\n\s*{/g, '{'))              // Reemplaza salto de línea seguido de '{'
        .pipe(replace(/\) \{/g, '){'))              // Realiza la sustitución ") {"  por "){"

        .pipe(trimlines())                          // quita espacios y tabs al principio
        .pipe(removeEmptyLines())                   // quita las lineas vacias 
        .pipe(dest( distoPath + 'classes'));
}




// exports para que puedan ser ejecutadas fuera de este archivo
exports.min_css   = min_css  ;
exports.min_js    = min_js   ;
exports.min_htmls = min_html ;
exports.min_php_functions = min_php_functions ;
exports.min_php_classes   = min_php_classes   ;
exports.all = parallel( min_js , min_css , min_html , min_php_functions , min_php_classes );



// Watching Files : https://gulpjs.com/docs/en/getting-started/watching-files
exports.default = function() {
    watch( CSSfiles  , {ignoreInitial:false} , min_css  );
    watch( JSfiles   , {ignoreInitial:false} , min_js   );
    watch( HTMLfiles , {ignoreInitial:false} , min_html );

    watch( PHPfunctions + '*.php' , {ignoreInitial:false} , min_php_functions );
    watch( PHPclasses   + '*.php' , {ignoreInitial:false} , min_php_classes   );


};

