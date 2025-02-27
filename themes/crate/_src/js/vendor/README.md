# Vendor Javascript directory #

Any Javascript files from external sources (that aren't available as Bower or Node modules) may be added here.

## Example ##

You've downloaded a script you want to use, called jquery.whatever.js.

Open it up in an editor. If it doesn't already contain a 'module.exports = ...' statement for Browserify, you should manually wrap the existing code in one:

	module.exports = function() {
		/* ORIGINAL CODE GOES HERE */
	};

Then save the file into this directory.

You can now include this script in another script in the \_src/js directory like this:

	var whatever = require( './vendor/jquery.whatever.js' );
	whatever();
