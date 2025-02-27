'use strict';

var $ = require('jQuery');

// $('').on('click', function () {

// });

var toggle = function () {

	function openMobileMenu() {
		$('body').addClass('menu-open');
		$(this).one('click', closeMobileMenu);
		$('a[href="#search"]').one('click', closeMobileMenu);
	}

	function closeMobileMenu() {
		$('body').removeClass('menu-open');
		$(this).one('click', openMobileMenu);
	}

	$('.menu-toggle').one('click', openMobileMenu);
}

module.exports = {
	toggle: toggle,
}
