<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blobaustriavoting: http://ogp.me/ns/fb/blobaustriavoting#">
        <meta charset="utf-8">

    <title>Blob Austria Voting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo Request::root() ?>/css/main.css?asd87a6sdg">
    <script src="<?php echo Request::root() ?>/js/jquery.js" type="text/javascript"></script>
         
        
          <meta property="fb:app_id" content="458082764272869" /> 
          
          <?php if (isset($item)) { ?>
          
          <meta property="og:type"   content="blobaustriavoting:location" /> 
          <meta property="og:url"    content="{{ Request::root() }}/items/{{ $item->id }}" /> 
          <meta property="og:title"  content="{{ $item->name }}" />
           <meta property="og:description"  content="{{ $item->body }}" /> 
          <meta property="og:image"  content="{{ Request::root() }}/photos/{{ $item->image }}" /> 
          <?php } ?>
        <style>
            table form { margin-bottom: 0; }
            form ul { margin-left: 0; padding-left: 0; list-style: none; }
            .error { color: red; font-style: italic; }
        </style>
    </head>

    <body>
    
    <div class="header">
  	<div class="pull-left logoholder"><a target="_parent" href="<?php echo Request::root() ?>"><img src="<?php echo Request::root() ?>/img/logo.jpg?123" class="logo" alt="BLOB"></a></div>
    <div class="pull-left headlineholder"><h1>Hol dir jetzt dein Blob-Event</h1><h2>Mit DJ, Musik uvm. an deine Badelocation!</h2>
    
    
    </div>
    
  </div>

        <div class="container">
            @if (Session::has('flash_message'))
                <div class="flash alert">
                    <p>{{ Session::get('flash_message') }} <?php Session::forget('flash_message'); ?></p>
                </div>
            @endif

            @yield('main')
        </div>

<p style="margin:  20px;color:#666;font-size: 11px;float:left;">   
Diese Promotion steht in keiner Verbindung zu Facebook und wird in keiner Weise von Facebook gesponsert, unterstützt oder organisiert. Der Empfänger der von Ihnen bereitgestellten Informationen ist nicht Facebook, sondern <a href="http://www.blob-europe.com/" target="_top">Blob-Austria</a>.</p>

    </body>

</html>