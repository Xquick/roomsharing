<!DOCTYPE HTML>
<html lang="cs">
<head>
    <script type="text/javascript" src="/js/jquery.js"></script>

    <title><?php echo $meta['title'] ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $meta['description'] ?>">
    <meta name="keywords" content="<?php echo $meta['keywords'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Robots" content="index,follow">
    <meta name="author" content="">
    <meta name="revisit-after" content="7 days">

    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/jquery-ui-1.10.4.custom.css">
    <link rel="stylesheet" href="/styles/prettyPhoto.css">
    <link rel="stylesheet" href="/styles/bootstrap.min.css">

    <script src="http://localhost:3919/socket.io/socket.io.js"></script>
    <!--    <script src="http://intense-headland-7332.herokuapp.com/socket.io/socket.io.js"></script>-->

    <script type="text/javascript"
            src="http://maps.google.com/maps/api/js?key=AIzaSyD-5dsjY4pid9JKFD8RydTmL3XxcBBc7UY&libraries=places&sensor=false?language=cz"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script


    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400&subset=latin,latin-ext' rel='stylesheet'
          type='text/css'>


    <script type="text/javascript" src="/js/googlemap.js"></script>
    <script type="text/javascript" src="/js/init.js"></script>
    <script type="text/javascript" src="/js/layout.js"></script>
    <script type="text/javascript" src="/js/script.js"></script>
    <script type="text/javascript" src="/js/jquery.geocomplete.js"></script>
    <script type="text/javascript" src="/js/object.new.js"></script>


        <!--    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->


<!--    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>-->
    <script type="text/javascript" src="/js/facebook.js"></script>
    <script type="text/javascript" src="/js/jquery.autosize.min.js"></script>
    <script type="text/javascript" src="/js/cookies.js"></script>
    <script type="text/javascript" src="/js/gallery.js"></script>
    <script type="text/javascript" src="/js/ad.detail.js"></script>
    <script type="text/javascript" src="/js/ad.new.js"></script>
    <script type="text/javascript" src="/js/wizard.js"></script>
    <script type="text/javascript" src="/js/redirect.js"></script>
    <script type="text/javascript" src="/js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>

    <script type="text/javascript" src="/js/fileupload/vendor/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="/js/fileupload/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="/js/fileupload/jquery.fileupload.js"></script>
    <script type="text/javascript" src="/js/fileupload/fileupload.script.js"></script>

    <script type="text/javascript" src="/js/components/filter.js"></script>
    <script type="text/javascript" src="/js/components/button-close.js"></script>
    <script type="text/javascript" src="/js/components/contact.js"></script>


    <script type="text/javascript" src="/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="/js/scrollbar/jquery.mCustomScrollbar.css"></script>


    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) { ?>
        <script type="text/javascript" src="/js/chat.client.js"></script>
    <?php } ?>



    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-slider.js"></script>

</head>

