var routeType;
var trackHolder = new Array();
var embedCounter = 0;
var properties = {
	limit: 50
};
var append = "no";
var currentUser = {};
var loggedUser = {};
var user_history = [];
var widgetIframe = document.getElementById('playa');
var widget       = SC.Widget(widgetIframe);




	


$.getJSON('init.json',function(data){
	SC.initialize({
		client_id: data.client_id,
		redirect_uri: data.redirect_uri,
		access_token: getCookie("SCaccess"),
		scope: 'non-expiring'
	});
	if (getCookie('SCaccess') != null && getCookie('SCaccess') != "null") {
		loggedIn();
		append = "no";
		setLoggedUser();
		loadTracks(getCookie("SCroute") || "/me/activities");
	} 
	else {
		SC.connect(function () {
			setCookie('SCaccess', SC.accessToken(), 99);
			properties = {
				limit: 50
			};
			setLoggedUser();
			loadTracks('/me/activities');
			loggedIn();
		});
	};
})




 $(document).ready(function(){
 	var controls_offset = 64;
 	$(document).on('scroll', function() {

 		console.log($(document).scrollTop())

    	$('#control_bar').toggleClass('fixit', $(document).scrollTop() > controls_offset);
  	});

 	widget.bind(SC.Widget.Events.PLAY, function () {
    	console.log("hi")
			$('.small_control').empty()
			$('.small_control').append('<i class="material-icons">pause</i>')
	})
	widget.bind(SC.Widget.Events.PAUSE, function () {
		console.log("bye!")
		$('.small_control').empty()
		$('.small_control').append('<i class="material-icons">play_arrow</i>')
	})
	console.dir(SC.isConnected());

 }) 
    
	



// console.log(SC.initialize());



$('.login').on('click', function () {
	SC.connect(function () {
		setCookie('SCaccess', SC.accessToken(), 99);
		properties = {
			limit: 50
		};
		append = "no";
		setLoggedUser();
		loggedIn();
		loadTracks('/me/activities');
	});
});

$(document).on('mouseover', '.artist_container', function () {

	bg = $(this).parent().css("background-color")
	$('.artist_container ul').css("background-color", bg)
		//add active class to ul?


});
$(document).on('click', '.artist_container', function () {


	if ($(this).parent().css("background-color") == 'rgb(212, 249, 246)') {
		//$('.artist_container ul').css("background-color","red")	
		$(this).children(2).addClass("active_btn")
	}

});

$(document).on('click', '.artist_action_button, .artist_action_button + ul', function (e) {
	preventDefault(e);
	stopPropagation(e);

});


$(document).on('click', '.streamLoad', function () {
	properties = {
		limit: 50
	};
	append = "no";
	$('#playlist_container').empty();
	loadTracks('/me/activities');
});

$(document).on('click', '.userTrackLoad', function (e) {
	e.stopPropagation();
	properties = {
		limit: 50
	};
	loadTracks('/users/' + this.dataset.userid + "/tracks");
});

$(document).on('click', '.userLikeLoad', function (e) {

	e.stopPropagation();
	properties = {
		limit: 1000
	};
	console.dir(this)
	userid = $(this).data("userid");
	loadTracks('/users/' + userid + "/favorites");
	//loadTracks('/me/activities');
});

$(document).on('click', '.trackShare', function (e) {

	e.stopPropagation();

	mouseX = event.clientX + document.body.scrollLeft;
	mouseY = event.clientY + document.body.scrollTop - 25;

	ta = $('<textarea id="clipboard"></textarea>').val($(this).data("url"));
	$('body').append(ta);

	copy_text = ta[0].select();
	document.execCommand('copy');
	$('#clipboard').remove();
	$('#clipboard_alert').css({
		'top': mouseY,
		'left': mouseX
	}).fadeIn("slow").delay(800).fadeOut("slow");

});

$(document).on('click', '#setcookie', function () {

	setCookie('SCaccess', SC.accessToken(), 99);
});

$(document).on('click', '#getcookie', function () {
	//	console.log(getCookie('SCaccess'));


});

$(document).on('click', '#reconnect', function () {

});

$(document).on('click', '.disconnect', function () {
	SC.disconnect();
	deleteCookies();
	$('.disconnect').fadeOut('slow', function () {
		$('a.login').fadeIn('slow');
		$('#frontpage').fadeIn('slow')
	});

	$('#control_bar').fadeOut('slow')
	$('#mobile_user').fadeOut('slow')
	$('#playlist_container').empty();
	$('#more_container').empty();
	$('#controls').fadeOut();
	$('.logged_in_avatar').fadeOut('slow');

});

