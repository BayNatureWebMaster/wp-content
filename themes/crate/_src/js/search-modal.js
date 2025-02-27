'use strict';

var $ = require('jQuery');

// $('').on('click', function () {

// });

var clear = function () {

	$(document).ready(function () {
		$('.site-search-form input[type="search"]').val('');
	});
}

var toggle = function () {

	function openSearchModal(e) {
		e.preventDefault();
		e.stopPropagation();
		$('body').removeClass('menu-open');
		$('.site-search-form').addClass('active');
		$('.close-search').one('click', closeSearchModal);
	}

	function closeSearchModal(e) {
		e.preventDefault();
		e.stopPropagation();
		$('.site-search-form').removeClass('active');
		$('a[href="#search"]').one('click', openSearchModal);
	}

	$('a[href="#search"]').one('click', openSearchModal);
	$('.close-search').one('click', closeSearchModal);
}

module.exports = {
	clear: clear,
	toggle: toggle,
}
