require('dotenv').config({ path: '../.env' });

console.log("Theme base:", base);
console.log("CSS dest:", path.sass.dest);
console.log("JS dest:", path.js.dest);

const themeName = process.env.THEME_NAME;
const siteUrl = process.env.SITE_URL;

const esbuild = require('esbuild');

const gulp            	= require( 'gulp' ),
			sass = require('gulp-dart-sass'),
			autoprefixer  		= require( 'autoprefixer' ),
			sortMediaQueries  = require( 'postcss-sort-media-queries' ),
			sourcemaps  			= require( 'gulp-sourcemaps' ),
			postcss 		  		= require( 'gulp-postcss' ),
			postcssImport = require('postcss-import'),
			cssnano 		  		= require( 'cssnano' ),
			combineQueries  	= require( 'postcss-combine-media-query' ),
			combineSelectors	= require( 'postcss-combine-duplicated-selectors' ),
			browserSync   		= require( 'browser-sync' ),
			plumber       		= require( 'gulp-plumber' ),
			pug           		= require( 'gulp-pug' ),
			rename        		= require( 'gulp-rename' ),
			svgSprite 		  	= require( 'gulp-svg-sprite' ),
			zip           		= require( 'gulp-zip' ),
			fs 								= require('fs'),
			pathModule 				= require('path')


/* ------------------------------------------------------------------------- *\
**  === PATHS                                                                *|
\* ------------------------------------------------------------------------- */
let base = "../web/wp-content/themes/" + themeName;

let path = {
	modules: {
		src: './src/pug/modules/*.php',
		dest: base +'/modules/',
	},
	components: {
		src: './src/pug/components/*.pug',
		dest: base +'/components/',
		watch: './src/pug/components/*.pug'
	},
	sass: {
		src: './src/sass/main.sass',
		dest: base +'/assets/css',
		watch: ['./src/sass/**/*.sass', './src/sass/**/*.css', './src/sass/**/*.scss'],
	},
	js: {
		src: './src/js/*.js',
		dest: base +'/assets/js'
	},
	jsplugins: {
		src: './src/js/plugins/*.js',
		dest: base +'/assets/js'
	},
	php: {
		src: './src/wordpress/**/*.php',
		dest: base +'/'
	},
	img: {
		src: './src/img/*.*',
		dest: base +'/assets/img'
	},
	svg: {
		src: './src/img/svg/*.svg',
		dest: base +'/assets/img'
	},
	font: {
		src: './src/fonts/*',
		dest: base +'/assets/fonts'
	},
	screenshot: {
		src: './src/wordpress/*.png',
		dest: base +'/'
	},
	style: {
		src: './src/wordpress/style.css',
		dest: base +'/'
	}
}

/* ------------------------------------------------------------------------- *\
**  === BROWSER SYNC                                                         *|
\* ------------------------------------------------------------------------- */
function browser_init(open = false) {
	browserSync.init({
		proxy: "http://" + siteUrl,
		port: 3000,
		open: open,
		ui: false,
		notify: false
	});
}


/* ------------------------------------------------------------------------- *\
**  === TASKS                                                                *|
\* ------------------------------------------------------------------------- */
function php_modules(){
	return gulp.src(path.modules.src)
		.pipe( gulp.dest(path.modules.dest) )
		.pipe( browserSync.stream() )
}
function html_pug_components(){
	return gulp.src(path.components.src)
		.pipe( plumber() )
		.pipe( pug({ pretty: '\t' }) )
		.on( 'error', function (err) { console.log(err) } )
		.pipe( rename({ extname: ".php" }))
		.pipe( gulp.dest(path.components.dest) )
		.pipe( browserSync.stream() );
}

function wordpress_php() {
	return gulp.src(path.php.src)
		.pipe( gulp.dest(path.php.dest) )
		.pipe( browserSync.stream() )
}
 
function style_sass(){
  const plugins = [
    postcssImport(),      
    autoprefixer(),       
  ];

  return gulp.src(path.sass.src)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(
      sass({ loadPaths: ['node_modules'] }).on('error', sass.logError)
    )
    .pipe(postcss(plugins))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(path.sass.dest))
    .pipe(browserSync.stream({ match: '**/*.css' }));
}

function svg_sprite(){
	return gulp.src(path.svg.src)
		.pipe(svgSprite({
			mode 	: {
				symbol: {
					render: {
						css: true,
						scss: true,
					},
					dest: '.',
					sprite: 'sprite.svg',
					example: false
				}
			}
		}))
		.pipe( gulp.dest(path.svg.dest) )
		.pipe( browserSync.stream() )
}

function font_copy() {
	return gulp.src(path.font.src)
		.pipe( gulp.dest(path.font.dest) )
		.pipe( browserSync.stream() )
}

function screenshot_copy() {
	return gulp.src(path.screenshot.src)
		.pipe( gulp.dest(path.screenshot.dest) )
		.pipe( browserSync.stream() )
}

function style_copy() {
	return gulp.src(path.style.src)
		.pipe( gulp.dest(path.style.dest) )
		.pipe( browserSync.stream() )
}

