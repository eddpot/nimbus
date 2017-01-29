<!DOCTYPE html>
<html>
<head>
<style>

#player_container{
	width:90%;
	margin:0 auto;

}
	.player{
	 width:100%;
	 border-bottom:1px solid #cccccc;
	
	}
	
	.infoPanel{
		padding:20px;
		float:left;
	}
.thumb, .uploader, .track_name, .track_length, .artist_likes, .artist_tracks{
	display:inline-block;
	padding:5px;
overflow:hidden;
vertical-align: middle;
min-width:10px;
}
.track_name{
 font-weight:bold;
}
.thumb{
	width:50px;
}
.uploader{
	width:20%;
}
.track_name{
	width:60%;
}
.track_length{
	width:10%;
}
.artist_likes{
	cursor:pointer;
}
.artist_tracks{
	cursor:pointer;

}

#spinner{
 width:75px;   
}
.player:nth-child(odd){
/*	background-color:#ededed;*/
}

.active:nth-child(n){
	border:2px solid #FF6600;
	background-color:#FF6600;
	color:#fff;
}


body{
	white-space: nowrap;

}
.show_track{
	margin-bottom:-5px;
}

/*range slider css*/
.range {
    display: table;
    position: relative;
    height: 25px;
    margin-top: 20px;
    background-color: rgb(245, 245, 245);
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    width:80%;
    margin:0 auto;
}

.range input[type="range"] {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    -o-appearance: none !important;
    appearance: none !important;

    display: table-cell;
    width: 100%;
    background-color: transparent;
    height: 25px;
    cursor: pointer;
}
.range input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    -o-appearance: none !important;
    appearance: none !important;

    width: 11px;
    height: 25px;
    color: rgb(255, 255, 255);
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0px;
    background-color: rgb(66, 139, 202);
}

.range input[type="range"]::-moz-slider-thumb {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    -o-appearance: none !important;
    appearance: none !important;
    
    width: 11px;
    height: 25px;
    color: rgb(255, 255, 255);
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0px;
    background-color: rgb(66, 139, 202);
}

.range output {
    display: table-cell;
    padding: 3px 5px 2px;
    min-width: 40px;
    color: rgb(255, 255, 255);
     background-color: rgb(66, 139, 202);
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
    width: 1%;
    white-space: nowrap;
    vertical-align: middle;

    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    -ms-transition: all 0.5s ease;
    transition: all 0.5s ease;

    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: -moz-none;
    -o-user-select: none;
    user-select: none;
}
.range input[type="range"] {
    outline: none;
}

.rangeinput[type="range"]::-webkit-slider-thumb {
    background-color: rgb(66, 139, 202);
}
.range input[type="range"]::-moz-slider-thumb {
    background-color: rgb(66, 139, 202);
}
.range output {
    background-color: rgb(66, 139, 202);
}
.range input[type="range"] {
    outline-color: rgb(66, 139, 202);
}

/*range finder css end */
#footer_player{
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 60px;
    background-color: #f5f5f5;
}

</style>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="mousewheel/jquery.mousewheel.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="//connect.soundcloud.com/sdk.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

</head>
<body>

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" id="home" href="#">Soundcloud Explorer</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
 
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#" id="login">Login</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<button class="btn toggle" id="get">Get your feed</button>



<div class="container">

<div id="controls">
	<div class="range">
    	<input type="range" name="range" min="0" max="180" value="0" oninput="range.value=value;filter();">
        <output id="range">0</output>
	</div>
</div>
	<div id="player_container">
	</div>
</div>

<div id="footer_player"></div>
<script>


var routeType;
var trackHolder=new Array();
var embedCounter=0;
var properties={limit:50};
var append="no";
        
		SC.initialize({
			client_id: "774049902682399050a4df542a52616e",
			redirect_uri: "http://109.158.109.157/soundcloud/callback.html",
             access_token: getCookie("SCaccess"),
             scope: 'non-expiring'
        }); 
		//console.dir(SC.isConnected());
		if(getCookie('SCaccess')!=null&&getCookie('SCaccess')!="null"){
					loggedIn();

            append="no";
			loadTracks(getCookie("SCroute")||"/me/activities");
			
		}
		else{
			SC.connect(function(){			
			//	console.log("!");
				setCookie('SCaccess',SC.accessToken(),99);
                properties={limit:50};
                
				loadTracks('/me/activities');
				loggedIn();
			});
		};
		
 // console.log(SC.initialize());
	


$('#login').on('click', function(){
	SC.connect(function(){
		setCookie('SCaccess',SC.accessToken(),99);
        properties={limit:50};
        append="no";
		loadTracks('/me/activities');
	});
});

