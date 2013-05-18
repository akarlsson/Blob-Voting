@extends('layouts.scaffold')

@section('main')

<?php 

$user = $facebook->getUser();
$signed_request = $facebook -> getSignedRequest();

$fblink = Request::root();


if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
	$params = array(
	  'canvas'    => 1,
	  'fbconnect' => 0,
	  'scope' => 'user_likes,publish_actions',
	  'redirect_uri' => Request::root()
	);	
  $loginUrl = $facebook->getLoginUrl($params);
}

?>

  <div id="fb-root"></div>
    <script>
		
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1&appId=<?php echo Config::get('app.fbappid') ?>";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
		
      window.fbAsyncInit = function() {
        //FB.init({
        //  appId: '<?php echo Config::get('app.fbappid') ?>',
        //  cookie: true,
        //  xfbml: true,
        //  oauth: true
        // });
		
		
		$('#auth-loginlink').click(function(){
			FB.login(function (response) { 
				top.location.href = "<?php echo $fblink ?>";
				//alert("yeah!"); 
				}, { 
				scope:'user_likes,publish_actions'
				});
                
            });
		
        FB.Event.subscribe('auth.logout', function(response) {
          top.location.href = "<?php echo $fblink ?>";
        });
		
		FB.Event.subscribe('edge.create', function(response) {
			top.location.href = "<?php echo $fblink ?>";
		});
		
		FB.Canvas.setAutoGrow();
		
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
	  </script>


<?php 
 if ($user) { 
	 
	$likes = $facebook->api("/me/likes/".Config::get('app.fbpageid'));
  
 	if( !empty($likes['data']) ) {
		?>
        
        <p style="margin:  20px;color:#666;font-size: 11px;"> 
    Lade ein Foto deiner Location hoch und gewinne ein eigenes Blobbing-Event. <strong>Steigere deine Chancen indem du deine Freunde animierst, für die Location zu stimmen. </strong> Nachdem wir deinen Eintrag geprüft haben und den Kriterien entspricht, kann von 24.5.2013 bis 30.06.2013 für deine Location abgestimmt werden. <br /><br />Fotos, die der Idee widersprechen werden ausnahmslos gelöscht. Der/Die GewinnerIn wird auf unserer Fanpage bekannt gegeben und von uns persönlich verständigt. Der Event wird als Ein-Tages-Veranstaltung organisiert. Wert: EUR 3.000 (Auf-, Abbau, Personal, Organisation, Sprungturm (unter Vorbehalt, hier müssen wir erst die Bedingungen direkt am See klären ob ein Aufbau möglich ist ), DJ, Musik, Blob). Der Gewinn ist nicht in bar ablösbar. Der Rechtsweg ist ausgeschlossen.</p>

{{ Form::open(array('route' => 'items.store', 'files' => 'true', 'class'=>'bewerbung', 'id'=>'bewerbung')) }}
<fieldset>
<legend>Mach mit und gewinn dein Blob-Event!</legend>
    <ul>
        <li>
            {{ Form::label('name', 'Badelocation') }}
            {{ Form::text('name','', array('style'=>'width:400px;padding: 5px;font-size: 20px;')) }}
            <hr />
        </li>

        <li>
            {{ Form::label('body', 'Warum willst du den Blob?') }}
            {{ Form::textarea('body') }}
            <hr />
        </li>

        <li>
            {{ Form::label('image', 'Bild') }}
            {{ Form::file('image') }}
            <p><small>Das Bild sollte als Endung .jpg, Breitformat und nicht mehr als 5 Mb haben.</small></p>
            <hr />
        </li>

        <li>
            {{ Form::label('author', 'Dein Name') }}
            {{ Form::hidden('author', $user) }}
            {{ Form::text('user', $user_profile["name"], array('disabled'=>'disabled')) }}
        <hr />
        </li>
        <li>
        	{{ HTML::image(Captcha::img(), 'Captcha image', array('id'=>'captchaimage', 'style'=>'vertical-align:middle')) }} <a href="#" class="btn" onclick="$('#captchaimage').attr('src','<?php echo Request::root() ?>/captcha?{{ rand(0,9999999)}}');return false;">Code neu laden</a><br  />
            {{ Form::text('captcha','',array('placeholder'=>'Code eingeben','style'=>'width:110px;padding:5px;')) }}
        </li>

        <li>
            
            <a href="#" onclick="$('#bewerbung').css('opacity','.5');$('#bewerbung').submit();return false;" class="votebutton" style="border:none;">Bewerben</a>
        </li>
    </ul>
    </fieldset>
{{ Form::close() }}

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif

  <?php
  } else { 
 	echo '<h1>Hol dir deinen Blob!</h1>';
  	echo '<p>Um mitspielen zu können, musst du zuerst diese Seite liken.</p>';
	echo '<div class="fb-like-box" data-href="'.Config::get('app.fbpagelink').'" data-width="292" data-show-faces="false" data-stream="false" data-header="true"></div>';
  }
  
  
  } else { ?>
  <h1>Hol dir deinen Blob!</h1>
  <p> <a href="<?php echo $loginUrl ?>" target="_top" class="votebutton">Jetzt anmelden und mitspielen</a></p>
    <?php } ?>

@stop


