@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col s12 m6 l12" id="Event">

  </div>
   <div class="col s12 m4 white-text">
     <div class="card blue lighten-1">
	<div class="card-content">
	<span class="card-title center-align">Alarmes</span>
       <table>
       <tbody id="Alarmes">
       </tbody>
     </table>
     </div>
     </div>
   </div>

   <div class="col s12 m4">
     <div class="card grey darken-2">
       <div class="card-content white-text">
         <span class="card-title">Réveil à distance</span>
         <table>
          <tbody>
            @foreach ($wakeOnLan as $wol)
              <tr>
                <td><i class="fa fa-server"></i> {{ $wol->name }}</td>
                <td><a class="waves-effect btn grey lighten-2 black-text" onclick="wakeOnLan({{ $wol->id }})">Allumer</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
       </div>
     </div>
    </div>

    <div class="col s12 m4">
      <div class="card blue lighten-1">
        <div class="card-content white-text">
          <span class="card-title">Températures</span>
          <table>
           <tbody id="Temperatures">
           </tbody>
         </table>
        </div>
      </div>
    </div>

    <div class="col s12 m4">
      <div class="card grey darken-2">
        <div class="card-content white-text">
          <span class="card-title">Garages</span>
	  <table>
       	     <tbody id="Garages">
             </tbody>
          </table>
        </div>
      </div>
    </div>
@endsection