$(document).on('click', '.history_back', function () {
	//console.dir(user_history[user_history.length-2])
	if (user_history.length > 1) {

		loadTracks(user_history[user_history.length - 2].full_route);
		user_history.pop();
		user_history.pop();

	}
});

$(document).on('click', '#more', function () {

	properties = {
		cursor: $(this).data("cursor"),
		limit: $(this).data("limit")
	};
	loadTracks("/me/activities");


});



$(document).on('click', '.player', function () {

	track_url = $(this).data("trackId");
	$('.player').removeClass('active');
	$(this).addClass('active');
	loadTrack(track_url, this);




});



$(document).on('click', '.small_control', function (e) {
	//todo to do here
	console.log("wtf")

	that = this

	widget.toggle();
	console.dir(widget)
	widget.isPaused(function (ispaused) {
		if (ispaused === true) {
			console.log("paws")
			$('.small_control').empty()
			$(that).append('<i class="material-icons">play_arrow</i>')

		} else {
			console.log("not paws")
			$('.small_control').empty()
			$(that).append('<i class="material-icons">pause</i>')
		}
	})




});

function filter() {

	duration = document.getElementById('range').value;

	$.each($('.player'), function (index, value) {

		track_length = $(value).data("duration");

		if (track_length < (duration * 60000)) {

			$(value).fadeOut();
		} else {
			$(value).fadeIn();
		}
	});

}


function adjustVolume() {

	vol = document.getElementById('volume').value;

	if (vol == 0) {

		$('.volume_icon').text("volume_mute")
	} else {
		$('.volume_icon').text("volume_up")
	}

	var iframeElement = document.getElementById('playa');
	var widget = SC.Widget(iframeElement);
	widget.setVolume(vol / 100);
	//	widget.getVolume(function(res){
	//		console.dir(res)
	//	})

}




$(document).ready(function () {
	$(".button-collapse").sideNav();
});


function loggedIn() {
	$('a.login').fadeOut('slow', function () {
		$('.disconnect').fadeIn();
		$('#controls').fadeIn();
	});


}