$('#get, #home').on('click', function(){
    properties={limit:50};
    append="no";
      $('#player_container').empty();
	loadTracks('/me/activities');
});

$(document).on('click','.userTrackLoad', function(){
            properties={limit:50};
	loadTracks('/users/'+this.dataset.userid+"/tracks");
});

$(document).on('click','.userLikeLoad', function(){
            properties={limit:50};
	loadTracks('/users/'+this.dataset.userid+"/favorites");
});

$(document).on('click','#setcookie', function(){

	setCookie('SCaccess',SC.accessToken(),99);
});

$(document).on('click','#getcookie', function(){
//	console.log(getCookie('SCaccess'));
	
	
});

$(document).on('click','#reconnect', function(){

});

$(document).on('click','#disconnect', function(){
		SC.disconnect();
});
    
$(document).on('click','#more', function(){

    properties={cursor:$(this).data("cursor"), limit:$(this).data("limit")};
    loadTracks("/me/activities");


});


    
$(document).on('click','.player',function(){
	track_id=this.attributes.trackid.value;
	if($('#player'+track_id).hasClass("active")){
		$('#player'+track_id).removeClass('active');
	}
	else{
		$('#player'+track_id).addClass('active');
	}

	if (!$('#'+track_id).hasClass("loaded")){
		loadTrack(track_id);	
	}

	$('#'+track_id).slideToggle('fast');


	
	

});

function filter(){
	
    duration=$('.range')[0].innerText;      
console.log(duration);
            $.each($('.player'), function(index, value){
             
                track_length=$(value).data("duration");
            
                if(track_length<(duration*60000)){
                  
                    $(value).fadeOut();
                }
                else{
                    $(value).fadeIn();
                }
	       });   
    
}
    
    
$(document).ready(function(){

});


function loggedIn(){
    $('a#login').fadeOut();
}

function loadTracks(route){

	setCookie('SCroute',route,99);

	SC.get(route,properties, function(data) { 

        
		if (route.substring(0,7)=='/users/') {
    
			routeType="user";
			collection=data;
            properties={limit:50};
            append="no";
		}
		else if(route.substring(0,4)=='/me/') {
            properties={limit:50};
			routeType="me";
			collection=data.collection;
            append="yes";            
		}
		else{
			console.log("something broke wit the route");
		}
            	if(append=="no"){
           
            $('#player_container').empty();
        }

		$.each(collection, function(index, value) {
		
			if (routeType=="me"){
				var id=value.origin.id;
				var duration=value.origin.duration;
				var embeddable_by=value.origin.embeddable_by;
				var kind = value.origin.kind;
              
			}
			else if(routeType=="user"){
				var id=value.id;
				var duration=value.duration;
				var embeddable_by=value.embeddable_by;
				var kind = value.kind;
               
			}
				
			if(embeddable_by=="all" && kind=="track"){
			//  console.dir(value.origin);
				SC.get('/tracks/'+id,function(track){
	
 					container = $("<div>").attr({'id':'player' + track.id,'class':'player','data-duration':track.duration, 'trackId':track.id});
 					thumb=$("<img></img>").attr({'class':'thumb','src':track.artwork_url});

 					uploader=$("<div>"+track.user.username+"</div>").attr({'class':'uploader'});
 					track_name=$("<div>"+track.title+"</div>").attr({'class':'track_name'});
 					track_length=$("<div>"+secondsToString(track.duration/1000)+"</div>").attr({'class':'track_length'});
 					
 					artist_likes=$("<span></span>").attr({'class':'glyphicon glyphicon-heart artist_likes userLikeLoad','data-userId':track.user_id});
 					artist_tracks=$("<span></span>").attr({'class':'glyphicon glyphicon-music artist_tracks userTrackLoad','data-userId':track.user_id});
 					show_track=$("<div>edd</div>").attr({'class':'show_track', 'id':track.id, 'style':'display:none;','data-url':track.permalink_url});
 					$(container).append(thumb).append(uploader).append(track_name).append(track_length).append(artist_likes).append(artist_tracks).append(show_track);
              		$('#player_container').append(container);
  					filter();

				});

	/* snip
                SC.get('/tracks/'+id,function(track){
					
                    var container = $("<div>").attr({'id':'player' + track.id,'class':'player','data-duration':track.duration, 'trackId':track.id});
                    var infoPanel = $("<div>").attr({'id':'infoPanel' + track.id,'class':'infoPanel'});
                    var row=$("<div>").attr({'class':'row','id':'row'+track.id});

                    $('#player_container').append(row);
                    $(row).append(infoPanel);
                    $(row).append(container);

                    var panelImage = track.artwork_url;
                    $(infoPanel).append("<a href='#' class='userTrackLoad' data-userId='"+track.user_id+"'>tracks </a>");
                    $(infoPanel).append("<a href='#' class='userLikeLoad' data-userId='"+track.user_id+"'> likes</a>");
                    $(infoPanel).append("<img src="+panelImage+">");
                    trackHolder.push(track);
							//$('#player'+id).append('<iframe width="100%" height="166" scrolling="no" frameborder="no" src="http://player.soundcloud.com/player.swf?url=http://api.soundcloud.com/tracks/'+track.id+'"></iframe>');
						    //$('#player'+id).append('<object height="81" width="100%"><param name="movie" value="http://player.soundcloud.com/player.swf?url=http://api.soundcloud.com/tracks/152355661"></param> <param name="allowscriptaccess" value="always"></param> <embed src="http://player.soundcloud.com/player.swf?url=http://api.soundcloud.com/tracks/152355661" allowscriptaccess="always" height="81"  type="application/x-shockwave-flash" width="100%"></embed></object>');
					SC.oEmbed(track.permalink_url, {maxheight:100},document.getElementById("player"+id));

                            filter();


                    //console.dir(track);
                      /*
                            if(index==(collection.length-1)){
                                console.log("end");
                                embedTracks(embedTracks);
                            }
						*/
/*more snip					
				});
snip end*/
			}
		
		});
        
        //console.log(routeType);
        if(routeType=="me"){

            temp_prop={};
            if (typeof data.next_href!="undefined"){
                var vars = {};
                var parts = data.next_href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    temp_prop[key] = value;
                });
                //console.dir(temp_prop);
            }
            $('#more').remove();
            
            $('body').append("<button href='#' data-cursor='"+temp_prop['cursor']+"' data-limit='"+temp_prop['limit']+"' id='more'>more</button>");
        }
	}); 


}



