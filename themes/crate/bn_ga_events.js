
function was_clickSubmitNewsletterSignUp ( origin ) {
  //alert(origin);
  if ( "function" !== typeof(ga) ) {
  	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-32668307-1', 'auto' ,'bnTracker');
  }
  //alert("are we alive?");
  //alert(typeof(ga));

  switch (origin) {
  	case "header":
  		//alert("send ga Header Newsletter Signup");
  		ga('bnTracker.send', 'event', 'Newsletter Signup', 'Header Newsletter Signup', 'Header Newsletter Signup');
  		break;
  	case "sidebar":
  	  //alert("send ga Sidebar Newsletter Signup");
  	  ga('bnTracker.send', 'event', 'Newsletter Signup', 'Sidebar Newsletter Signup', 'Sidebar Newsletter Signup');
  		break;
  	case "footer":
  	  //alert("send ga Footer Newsletter Signup");
  		ga('bnTracker.send', 'event', 'Newsletter Signup', 'Footer Newsletter Signup', 'Footer Newsletter Signup');
  		break;
  	case "article":
  		ga('bnTracker.send', 'event', 'Newsletter Signup', 'Article Newsletter Signup', 'Article Newsletter Signup');
  		break;

  }
  
  //alert("done with ga send");
}

function clickSubscribeButton ( origin ) {
  if ( "function" !== typeof(ga) ) {
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-32668307-1', 'auto');
  }
  switch ( origin ) {
    case "article" :
      ga('send', 'event', 'Subscribe Button Click', 'Article Subscribe Click', 'Article Subscribe Click');
      break;
    case "header":
      ga('send', 'event', 'Subscribe Button Click', 'Header Subscribe Click', 'Header Subscribe Click');
      break;
    case "footer":
      ga('send', 'event', 'Subscribe Button Click', 'Footer Subscribe Click', 'Footer Subscribe Click');
        break;
  }
}

function clickDonateButton ( origin ) {
  if ( "function" !== typeof(ga) ) {
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-32668307-1', 'auto');
  }
  switch ( origin ) {
    case "article" :
      ga('send', 'event', 'Donate Button Click', 'Article Donate Click', 'Article Donate Click');
      break;
    case "header":
      ga('send', 'event', 'Donate Button Click', 'Header Donate Click', 'Header Donate Click');
      break;
    case "footer":
      ga('send', 'event', 'Donate Button Click', 'Footer Donate Click', 'Footer Donate Click');
        break;
  }
}