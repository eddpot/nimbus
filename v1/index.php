<!DOCTYPE html>
<html>
<head>

 <link rel="stylesheet" href="css/default.css">
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="mousewheel/jquery.mousewheel.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="//connect.soundcloud.com/sdk.js"></script>
	<script src="https://w.soundcloud.com/player/api.js"></script>
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
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
      
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav navbar-right">
        <li><a href="#" id="login">Login</a></li>
        <li><a href="#" id="disconnect" style="display:none">Logout</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container">
	
		<div id="controls" class="row">
			 
			<div id="route" class="col-md-6">
				<ul id="current_user">
					<span class="glyphicon glyphicon-arrow-left history_back"></span>
					<li><img id="current_user_avatar" src=""></li>
					<li id="current_user_username"></li>
					<li>
						<ul id="current_user_list_type">
							<li id="stream"><span class="glyphicon glyphicon-th-list streamLoad" data-userid="0"></span></li>
							<li id="tracks"><span class="glyphicon glyphicon-music  userTrackLoad" data-userid="0"></span></li>
							<li id="likes"><span class="glyphicon glyphicon-heart userLikeLoad" data-userid="0"></span></li>
						</ul>
					</li>
				</ul>	
			</div>

			<div class="col-md-6">
				<div class="range ">
			
				   	<label id="range_label">Hiding all tracks shorter than</label>        
				   	<input  type="range" name="range" min="0" max="180" value="0" oninput="range.value=value;filter();">
			       <output id="range">0</output>
			      </div>
			</div>
		</div>	
	
	<div id="playlist_container"></div>
	<div id="more_container"></div>
	<div id="clipboard_alert">URL Copied to clipboard <span class="glyphicon glyphicon-check"></span></div>

</div>

<div id="footer_player"></div>
<script>


var routeType;
var trackHolder=new Array();
var embedCounter=0;
var properties={limit:50};
var append="no";
var currentUser={};
var loggedUser={};
var user_history=[];
        
		SC.initialize({
			client_id: "2873ab235ed1225f83ac81a5fccceb0e",
			redirect_uri: "http://109.158.83.15/soundcloud/callback.html",
             access_token: getCookie("SCaccess"),
             scope: 'non-expiring'
        }); 
		//console.dir(SC.isConnected());
		if(getCookie('SCaccess')!=null&&getCookie('SCaccess')!="null"){
					loggedIn();

            append="no";
            setLoggedUser();
			loadTracks(getCookie("SCroute")||"/me/activities");
			
		}
		else{
			SC.connect(function(){			
				
				setCookie('SCaccess',SC.accessToken(),99);
                properties={limit:50};
                setLoggedUser();
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
        setLoggedUser();
        loggedIn();        
		loadTracks('/me/activities');
	});
});

$(document).on('click','.streamLoad', function(){
    properties={limit:50};
    append="no";
      $('#playlist_container').empty();
	loadTracks('/me/activities');
});

$(document).on('click','.userTrackLoad', function(e){
	e.stopPropagation();
    properties={limit:50};
	loadTracks('/users/'+this.dataset.userid+"/tracks");
});

$(document).on('click','.userLikeLoad', function(e){
	   e.stopPropagation();
            properties={limit:100};
	loadTracks('/users/'+this.dataset.userid+"/favorites");
});

$(document).on('click','.trackShare', function(e){
	
	e.stopPropagation();
	
	mouseX = event.clientX + document.body.scrollLeft;
 	mouseY = event.clientY + document.body.scrollTop-25;

	ta=$('<textarea id="clipboard"></textarea>').val($(this).data("url"));
	$('body').append(ta);
	   
	copy_text=ta[0].select();
	document.execCommand('copy');
	$('#clipboard').remove();
	$('#clipboard_alert').css({'top':mouseY,'left':mouseX}).fadeIn("slow").delay(800).fadeOut("slow");
          
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
	deleteCookies();
	$('a#login').fadeIn();
    $('#disconnect').fadeOut();
	$('#playlist_container').empty();
	$('#more_container').empty();
	$('#controls').fadeOut();
	$('.navbar-left').remove();
	
});
 
$(document).on('click','.history_back', function(){
	//console.dir(user_history[user_history.length-2])
	if (user_history.length>1){
		
		loadTracks(user_history[user_history.length-2].full_route);
		user_history.pop();
		user_history.pop();

	}
});
    
$(document).on('click','#more', function(){

    properties={cursor:$(this).data("cursor"), limit:$(this).data("limit")};
    loadTracks("/me/activities");


});


    
$(document).on('click','.player',function(){

	track_url=$(this).data("url");
	$('.player').removeClass('active');
	$(this).addClass('active');
	loadTrack(track_url,this);	

	
	

});



