# About this directory
This img/ directory supports a number of magic directories that gulp watches and manipulates. Those behaviors are as follows:

## optimize/media-img/
Files saved into this directory will be imported into WordPress as media posts using wp-cli. They will then be deleted from this directory.

## optimize/template-img/
PNG and JPG files saved into this directory will be losslessly compressed and optimized for web viewing and placed in crate/images.

## optimize/processed/
This directory will be used to backup original unminified template images added to optimize/template-img.

## sprite/
PNG and JPG files saved into this directory will be optimized and then turned into inline CSS data, assigned to a class of icon-[filename-without-extension], and defined in 
 crate/_src/scss/general/_sprite.scss
 
## svg/
SVG files saved into this directory will be optimized and then turned into inline CSS data, assigned to a class of icon-[filename-without-extension], and defined in 
 crate/_src/scss/general/_icons.scss. They will *also* be injected into header.php as <symbol> elements with ids of icon-[filename-without-extension].
