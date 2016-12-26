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
          <thead>
            <tr>
                <th data-field="id">Nom</th>
                <th data-field="pushon"></th>
            </tr>
          </thead>

          <tbody>
            @foreach ($wakeOnLan as $wol)
              <tr>
                <td><i class="fa fa-server"></i> {{ $wol->name }}</td>
                <td id="wol{{ $wol->id }}"></td>
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
           <thead>
             <tr>
                 <th data-field="id">Nom</th>
                 <th data-field="value">°C</th>
             </tr>
           </thead>

           <tbody>
		@foreach($temperaturesDevices as $tempDevice)
		<tr>
			<td><i class="fa fa-leaf"></i> {{ $tempDevice->name }}</td>
			<td id="temp{{ $tempDevice->id }}"></td>
		</tr>
		@endforeach
           </tbody>
         </table>
        </div>
      </div>
    </div>

    <div class="col s12 m4">
      <div class="card grey darken-2">
        <div class="card-content white-text">
          <span class="card-title">Garages</span>
		@foreach($garages as $garage)
	          <div id="garage{{ $garage->id }}">
	            
              </div>
        	 @endforeach
        </div>
      </div>
    </div>
@endsection

@section('JS')

    <script>
        var token_id = "{{ Auth::user()->token_id }}";
        var token_key = "{{ Auth::user()->token_key}}";

        var alarms = [];


        function initAlarmes(){
            $.get({
                url: '/api/v3/alarms',
                headers: {
                    "Token-Id": token_id,
                    "Token-Key": token_key
                },
                success: function(data, textStatus, xhr){
			console.log(data);
                    if(data['status'] == "success"){
                        for (var i = 0; i < data['data'].length; i++) {
                            var alarme = data['data'][i];
                            var state = "Désactivée";
                            if(alarme['state'] == 1) state = "Activée";
                            alarms.push(alarme);
                            $("#Alarmes").append('<tr> <td>' + alarme['device']['name'] + '</td><td><a class="waves-effect btn ' + state + '</td></tr>');
             	       }
                    }
               }
});
        }
        initAlarmes();
    </script>
<!--<script src="{{ asset('js/AlarmBox.js') }}"></script>
<script src="{{ asset('js/wakeOnLan.js') }}"></script>
<script src="{{ asset('js/TemperatureBox.js') }}"></script>
<script src="{{ asset('js/Event.js') }}"></script>
<script src="{{ asset('js/GarageBox.js') }}"></script>-->
<script>
    var token_id = "{{ Auth::user()->token_id }}";
    var token_key = "{{ Auth::user()->token_key}}";

/* ReactDOM.render(React.createElement(Event, {tokenID: token_id, tokenKey: token_key}), document.getElementById('Event'));
  @foreach ($wakeOnLan as $wol)
    ReactDOM.render(React.createElement(wakeOnLan, { id: "{{ $wol->id }}", tokenID: token_id,
      tokenKey: token_key}), document.getElementById('wol{{ $wol->id }}'));
  @endforeach
  @foreach ($temperaturesDevices as $temperaturesDevice)
    ReactDOM.render(React.createElement(TemperatureBox, { id: "{{ $temperaturesDevice->id }}",
      tokenID: token_id, tokenKey: token_key}), document.getElementById('temp{{ $temperaturesDevice->id }}'));
  @endforeach
  @foreach ($garages as $garage)
    ReactDOM.render(React.createElement(GarageBox, { id: "{{ $garage->id }}", name: "{{ $garage->name }}",
      tokenID: token_id, tokenKey: token_key}), document.getElementById('garage{{ $garage->id }}'));
  @endforeach*/
</script>
@endsection