function loadTracks(route) {

	setCookie('SCroute', route, 99);

	SC.get(route, properties, function (data) {


		if (route.substring(0, 7) == '/users/') {
			setCurrentUser('/users/' + route.split("/")[2], route.split("/")[3]);
			routeType = "user";
			collection = data;
			properties = {
				limit: 200
			};
			append = "no";

		} else if (route.substring(0, 4) == '/me/') {
			setCurrentUser('me', route.split("/")[2]);
			properties = {
				limit: 50
			};
			routeType = "me";
			collection = data.collection;
			append = "yes";
		} else {
			console.log("something broke wit the route");
		}
		if (append == "no") {

			$('#playlist_container').empty();
		}

		$.each(collection, function (index, value) {

			if (routeType == "me" && value.origin != null) {
				var id = value.origin.id;
				var duration = value.origin.duration;
				var embeddable_by = value.origin.embeddable_by;
				var kind = value.origin.kind;


			} else if (routeType == "user" && value.id != null) {
				var id = value.id;
				var duration = value.duration;
				var embeddable_by = value.embeddable_by;
				var kind = value.kind;


			}

			if (embeddable_by == "all" && kind == "track") {
				//  console.dir(value.origin);
				SC.get('/tracks/' + id, function (track) {

					created_at = track.created_at;
					created_at = created_at.split(" ");
					created_at = created_at[0];
					var today = new Date();
					var dd = today.getDate();
					var mm = today.getMonth() + 1; //January is 0!
					var yyyy = today.getFullYear();
					if (dd < 10) {
						dd = '0' + dd
					}

					if (mm < 10) {
						mm = '0' + mm
					}
					age = daydiff(parseDate(created_at), parseDate(yyyy + "/" + mm + "/" + dd));
					age = age + " days"
					if (track.artwork_url === null) {
						track.artwork_url = "https://placeimg.com/50/50/animals/grayscale"
					}
					container = $("<div></div>").attr({
						'id': 'player' + track.id,
						'class': 'player row valign-wrapper hoverable card-panel',
						'data-duration': track.duration,
						'data-track-id': track.id,
						'data-url': track.permalink_url
					});
					thumb_holder = $("<div></div>").attr({
						'class': 'thumbholder col s1 valign hide-on-small-and-down'
					});
					thumb = $("<img></img>").attr({
						'class': 'thumb valign circle',
						'src': track.artwork_url
					});

					thumb_holder.append(thumb)
					uploader = $("<div>" + track.user.username + "</div>").attr({
						'class': 'uploader col s3 m2 l2 valign truncate'
					});
					track_name = $("<div>" + track.title + "</div>").attr({
						'class': 'track_name col s7 m6 l6 valign truncate'
					});
					track_length = $("<div>" + secondsToString(track.duration / 1000) + "</div>").attr({
						'class': 'track_length col m1 l1 hide-on-small-and-down valign '
					});
					track_age = $("<div>" + age + "</div>").attr({
						'class': 'track_age col s1 hide-on-med-and-down'
					});



					artist_container_root = $("<div></div>").attr({
						'class': 'col s2 m2 l1'
					});
					artist_container = $("<div></div>").attr({
						'class': 'fixed-action-btn horizontal col s2 m2 l1 artist_container'
					});
					artist_container_root.append(artist_container)
					a = $("<a></a>").attr({
						'class': 'btn-floating btn-large blue darken-4  artist_action_button'
					});
					artist_container.append(a);
					i = $("<i>add</i>").attr({
						'class': 'material-icons'
					})
					a.append(i);
					ul = $("<ul></ul>").addClass('')
					artist_container.append(ul);
					li1 = $("<li></li>")
					ul.append(li1)
					a1 = $("<a></a>").attr({
						'class': 'btn-floating deep-orange userLikeLoad ',
						'data-userId': track.user_id
					})
					li1.append(a1)
					i1 = $("<i>grade</i>").attr({
						'class': 'material-icons'
					})
					a1.append(i1)
					li2 = $("<li></li>")
					ul.append(li2)
					a2 = $("<a></a>").attr({
						'class': 'btn-floating purple darken-2  userTrackLoad',
						'data-userId': track.user_id
					})
					li2.append(a2)
					i2 = $("<i>library_music</i>").attr({
						'class': 'material-icons'
					})
					a2.append(i2)
					li3 = $("<li></li>")
					ul.append(li3)
					a3 = $("<a></a>").attr({
						'class': 'btn-floating green darken-2',
						'data-url': track.permalink_url
					});
					li3.append(a3)
					i3 = $("<i>launch</i>").attr({
						'class': 'material-icons'
					})
					a3.append(i3)



					/*artist_likes=$("<i>stars</i>").attr({'class':'smallish material-icons collection-item artist_likes userLikeLoad','data-userId':track.user_id,'title':track.user.username+' likes'});
					artist_tracks=$("<i>view_list</i>").attr({'class':'smallish material-icons collection-item artist_tracks userTrackLoad','data-userId':track.user_id,'title':track.user.username+' tracks'});
					share_track=$("<i>input</i>").attr({'class':'smallish material-icons collection-item share_tracks trackShare','data-url':track.permalink_url,'title':'Copy track URL'});
 					
					artist_container.append(artist_tracks).append(artist_likes).append(share_track);
					*/
					$(container).append(thumb_holder).append(uploader).append(track_name).append(track_length).append(track_age).append(artist_container);
					$('#playlist_container').append(container);
					filter();

				});


			}

		});

		//console.log(routeType);
		$('#more').remove();

		if (routeType == "me") {

			temp_prop = {};
			if (typeof data.next_href != "undefined") {
				var vars = {};
				var parts = data.next_href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
					temp_prop[key] = value;
				});
				//console.dir(temp_prop);
			}
			$('#more_container').append("<button href='#' data-cursor='" + temp_prop['cursor'] + "' data-limit='" + temp_prop['limit'] + "' id='more' class='btn'>Load more tracks</button>");

		}

	});


}




function loadTrack(track_url, source) {
	//	$('#footer_player').empty();
	//SC.oEmbed(track_url,document.getElementById('footer_player'));
		$('.small_control').empty()
		$('.small_control').append('<i class="material-icons">play_arrow</i>')
	$('#playa').css("visibility","visible");
	widget.load('http://api.soundcloud.com/tracks/'+track_url,{'show_artwork':true,'show_comments':true});
		
		/*
		SC.oEmbed(track_url, {
		'maxheight': 160,
		'color': '90CAF9'
	}, function (res) {

	
		$('#footer_player').empty();
		htmlToAppend = $(res.html);
		$(htmlToAppend).attr("id", "playa");
		$('#footer_player').append(htmlToAppend)
		
	});
*/

	/*
		var iframeElement=document.getElementById('playa');
		var widget=SC.Widget(iframeElement);
		widget.setVolume(0)
		widget.getVolume(function(res){
			console.dir(res)
		})
	*/
}