$(document).on('click','.small_control',function(e){
	//todo to do here
	e.preventDefault();
	e.stopPropagation()
	that=this
	var iframeElement=document.getElementById('playa');
	var widget=SC.Widget(iframeElement);
	widget.toggle();
	widget.bind(SC.Widget.Events.PLAY, function() {
		$('.small_control').empty()
			$(that).append('<span class="glyphicon glyphicon-pause" aria-hidden="true" style="font-size: 30px; width: 100%; padding-top: 7px;"></span>')
	})
	widget.bind(SC.Widget.Events.PAUSE, function() {
		$('.small_control').empty()
					$(that).append('<span class="glyphicon glyphicon-play" aria-hidden="true"></span>')
	})
	
	widget.isPaused(function(ispaused){
		if (ispaused===true){
			
			$('.small_control').empty()
			$(that).append('<span class="glyphicon glyphicon-play" aria-hidden="true"></span>')

		}
		else{
			$('.small_control').empty()
			$(that).append('<span class="glyphicon glyphicon-pause" aria-hidden="true" style="font-size: 30px; width: 100%; padding-top: 7px;"></span>')
		}
	})

		
		

});

function filter(){
	
    duration=document.getElementById('range').value;      

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
    $('#disconnect').fadeIn();
    $('#controls').fadeIn();
}

function loadTracks(route){

	setCookie('SCroute',route,99);

	SC.get(route,properties, function(data) { 
	
       
		if (route.substring(0,7)=='/users/') {
   			setCurrentUser('/users/'+route.split("/")[2],route.split("/")[3]);
			routeType="user";
			collection=data;
            properties={limit:200};
            append="no";

		}
		else if(route.substring(0,4)=='/me/') {
            setCurrentUser('me',route.split("/")[2]);
            properties={limit:50};
			routeType="me";
			collection=data.collection;
            append="yes";            
		}
		else{
			console.log("something broke wit the route");
		}
            	if(append=="no"){
           
            $('#playlist_container').empty();
        }

		$.each(collection, function(index, value) {
		
			if (routeType=="me" && value.origin!=null){
				var id=value.origin.id;
				var duration=value.origin.duration;
				var embeddable_by=value.origin.embeddable_by;
				var kind = value.origin.kind;
								
              
			}
			else if(routeType=="user" && value.id!=null){
				var id=value.id;
				var duration=value.duration;
				var embeddable_by=value.embeddable_by;
				var kind = value.kind;
			
               
			}
				
			if(embeddable_by=="all" && kind=="track"){
			//  console.dir(value.origin);
				SC.get('/tracks/'+id,function(track){
					
					created_at=track.created_at;
					created_at=created_at.split(" ");
					created_at=created_at[0];
					var today = new Date();
					var dd = today.getDate();
					var mm = today.getMonth()+1; //January is 0!
					var yyyy = today.getFullYear();
					if(dd<10) {
					    dd='0'+dd
					} 

					if(mm<10) {
					    mm='0'+mm
					} 
					age=daydiff(parseDate(created_at),parseDate(yyyy+"/"+mm+"/"+dd));
					age = age+" days"
					if (track.artwork_url===null){track.artwork_url="https://placeimg.com/50/50/animals/grayscale"}
 					container = $("<div>").attr({'id':'player' + track.id,'class':'player','data-duration':track.duration, 'data-track-id':track.id,'data-url':track.permalink_url});
 					thumb_holder=$("<div></div>").attr({'class':'thumbholder'});
 					thumb=$("<img></img>").attr({'class':'thumb','src':track.artwork_url});
 					small_control=$("<div></div>").attr({'class':'small_control'});
 					thumb_holder.append(thumb).append(small_control);
 					uploader=$("<div>"+track.user.username+"</div>").attr({'class':'uploader'});
 					track_name=$("<div>"+track.title+"</div>").attr({'class':'track_name'});
 					track_length=$("<div>"+secondsToString(track.duration/1000)+"</div>").attr({'class':'track_length'});
 					track_age=$("<div>"+age+"</div>").attr({'class':'track_age'});
 					artist_likes=$("<span></span>").attr({'class':'glyphicon glyphicon-heart artist_likes userLikeLoad','data-userId':track.user_id,'title':track.user.username+' likes'});
 					artist_tracks=$("<span></span>").attr({'class':'glyphicon glyphicon-music artist_tracks userTrackLoad','data-userId':track.user_id,'title':track.user.username+' tracks'});
 					share_track=$("<span></span>").attr({'class':'glyphicon glyphicon-share share_tracks trackShare','data-url':track.permalink_url,'title':'Copy track URL'});
 				
 					$(container).append(thumb_holder).append(uploader).append(track_name).append(track_length).append(track_age).append(artist_likes).append(artist_tracks).append(share_track);
              		$('#playlist_container').append(container);
  					filter();

				});


			}
		
		});
        
        //console.log(routeType);
        $('#more').remove();
        
        if(routeType=="me"){

            temp_prop={};
            if (typeof data.next_href!="undefined"){
                var vars = {};
                var parts = data.next_href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    temp_prop[key] = value;
                });
                //console.dir(temp_prop);
            }
            $('#more_container').append("<button href='#' data-cursor='"+temp_prop['cursor']+"' data-limit='"+temp_prop['limit']+"' id='more' class='btn'>Load more tracks</button>");

        }
        
	}); 


}




