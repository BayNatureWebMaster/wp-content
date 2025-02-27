'use strict';

var $ = require('jQuery');

var ajaxArchives = function( buttonText ) {

    var $selector = jQuery('.archive-nav'),
        buttonText  = buttonText || "Show More",
        currentPage = jQuery('.ajax-archive').data('current-page') || 1;

    // if we have nothing to do — no archive or no next button — stop.
    if ( ! $selector.length || ! jQuery('.next', $selector).length ) return;

    // hide the paginated nav
    jQuery('.page-numbers', $selector).hide();

    // find the URL of the 'next' button within the paginated links, making sure it's relative
    var nextButtonHref = '/' + jQuery('.next', $selector).attr('href').replace(/^(?:\/\/|[^\/]+)*\//,"");

    // inject our ajax-powered button into the archive nav
    var $button = jQuery('<button class="archive-ajax-next" data-url="' + nextButtonHref + '">' + buttonText + '</button>').appendTo( $selector );

    // watch for clicks on our button, load new content via ajax when clicked and update URL to fetch.
    $button.on('click', function() {

        var $this = jQuery(this),
            ajaxUrl = $this.data('url');

        // set a spinner and change text
        $this.html('Loading...');
        $this.addClass('ajax-loading');

        // load the new content...
        jQuery.get( ajaxUrl, function( data ) {

            // hide the loader
            $this.removeClass('ajax-loading').trigger('blur').text( buttonText );

            // fetch what we need
            var $page = jQuery.parseHTML( data ),
                $newItems = jQuery('.ajax-archive > *', $page),
                $nextButton = jQuery( '.archive-nav > .next', $page );

            // inject into DOM and update lazyloader
            $newItems.appendTo('.ajax-archive');
            //sr.sync();
            //myLazyLoad.update();

            // update the button URL to the next url, or hide it.
            if ( ! $nextButton.length ) {
                $button.hide();
            } else {
                var nextUrl = '/' + jQuery( '.archive-nav > .next', $page ).attr('href').replace(/^(?:\/\/|[^\/]+)*\//,"");
                $button.data('url', nextUrl );
            }

        });

    });

}

module.exports = {
    ajaxArchives: ajaxArchives,
}