@section('JS')

    <script>
    var token_id = "{{ Auth::user()->token_id }}";
    var token_key = "{{ Auth::user()->token_key}}";

    var alarms = [];
	var temperatures = [];
	var garages = [];

    var last_update_alarms;
    var last_update_garages;
    var last_update_temperatures;

        function getColorFromState(state){
            if(state == true){
                return "Activée/grey/darken-3";
            }
            else {
                return "Désactivée/grey/lighten-2 black-text"
            }
        }

	function getTextFromState(state) {
		if(state == true){
			return "Ouvert/grey/darken-3";
		}
		else {
			return "Fermé/grey/lighten-2 black-text";
		}
	}

        function initAlarmes(){
            $.get({
                url: '/api/v3/alarms',
                headers: {
                    "Token-Id": token_id,
                    "Token-Key": token_key
                },
                success: function(data, textStatus, xhr){
                    if(data['status'] == "success"){
                        last_update_alarms = new Date().toGMTString();
                        for (var i = 0; i < data['data'].length; i++) {
                            var alarme = data['data'][i];
                            var state = "Désactivée";
                            if(alarme['state'] == 1) state = "Activée";
                            alarms.push(alarme);
			    r = getColorFromState(alarme['state']).split('/');
                            $("#Alarmes").append('<tr> <td><p>' + alarme['device']['name'] + '</p></td><td>' +
                                    '<a class="waves-effect btn ' + r[1] + ' ' + r[2] + '" onclick="activateAlarm(' + alarme['device_id'] + ', ' + 
				alarme['id'] + ')" id="alarme' + alarme['id'] + '"><p>' +
                                    state + '</p></a></td></tr>');
             	       }
                    }
               }
            });
        }


	function initTemperatures(){
            $.get({
                url: '/api/v3/temperatures',
                headers: {
                    "Token-Id": token_id,
                    "Token-Key": token_key
                },
                success: function(data, textStatus, xhr){
                    if(data['status'] == "success"){
                        last_update_temperatures = new Date().toGMTString();
                        for (var i = 0; i < data['data'].length; i++) {
                            var temperature = data['data'][i];
                            temperatures.push(temperature);
                            var date = new Date(temperature['created_at'].replace(/-/g,'/'));
                                var aDate = new Date();
                            var color;
                                if (aDate - date > 1000 * 60 * 60){
                                    color = "red-text";
                            }
                            $("#Temperatures").append('<tr> <td><p class="' + color + '">' + temperature['device']['name'] + '</p></td><td>' +
                                    '<p id="temperature' + temperature['device_id'] + '" class="' + color + '"> ' + temperature['value'] + '°C</p></td></tr>');
                       }
                    }
               }
            });
        }

	function initGarages(){
            $.get({
                url: '/api/v3/garages',
                headers: {
                    "Token-Id": token_id,
                    "Token-Key": token_key
                },
                success: function(data, textStatus, xhr){
                    if(data['status'] == "success"){
                        last_update_garages = new Date().toGMTString();
                        for (var i = 0; i < data['data'].length; i++) {
                            var garage = data['data'][i];
                            garages.push(garage);
			    r = getTextFromState(garage['state']).split('/')
			    $("#Garages").append('<tr> <td><p>' + garage['name'] + '</p></td><td>' +
                                    '<a class="waves-effect btn ' + r[1] + ' ' + r[2] + '" ' +
                                    'onclick="openGarage(' + garage['id'] + ')"' + 'id="garage' + garage['id'] +
                                    '"><p>' + r[0] + '</p></a></td></tr>');
                       }
                    }
               }
            });
        }

        function activateAlarm(id, alarme_id){
            $.post({
                url: '/api/v3/alarms/' + id,
                headers: {
                    "Token-Id": token_id,
                    "Token-Key": token_key
                },
                success: function(data, textStatus, xhr){
                    if(data['status'] == "success"){
                        Materialize.toast(data['userInfo'], 4000);
                        r = getColorFromState(data['details']).split('/');
                        $("#alarme" + alarme_id)
				.html(r[0])
				.prop('class', 'waves-effect btn ' + r[1] + ' ' + r[2]);
                    }
                }

            })
        }

	function refreshAlarms(){
	    $.get({
                url: '/api/v3/alarms/',
                headers: {
                    "Token-Id": token_id,
                    "Token-Key": token_key,
                    "If-Modified-Since": last_update_alarms
                },
                success: function(data, textStatus, xhr){
                    last_update_alarms = new Date().toGMTString();
                    if(xhr.status == 200){
                        if(data['status'] == "success"){
                            for(var i=0; i < alarms.length; i++){
                            var alarme = data['data'][i];
                            r = getColorFromState(alarme['state']).split('/');
                            $("#alarme" + alarme['id'])
                                .html(r[0])
                                .prop('class', 'waves-effect btn ' + r[1] + ' ' + r[2]);
			                }
			            }
                    }
                }

            })
	}

	 function refreshGarages(){
            $.get({
                url: '/api/v3/garages/',
                headers: {
                    "Token-Id": token_id,
                    "Token-Key": token_key,
                    "If-Modified-Since": last_update_garages
                },
                success: function(data, textStatus, xhr){
                    last_update_garages = new Date().toGMTString();
                    if(xhr.status == 200){
                        if(data['status'] == "success"){
                            for(var i=0; i < garages.length; i++){
                                var garage = data['data'][i];
                                r = getTextFromState(garage['state']).split('/');
                                $("#garage" + garage['id'])
                                        .html(r[0])
                                        .prop('class', 'waves-effect btn ' + r[1] + ' ' + r[2]);
                            }
                        }
                    }
                }

            })
        }

    function refreshTemperatures(){
        $.get({
            url: '/api/v3/temperatures/',
            headers: {
                "Token-Id": token_id,
                "Token-Key": token_key,
                "If-Modified-Since": last_update_temperatures
            },
            success: function(data, textStatus, xhr){
                last_update_temperatures = new Date().toGMTString();
                if(xhr.status == 200){
                    if(data['status'] == "success"){
                        for(var i=0; i < temperatures.length; i++){
                            var temperature = data['data'][i];
                            var date = new Date(temperature['created_at'].replace(/-/g,'/'));
                            var aDate = new Date();
                            var color;
                            if (aDate - date > 1000 * 60 * 60){
                                color = "red-text";
                            }
                            $("#Temperatures").append('<tr> <td><p class="' + color + '">' + temperature['device']['name'] + '</p></td><td>' +
                                    '<p id="temperature' + temperature['device_id'] + '" class="' + color + '"> ' + temperature['value'] + '</p></td></tr>');
                        }
                    }
                }
            }

        })
    }

	function wakeOnLan(id){
            $.post({
                url: '/api/v3/wakeonlan/',
		        data: { id: id },
                headers: {
                    "Token-Id": token_id,
                    "Token-Key": token_key
                },
                success: function(data, textStatus, xhr){
                    if(data['status'] == "success"){
                        Materialize.toast(data['userInfo'], 4000);
                    }
                }

            })
        }

    function openGarage(id){
        $.post({
            url: '/api/v3/garages/' + id + '/up',
            headers: {
                "Token-Id": token_id,
                "Token-Key": token_key
            },
            success: function(data, textStatus, xhr){
                if(data['status'] == "success"){
                    Materialize.toast(data['userInfo'], 4000);
                    $("#alarme" + alarme_id)
                            .html(r[0])
                            .prop('class', 'waves-effect btn ' + r[1] + ' ' + r[2]);
                }
                else if(data['status'] == "pending"){
                    window.location.href = "/code";
                }
            }

        })
    }

    initAlarmes();
	initTemperatures();
	initGarages();
	setInterval(refreshAlarms, 5000);
	setInterval(refreshGarages, 5000);
	setInterval(refreshTemperatures, 250000);
    </script>
@endsection