/*
var embedTracks = function(callback){
    if (embedCounter!=(trackHolder.length-1)){
        
        
     track=trackHolder[embedCounter];
        
        
        embedCounter++;
        console.log(track);
        
        var container = $("<div>").attr({'id':'player' + track.id,'class':'player','data-duration':track.duration, 'trackId':track.id});
		var infoPanel = $("<div>").attr({'id':'infoPanel' + track.id,'class':'infoPanel'});
		var row=$("<div>").attr({'class':'row','id':'row'+track.id});
		
		$('#player_container').append(row);
		$(row).append(infoPanel);
        $(row).append(container);
        	var panelImage = track.artwork_url;
    //	console.dir(track.user);
	//console.log(routeType);
	
	$(infoPanel).append("<a href='#' class='userTrackLoad' data-userId='"+track.user_id+"'>tracks </a>");
	$(infoPanel).append("<a href='#' class='userLikeLoad' data-userId='"+track.user_id+"'> likes</a>");
	$(infoPanel).append("<img src="+panelImage+">");

        SC.oEmbed(track.permalink_url, {maxheight:100},document.getElementById("player"+track.id));

        callback(embedTracks);
        
    }

    
 
    
}
*/

function loadTrack(track_id){
	

	target=$("#"+track_id)[0];

	song_url=($(target).attr("data-url"));
	$(target).addClass("loaded");

	SC.oEmbed(song_url, {maxheight:140},document.getElementById(track_id));


}
    
function setCookie(c_name,value,exdays)
    {
      var exdate=new Date();
      exdate.setDate(exdate.getDate() + exdays);
      var c_value=escape(value) + 
        ((exdays==null) ? "" : ("; expires="+exdate.toUTCString()));
      document.cookie=c_name + "=" + c_value;
    }

    function getCookie(c_name)
    {
     var i,x,y,ARRcookies=document.cookie.split(";");
     for (i=0;i<ARRcookies.length;i++)
     {
      x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
      y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
      x=x.replace(/^\s+|\s+$/g,"");
      if (x==c_name)
      {
       return unescape(y);
      }
     }
    }
 function secondsToString(seconds)
{

var numhours = Math.floor(((seconds % 31536000) % 86400) / 3600);
var numminutes = Math.floor((((seconds % 31536000) % 86400) % 3600) / 60);
var numseconds = (((seconds % 31536000) % 86400) % 3600) % 60;

if (numminutes<10){
	numminutes="0"+numminutes
}
if (numhours<10){
	numhours="0"+numhours
}
if (numseconds<10){
	numseconds="0"+numseconds
}

	return  numhours + ":" + numminutes + ":" + Math.round(numseconds)



}
</script>
</body>