function loadTrack(track_url,source){
//	$('#footer_player').empty();
//SC.oEmbed(track_url,document.getElementById('footer_player'));
scontrol=source.childNodes[0].childNodes[1];
	$('.small_control').empty()
	$(scontrol).append('<span class="glyphicon glyphicon-play" aria-hidden="true"></span>') 
	SC.oEmbed(track_url,{'maxheight':160},function(res){
	
		$('#footer_player').empty();
		htmlToAppend=$(res.html);
		$(htmlToAppend).attr("id","playa");
		$('#footer_player').append(htmlToAppend)

	});

}

function setLoggedUser(){
	SC.get('/me', function(data) {
		
		loggedUser.username=data.username;
		loggedUser.img=data.avatar_url;
		loggedUser.id=data.id;
		

		ul=$('<ul></ul>').addClass('nav navbar-nav navbar-left');
		li1=$('<li></li>')
		li2=$('<li></li>')
		img=$('<img>').attr("src",loggedUser.img).addClass('thumb streamLoad').attr("id","logged_in_avatar");
		name1=$('<div>'+loggedUser.username+'</div>').attr("id","logged_in_username").addClass(' streamLoad')

		ul.append(li1).append(li2)
		li1.append(img)
		li2.append(name1)
		$('.navbar-collapse').append(ul);
        
     });	

}
     		
function setCurrentUser(user_type,list_type){

	

	if (user_type==="me"){
		route="/me";
		$('#stream .streamLoad').fadeIn('slow');
	}
	else{
		route=user_type

		
	}
	currentUser.list_type=list_type

	SC.get(route, function(data) {
		
		$('#tracks .userTrackLoad').attr({"data-UserId":data.id, 'title':data.username+ ' tracks'});
		$('#likes .userLikeLoad').attr({"data-UserId":data.id, 'title':data.username+ ' likes'});
		$('#stream .streamLoad').attr({"data-UserId":data.id, 'title':data.username+ ' stream'});

		currentUser.username=data.username;
		currentUser.img=data.avatar_url;
        $('#current_user_avatar').attr("src",currentUser.img);
        $('#current_user_username').text(currentUser.username);

		if (currentUser.username!=loggedUser.username){
			$('#stream .streamLoad').fadeOut('slow');	
		}
        //console.log(route+": : "+list_type)
        user_history.push({'user_type':route,'full_route':route+"/"+list_type})
        
     });
	

	
	$('#current_user_list_type li span').css("color","#000")

	if (list_type==="activities"){

		$('#stream span').css("color","#ff6600")
	}
	else if(list_type==="tracks"){
		$('#tracks span').css("color","#ff6600")
	}
	else if(list_type==="favorites"){
		$('#likes span').css("color","#ff6600")
	}	

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

    function deleteCookies(){
		document.cookie = 'SCaccess=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
		document.cookie = 'SCroute=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
 function secondsToString(seconds)
{

var numhours = Math.floor(((seconds % 31536000) % 86400) / 3600);
var numminutes = Math.floor((((seconds % 31536000) % 86400) % 3600) / 60);
var numseconds = Math.floor((((seconds % 31536000) % 86400) % 3600) % 60);

if (numminutes<10){
	numminutes="0"+numminutes
}
if (numhours<10){
	numhours="0"+numhours
}
if (numseconds<10){
	numseconds="0"+numseconds
}

	return  numhours + ":" + numminutes + ":" + numseconds




}

function parseDate(str) {
    var ymd = str.split('/')

    return new Date(ymd[0],ymd[1]-1,ymd[2]);
}

function daydiff(first, second) {


    return Math.round((second-first)/(1000*60*60*24));
}


</script>
</body>