function import_modules_json(done) {
  const jsonContent = fs.readFileSync('modules.json', 'utf-8');
  const data = JSON.parse(jsonContent);

  function createFilesRecursively(obj, basePath = 'src') {
		Object.keys(obj).forEach(key => {
			const currentPath = pathModule.join(basePath, key);
	
			if(typeof obj[key] === 'object') {

				if(obj[key].content) {
					const directoryPath = pathModule.dirname(currentPath);
					
					if(!fs.existsSync(directoryPath)) {
						fs.mkdirSync(directoryPath, { recursive: true });
					}
					fs.writeFileSync(currentPath, obj[key].content);
					console.log(`Fichier ${currentPath} créé/mis à jour.`);
				} else {
					createFilesRecursively(obj[key], currentPath);
				}
			}
		});
	}

  createFilesRecursively(data);

  done();
}

function export_modules_json(done) {
  const rootPath = 'src';
  const ignoredDirectories = ['fonts', 'img'];

	function generateJson(dirPath, modules) {
		const files = fs.readdirSync(dirPath);

		
		files.forEach(file => {
			const filePath = pathModule.join(dirPath, file);
			const stats = fs.statSync(filePath);
			
			const fileType = pathModule.extname(file).substring(1);
			const fileNameWithoutExt = pathModule.basename(file, `.${fileType}`);
			const fullFileName = fileType ? `${fileNameWithoutExt}.${fileType}` : fileNameWithoutExt;

			if(stats.isDirectory() && !ignoredDirectories.includes(file)) {
				modules[file] = {};
				generateJson(filePath, modules[file]);
			} else if(stats.isFile()) {
				const fileContent = fs.readFileSync(filePath, 'utf-8').trim();
				modules[fullFileName] = { content: fileContent };
			}
		})
	}

  const modulesJson = {};
  generateJson(rootPath, modulesJson);
  fs.writeFileSync('modules.json', JSON.stringify(modulesJson, null, 2));
  done();
}


/* ------------------------------------------------------------------------- *\
**  === BUILD                                                                *|
\* ------------------------------------------------------------------------- */
function min_css() {
	const plugins = [
		postcssImport({ path: ['node_modules'] }),
		autoprefixer(),
		cssnano(),
	];

	return gulp.src(path.sass.dest + "/main.css")
		.pipe(postcss(plugins))
		.pipe(rename({ suffix: '.min' }))
		.pipe(gulp.dest(path.sass.dest));
}

function js_bundle(done){
  const isProd = process.env.NODE_ENV === 'production';

  esbuild.build({
    entryPoints: ['./src/js/main.js'],
    bundle: true,
    minify: isProd,
    sourcemap: true,
    target: ['es2019'],
    outfile: path.js.dest + (isProd ? '/main.min.js' : '/main.js'),
  }).then(() => {
    browserSync.reload();
    done();
  }).catch((err) => {
    console.error(err);
    done(err);
  });
}


/* ------------------------------------------------------------------------- *\
**  === GULP EXPORTS                                                         *|
\* ------------------------------------------------------------------------- */
function watch_files() {
	browser_init()

	gulp.watch(path.components.watch, gulp_pug)
	gulp.watch(path.sass.watch, gulp.series(style_sass, min_css))
	gulp.watch('./src/js/**/*.js', js_bundle)
	gulp.watch(path.php.src, wordpress_php)
	gulp.watch(path.modules.src, php_modules)
	gulp.watch(path.svg.src, svg_sprite)
	gulp.watch(path.font.src, font_copy)
	gulp.watch(path.screenshot.src, screenshot_copy)
	gulp.watch(path.style.src, style_copy)
}

const gulp_pug        = gulp.series(html_pug_components),
			gulp_sass       = gulp.series(style_sass),
			gulp_js = gulp.series(js_bundle),
			gulp_php        = gulp.series(wordpress_php),
			gulp_modules    = gulp.series(php_modules),
			gulp_svg        = gulp.series(svg_sprite),
			gulp_font       = gulp.series(font_copy),
			gulp_screenshot = gulp.series(screenshot_copy),
			gulp_style      = gulp.series(style_copy),
			gulp_import_modules_json = gulp.series(import_modules_json),
			gulp_export_modules_json = gulp.series(export_modules_json)

const gulp_dist  = gulp.parallel(gulp_pug, gulp_modules, gulp_sass, gulp_php, gulp_js, gulp_font, gulp_screenshot, gulp_svg, gulp_style),
			gulp_min   = gulp.parallel(min_css),
			gulp_build = gulp.series(gulp_dist, gulp_min),
			gulp_watch = gulp.parallel(gulp_dist, watch_files)

module.exports = {
	pug    : gulp_pug,
	sass   : gulp_sass,
	js     : gulp_js,
	svg    : gulp_svg,
	dist   : gulp_dist,
	watch  : gulp_watch,
	build  : gulp_build,
	import_modules_json: gulp_import_modules_json,
	export_modules_json: gulp_export_modules_json,
	default: gulp_watch
}
