

<?php

Session::put('isadmin', 'false');

$now = date('Y-m-d H:i:s');
$votingstart = Config::get('app.votingstart');
$votingend = Config::get('app.votingend');
$start = date("d.m.Y", strtotime($votingstart));
$starttime = date("H:i", strtotime($votingstart));
$end = date("d.m.Y", strtotime($votingend));
$endtime = date("H:i", strtotime($votingend));


// Facebook Credentials
$facebook = new Facebook(array(
  'appId'  => Config::get('app.fbappid'),
  'secret' => Config::get('app.fbappsecret'),
));

$urlrand = rand(1,300000000);


$fblink = Request::root()."?".$urlrand;


// Get User ID
$user = $facebook->getUser();

//echo "User:".$user;

$signed_request = $facebook -> getSignedRequest();

if (isset($signed_request['page']['admin'])) {

if( $signed_request['page']['admin'] ) {
	echo '<a href="'.Request::root().'/items" class="btn">Admin</a>';
	Session::put('isadmin', 'true');
	}

}



if ($user) {
	
  try {
    $user_profile = $facebook->api('/me');
	echo "<!--".$user."-->";
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}


if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
	$params = array(
	  'canvas'    => 1,
	  'fbconnect' => 0,
	  'scope' => 'user_likes,email',
	  'redirect_uri' => Request::root().'/test_hello'
	);	
  $loginUrl = $facebook->getLoginUrl($params);
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Hol dir den Blob beim Blob Austria Voting!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bewirb dich mit deiner Badelocation für den Blob. Das Foto mit den meisten Stimmen bis 30. Juni 2013 gewinnt!" />
    <meta name="og:title" content="Hol dir den Blob beim Blob Austria Voting!" />
    <meta name="og:url" content="{{ Request::root() }}" />
    <meta name="og:description" content="Bewirb dich mit deiner Badelocation für den Blob. Das Foto mit den meisten Stimmen bis 30. Juni 2013 gewinnt!" />
    <meta name="og:image" content="{{ Request::root() }}/img/blobber.jpg" />
	<link rel="stylesheet" type="text/css" href="{{ Request::root() }}/css/main.css?123akk21212511">
    <script src="{{ Request::root() }}/js/jquery.js" type="text/javascript"></script>
    
  </head>
  <body>
  	
  
  <div class="header">
  	<div class="pull-left logoholder"><a target="_parent" href="{{ Request::root() }}"><img src="{{ Request::root() }}/img/logo.jpg?123" class="logo" alt="BLOB"></a></div>
    <div class="pull-left headlineholder"><h1>Hol dir jetzt dein Blob-Event</h1><h2>Mit DJ, Musik uvm. an deine Badelocation!</h2><p>{{ link_to_route('items.create', 'Jetzt bewerben.');  }} Das Foto mit den meisten Stimmen bis 30. Juni 2013 gewinnt!</p></div>
    @if ($user && (empty($signed_request["app_data"])||isset($_GET["app_data"])) ) 
     <img src="{{ Request::root() }}/img/fb_header.png?123" alt="BLOB Austria" style="float:left;width:100%;margin: 10px 0;" />
    <div style="position:relative;float:left;text-align:center;width:100%;margin: 20px auto 50px auto;"><div><a href="{{ Request::root() }}/items/create" class="votebutton">Jetzt bewerben</a></div><p>oder für eine Badelocation voten:</p></div>
    @endif
  </div>
  
   @if (Session::has('flash_message'))
        <div class="flash alert">
            <p>{{ Session::get('flash_message'); Session::forget('flash_message'); }}</p>
        </div>
   @endif
  
  <div id="fb-root"></div>
  
    <script type="text/javascript">
		
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1&appId={{ Config::get('app.fbappid') }}";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
		
      window.fbAsyncInit = function() {		
		
		 FB.init({
          appId: '{{ Config::get("app.fbappid") }}',
          cookie: true,
          xfbml: true,
          oauth: true
        });
		
		$('#auth-loginlink').click(function(){
			FB.login(function (response) { 
				top.location.href = "{{ $fblink }}";
				}, { 
				scope:'user_likes,publish_actions'
				});
                
            });
		
        FB.Event.subscribe('auth.logout', function(response) {
          top.location.href = "{{ $fblink }}";
        });
		
		FB.Event.subscribe('edge.create', function(response) {
			top.location.href = "{{ $fblink }}";
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
	
			// GET USER DATA AND SAVE IN USER TABLE
			
			if (isset($user_profile["gender"]))
				$user_gender = $user_profile["gender"];
			$user_username = $user_profile["name"];	 
			if (isset($user_profile["first_name"]))
				$user_forename = $user_profile["first_name"];
			if (isset($user_profile["last_name"]))
				$user_lastname = $user_profile["last_name"];
			if (isset($user_profile["email"]))
				$user_email = $user_profile["email"];
			
			$checkuser = User::where('fbid', '=', $user)->count();
					
			if ($checkuser==0) {
					
				$newuser = new User;		
				$newuser->fbid = $user;
				$newuser->username = $user_username;
				if (isset($user_profile["first_name"]))	
					$newuser->forename = $user_forename;
				if (isset($user_profile["last_name"]))
					$newuser->lastname = $user_lastname;
				if (isset($user_profile["gender"]))	
					$newuser->gender = $user_gender;
				if (isset($user_profile["email"]))
					$newuser->email = $user_email;
				$newuser->save();
				
			}
			
	
			// CHECK PARAMETERS
	  
	  
	  if (!empty($signed_request["app_data"])||isset($_GET["app_data"])) {
		
		if (!empty($signed_request["app_data"]))
		 		$par = $signed_request["app_data"];
		if (isset($_GET["app_data"]))
				$par = $_GET["app_data"];
				
		 $id = -1;
		 
		 
		 if (strrpos($par,"vote") !== false) {
			  // VOTE FOR ID
				$id = str_replace("vote","",$par);
			
				$checkvote = Vote::where('fbid', '=', $user)->where('item_id', '=', $id)->count();
			
				if ($checkvote!=0) {
				
					echo "<h1>Hol dir deinen Blob!</h1><p class='error'>Du hast für diesen Teilnehmer bereits abgestimmt.</p>";
				
		 		} else {
			
					$vote = new Vote;		
					$vote->fbid = $user;
					$vote->item_id = $id;
					
					$vote->save();
					
					echo "<h1>Hol dir deinen Blob!</h1><p class='message'>Danke für deine Stimme.</p>";
					
					/*$response = $facebook->api(
					  'me/blobaustriavoting:vote',
					  'POST',
					  array(
						'location' => "http://app.blueberrymedia.at/blob/items/".$id
					  )
					);*/
							
					
				}
				echo "<div style='position:relative;float:left;text-align:center;width:100%;margin: 20px auto 50px auto;'><div><a href='".$fblink."&app_data=detail".$id."' target='_parent' class='votebutton'>Zurück zum Voting</a></div></div>";
		  }
			
			
			  
		  if (strrpos($par,"detail") !== false) {
			  
			  // DETAIL VIEW OF ITEM
			  
			  $id = str_replace("detail","",$par);
			   
			  // VIEW AN ITEM
	  
			  $item = Item::find($id);
			  
			  if (is_object($item)&&$item->active==1) {
			  
			  $votes = Vote::where('item_id','=',$item->id)->count();
			  $sender = json_decode(file_get_contents('http://graph.facebook.com/'.$item->author))->name;
			  
			?> 
				<p>&nbsp;</p>
				<p>
                <div class="imgholder detailview"  style="background-image:url({{ Request::root() }}/photos/{{ $item->image }});">
				<h1>{{ $item->name }}</h1>
                    <div class="voting">
                     {{ $votes }}<br />
                     <span class="votes">Votes</span>
                    </div>
                </div>
				
                <p class="subline">Eingeschickt von <a href="http://facebook.com/{{ $item->author }}" target="_blank">{{ $sender }}</a></p>
                
				<p class="buttons">       
               @if ($votingstart <= $now && $votingend >= $now)	
				  <a id="vote" class="votebutton" rel="{{ $item->id }}">Für mich voten</a>
               @else                	
                  <script>alert('Das Voting startet am {{ $start }} um {{ $starttime }} und endet am {{ $end }} um {{ $endtime }}.');</script>
                  Das Voting startet am {{ $start }} um {{ $starttime }} und endet am {{ $end }} um {{ $endtime }}.
               @endif
				  <a id="recommend" class="votebutton recommend" rel="{{ $item->id }}">Weiterempfehlen</a>
				  <a id="post" class="votebutton post" rel="{{ $item->id }}">Auf meiner Pinnwand posten</a>
				  <a id="send" class="votebutton send" rel="{{ $item->id }}">Senden</a>
				</p>
                
                
                <div class="fbcomments">
                	<div class="fb-comments" data-href="{{ Request::root() }}/items/{{ $item->id }}" data-width="470" data-num-posts="10"></div>
                </div>
				
				  <script>
					  $(document).ready(function() {
						  
						   $("a#vote").click(function() {
								FB.login(function(response) {
									if (response.authResponse) {						
										top.location.href = "{{ $fblink }}&app_data=vote{{ $item->id }}";
									}
								});
								
							});
										
							$("a#recommend").click(function() {
								FB.ui({
									method: "apprequests", 
									title: ("{{ $item->name }}"), 
									message: ("Gewinnen wir ein Blob-Event für {{ $item->name }}! Je mehr Stimmen, desto größer ist die Chance! Wir brauchen deine Unterstützung!"),
									redirect_uri: "{{ Request::root() }}/items/{{ $item->id }}",
									data: {{ $item->id }}
								});
							});  
							$("a#post").click(function() {
							
								FB.ui({
									method: "feed",
									name: ("Gewinnen wir ein Blob-Event!"),
									link: "{{ Request::root() }}/items/{{ $item->id }}",
									picture: "{{ Request::root() }}/photos/{{ $item->image }}",
									description: ("Vote für {{ $item->name }}! Je mehr Stimmen, desto größer ist die Chance! Wir brauchen deine Unterstützung!"),
									redirect_uri: "{{ Request::root() }}/items/{{ $item->id }}"
				
								});
							});
							$("a#send").click(function() {            
								 FB.ui({
								  method: "send",
								  link: "{{ Request::root() }}/items/{{ $item->id }}",
								  picture: "{{ Request::root() }}/photos/{{ $item->image }}",
								  name: ("Gewinnen wir ein Blob-Event!"),
								  description: ("Vote für {{ $item->name }}! Je mehr Stimmen, desto größer ist die Chance! Wir brauchen deine Unterstützung!")                    
								});
							});           
						  
						  });
					  
					</script>
			  
			 <?php
			 }
		  }
		  
		  } else {
	  
	// LIST ALL ITEMS	  
	  
	  $items = DB::select(DB::raw(' select i.*, votescount from items i left join (select votes.item_id, count(1) as votescount from votes group by votes.item_id) v on i.id = v.item_id where active = 1 order by coalesce(v.votescount, 0) DESC '));
	  
	foreach ($items as $item) {
		
		$votescount = $item->votescount;
		if ($votescount=="") $votescount=0;
	?> 
    <div class="item">
        <p>
		<a class="imgholder" target="_parent" href="{{ $fblink }}&app_data=detail{{ $item->id }}" style="background-image:url({{ Request::root()}}/photos/{{ $item->image }});background-repeat:no-repeat;background-position: center center;background-size: 100%;">
		<span class="h1">{{ $item->name }}</span>
                    <span class="voting">
                     {{ $votescount }}<br />
                     <span class="votes">Votes</span>
                    </span>
                <span class="votebutton recommend">Ich will den Blob hier haben!</span>
                </a>
                </p>
                
                <?php
                //$sender = json_decode(file_get_contents('http://graph.facebook.com/'.$item->author))->name;
				$sender = User::where('fbid','=',$item->author)->take(1)->get();
				echo '<p  class="subline">Eingeschickt von <a href="http://facebook.com/'.$item->author.'" target="_blank">'.$sender[0]->username.'</a></p>';
				?>
        
		</div>
	 <?php
		}
}
	  
      ?>
      
    
    <?php
  } else { 
 	echo '<h3>&nbsp;</h3>';
  	echo '<p>Um mitspielen zu können, musst du zuerst diese Seite liken.</p><div class="fb-like-box" data-href="' . Config::get('app.fbpagelink') . '" data-width="250" data-show-faces="false" data-stream="false" data-header="true"></div>';
  }
  
  
  } else { ?>

 <img src="{{ Request::root() }}/img/fb_header.png?123" alt="BLOB Austria" style="float:left;width:100%;margin: 10px 0;" />
 <div style="position:relative;float:left;text-align:center;width:100%;margin: 20px auto 50px auto;"><div>
  <a href="{{ $loginUrl }}" target="_top" id="" class="votebutton">Jetzt anmelden und mitspielen</a></div></div>
    <?php } ?>
<p style="margin:  20px;color:#666;font-size: 11px;float:left;">   
Diese Promotion steht in keiner Verbindung zu Facebook und wird in keiner Weise von Facebook gesponsert, unterstützt oder organisiert. Der Empfänger der von Ihnen bereitgestellten Informationen ist nicht Facebook, sondern <a href="http://www.blob-europe.com/" target="_top">Blob-Austria</a>.</p>
  </body>
</html>