function setLoggedUser() {
	SC.get('/me', function (data) {
		
	loggedUser.username = data.username;
	loggedUser.img = data.avatar_url;
	loggedUser.id = data.id;

		$('#frontpage').fadeOut('slow',function(){
			$('#control_bar').fadeIn('slow')	
			$('.logged_in_avatar').attr("src", loggedUser.img).fadeIn('slow');
			$('.logged_in_username').html(loggedUser.username)
			$('#mobile_user').fadeIn('slow')
		})
		
	name1 = $('<div>' + loggedUser.username + '</div>').addClass('logged_in_username streamLoad')

		/* embed user home on login
        SC.oEmbed('https://api.soundcloud.com/users/'+loggedUser.id,{'maxheight':160,'color':'90CAF9'},function(res){
	
		$('#footer_player').empty();
		htmlToAppend=$(res.html);
		$(htmlToAppend).attr("id","playa");
		$('#footer_player').append(htmlToAppend)


	});
*/

	});

}

function setCurrentUser(user_type, list_type) {



	if (user_type === "me") {
		route = "/me";
		$('#stream, .streamLoad').fadeIn('slow');
	} else {
		route = user_type


	}
	currentUser.list_type = list_type

	SC.get(route, function (data) {

		$('#tracks').parent().attr({
			"data-UserId": data.id,
			'data-tooltip': data.username + ' tracks'
		});
		$('#likes').parent().attr({
			"data-UserId": data.id,
			'data-tooltip': data.username + ' likes'
		});
		$('#stream').parent().attr({
			"data-UserId": data.id,
			'data-tooltip': data.username + ' stream'
		});
		$('.control_btn').attr({
			"data-UserId": data.id
		});

		$('.brand-logo').attr({
			'data-tooltip': currentUser.username + ' stream'
		});

		currentUser.username = data.username;
		currentUser.img = data.avatar_url;
		$('#current_user_avatar').attr("src", currentUser.img);
		$('#current_user_username').html(currentUser.username);

		if (currentUser.username != loggedUser.username) {
			$('#stream').fadeOut('slow');
		}
		//console.log(route+": : "+list_type)
		user_history.push({
			'user_type': route,
			'full_route': route + "/" + list_type
		})

	});




	$('.control_btn i').css({
		"background-color": "#26a69a !important"
	})

	if (list_type === "activities") {

		$('#stream i').css({
			"background-color": "#ff5722 !important"
		})
	} else if (list_type === "tracks") {
		$('#tracks i').css({
			"background-color": "#ff5722 !important"
		})
	} else if (list_type === "favorites") {
		$('#likes i').css({
			"background-color": "#ff5722 !important"
		})

	}

}

function setCookie(c_name, value, exdays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value = escape(value) +
		((exdays == null) ? "" : ("; expires=" + exdate.toUTCString()));
	document.cookie = c_name + "=" + c_value;
}

function getCookie(c_name) {
	var i, x, y, ARRcookies = document.cookie.split(";");
	for (i = 0; i < ARRcookies.length; i++) {
		x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
		y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
		x = x.replace(/^\s+|\s+$/g, "");
		if (x == c_name) {
			return unescape(y);
		}
	}
}

function deleteCookies() {
	document.cookie = 'SCaccess=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	document.cookie = 'SCroute=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function secondsToString(seconds) {

	var numhours = Math.floor(((seconds % 31536000) % 86400) / 3600);
	var numminutes = Math.floor((((seconds % 31536000) % 86400) % 3600) / 60);
	var numseconds = Math.floor((((seconds % 31536000) % 86400) % 3600) % 60);

	if (numminutes < 10) {
		numminutes = "0" + numminutes
	}

	if (numseconds < 10) {
		numseconds = "0" + numseconds
	}

	return numhours + ":" + numminutes + ":" + numseconds




}

function parseDate(str) {
	var ymd = str.split('/')

	return new Date(ymd[0], ymd[1] - 1, ymd[2]);
}

function daydiff(first, second) {


	return Math.round((second - first) / (1000 * 60 * 60 * 24));
}