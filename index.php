<!DOCTYPE html>
<html>
<head>
<title>Nimbus</title>
<meta name="viewport" content="width=320, initial-scale=1">
 <link rel="stylesheet" href="css/default.css">
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<!--	<script src="//connect.soundcloud.com/sdk.js"></script>-->
	<script src="https://connect.soundcloud.com/sdk.js"></script>
	<script src="https://w.soundcloud.com/player/api.js"></script>
	
<!--  Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
   <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
   
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!--google analytics-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-17000004-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body class="blue lighten-5">
   
<nav class="blue lighten-2">
    <div class="nav-wrapper">

      <a href="#!" class="brand-logo streamLoad tooltipped" data-position="bottom" data-delay="1000" data-tooltip="previous list "><i class="large material-icons left">cloud_queue</i>Nimbus</a>
      <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
      <ul class="right hide-on-med-and-down">
       <li><a href="#" class="login"><i class="tiny material-icons right">perm_identity</i>Login</a></li>
       <li class="disconnect"><a href="#" >Logout</a></li>
       <li>       	
       	
         <img class="logged_in_avatar circle" src="" alt="Logged In User">
       
        
       </li>
      </ul>
      <ul class="side-nav" id="mobile-demo">
       <li>       	
       	<div  id="mobile_user" class="chip">
         <img class="logged_in_avatar" src="" alt="Logged In User">
         <span  class="logged_in_username">loading</span>
        </div>
       </li>
       <li><a href="#" class="login"><i class=" material-icons right">perm_identity</i>Login</a></li>
       <li><a href="#" class="disconnect"><i class="material-icons right">perm_identity</i>Logout</a></li>
      </ul>
    </div>
  </nav>




<div id="control_bar" class="center blue-grey darken-2">
    
		<ul class="center">
			<li>
				<ul>
				<li class="tooltipped" data-position="bottom" data-delay="1000" data-tooltip="previous list">
	        		<div href="#!" class="waves-effect waves-circle waves-light btn-floating history_back">
	    				<i class="material-icons">arrow_back</i>
	  				</div>
	  			</li>
				<li class="tooltipped" data-position="bottom" data-delay="1000" data-tooltip="current user">
					<div  class="chip">
				    	<img src="https://i1.sndcdn.com/avatars-000007680258-e87zg0-large.jpg" id="current_user_avatar" alt="Contact Person">
						<span id="current_user_username">loading</span>
					</div>
				</li>
				</ul>
			</li>
			<li>
				<ul id="current_user_list_type">
					<li class="tooltipped" data-position="bottom" data-delay="1000" data-tooltip="likes">
						<div id="likes" class="waves-effect waves-circle waves-light btn-floating control_btn userLikeLoad"><i class="material-icons">grade</i>
						</div>
					</li>
					<li class="tooltipped" data-position="bottom" data-delay="1000" data-tooltip="tracks">
						<div id="tracks" class="waves-effect waves-circle waves-light btn-floating control_btn  userTrackLoad"><i class="material-icons">album</i>
					</li>
					<li class="tooltipped" data-position="bottom" data-delay="1000" data-tooltip="stream">
						<div id="stream" class="waves-effect waves-circle waves-light btn-floating control_btn  streamLoad"><i class="material-icons">view_list</i></div>
					</li>

					<li class="tooltipped" data-position="bottom" data-delay="1000" data-tooltip="play/pause" id="control_tt">
						<div id="" class="waves-effect waves-circle waves-light btn-floating small_control"><i class="material-icons">play_arrow</i>
						</div>
					</li>
				</ul>

			<li >
				<ul>
					<li class="tooltipped" data-position="bottom" data-delay="1000" data-tooltip="track length filter" id="length_tt">
						
						<div class="range-field">     
							<input  type="range" name="range" min="0" max="180" value="0" oninput="range.value=value;filter();">
						</div>
						<i class="tiny material-icons">av_timer</i>
						<output id="range" class="">0</output>
					</li>
					<li class="tooltipped" data-position="bottom" data-delay="1000" data-tooltip="volume" id="volume_tt">
						
						<div class="range-field">     
							<input  type="range" id="volume" name="volume" min="0" max="100" value="100" oninput="volume_output.value=value;adjustVolume();">
						</div>
						<i class="volume_icon tiny material-icons">volume_up</i>
						<output id="volume_output" class="">100</output>
					</li>
				</ul>				
			</li>
      	</ul>

  </div>



<div class="container">

    <div id="frontpage">
    <div class="row">
      <div class="col s4 ">
      	<div class="promo center">
        	<i class="material-icons blue-text">cloud_queue</i>
        	<h5>Powered by Soundcloud</h5>
        	<p>The site uses the powerful <a target="_BLANK" href="https://developers.soundcloud.com/docs/api/guide">Soundcloud API</a> and embedded player to manage and deliver your music.</p>        	
        </div>
      </div>
      <div class="col s4">
      	<div class="promo center">
        	<i class="material-icons blue-text">group</i>
        	<h5>User Browsing</h5>
        	<p>Browse through friends and related users to quickly find and play new content.</p>
        </div>
      </div>
      <div class="col s4">
      	<div class="promo center">
        	<i class="material-icons blue-text">av_timer</i>
        	<h5>Track Length Filtering</h5>
        	<p>Use the track length filter to customise your music feeds to find sets and mixes easily.</p>         	
        </div>
      </div>
     </div>
     <div class="row">
     	<div class="col s4 offset-s4 center">
     	 <div class="btn waves-effect waves-light login">
     	 	Login To Begin
     	</div>
     </div>
    </div>
    </div>
		<div id="playlist_container" class="row"></div>


	<div id="more_container" class="row"></div>

</div>

<div id="footer_player"><iframe id="playa" width="100%" height="300" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F1848538&show_artwork=true"></iframe></div>
<script src="js/nimbus.js"></script>
</body>
