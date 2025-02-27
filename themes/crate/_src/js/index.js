'use strict';

var $ = require('jQuery');
var mobileMenu  = require('./mobile-menu');
var searchModal = require('./search-modal');
var stickybits  = require('stickybits');

mobileMenu.toggle();
searchModal.clear();
searchModal.toggle();

var ajaxSetup = require('./ajax-cs');
ajaxSetup.ajaxArchives();



$(document).ready(function () {

	//event page tribe divider hide
	//functions are not optimized well to handle if there is content before dividers or not
	if ($('body').hasClass('events-single')){
		if (!$('.tribe-events-schedule .recurringinfo').length){
			$('.tribe-events-schedule span.tribe-events-divider').hide();
		} else {
			if($('.tribe-events-schedule .recurringinfo span.tribe-events-divider').is(':first-child')){
				$('.tribe-events-schedule .recurringinfo span.tribe-events-divider').hide();
			}
		}
	}

});

stickybits( '#issue-bar', {useStickyClasses: true} );
