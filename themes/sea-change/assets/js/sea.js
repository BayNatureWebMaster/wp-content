(function($) {

    // Waypoints
    var inviewLanding = new Waypoint.Inview({
        element: $('#landing')[0],
        enter: function(direction) {
            //alert('Enter triggered with direction ' + direction)
            $('.footer').hide();
        },
        offset: function() {
            return -this.element.clientHeight
        }
    })

    var inviewRead = new Waypoint.Inview({
        element: $('#read'),
        enter: function(direction) {
            $('.read-nav').css('color','#5A69C6');
            $('.watch-nav').css('color','#FFF');
            $('.act-nav').css('color','#FFF');
            $('.about-nav').css('color','#FFF');
            $('.footer').show();
        },
        offset: function() {
            return 0;
        }
    })
    var inviewWatch = new Waypoint.Inview({
        element: $('#watch'),
        enter: function(direction) {
            $('.read-nav').css('color','#FFF');
            $('.watch-nav').css('color','#5A69C6');
            $('.act-nav').css('color','#FFF');
            $('.about-nav').css('color','#FFF');
        },
        offset: function() {
            return 20;
        }
    })
    var inviewAct = new Waypoint.Inview({
        element: $('#act'),
        enter: function(direction) {
            $('.read-nav').css('color','#FFF');
            $('.watch-nav').css('color','#FFF');
            $('.act-nav').css('color','#5A69C6');
            $('.about-nav').css('color','#FFF');
        },
        offset: function() {
            return 0;
        }
    })
    var inviewAbout = new Waypoint.Inview({
        element: $('#about'),
        enter: function(direction) {
            $('.read-nav').css('color','#FFF');
            $('.watch-nav').css('color','#FFF');
            $('.act-nav').css('color','#FFF');
            $('.about-nav').css('color','#5A69C6');

        },
        offset: function() {
            return -this.element.clientHeight
        }
    })

    // scrolling animation

    $( '.arrow-down').click(function() {
        $( '#read' ).animatescroll();
    });

    $( '.read-nav').click(function() {
        $( '#read' ).animatescroll();
    });

    $( '.watch-nav').click(function() {
        $( '#watch' ).animatescroll();
    });

    $( '.act-nav').click(function() {
        $( '#act' ).animatescroll();
    });

    $( '.about-nav').click(function() {
        $( '#about' ).animatescroll();
    });

})(jQuery);
