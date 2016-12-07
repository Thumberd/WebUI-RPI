@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col s12 m6 l12" id="Event">
  </div>
   <div class="col s12 m4 white-text">
     <div class="card {{ $color }} lighten-1">
	<div class="card-content">
	<span class="card-title">Alarmes</span>
       <table>
       <tbody id="Alarms">
         @foreach($alarms as $alarm)
          <tr>
            <td>{{ $alarm->device->name }}</td>
            <td id="alarm{{ $alarm->id }}"></td>
          </tr>
         @endforeach
       </tbody>
     </table>
     </div>
     </div>
   </div>

   <div class="col s12 m4">
     <div class="card {{ $color }} lighten-1">
       <div class="card-content white-text">
         <span class="card-title">Centre de contrôle</span>
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
      <div class="card {{ $color }} lighten-1">
        <div class="card-content white-text">
          <span class="card-title">Température</span>
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
      <div class="card {{ $color }} lighten-1">
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
<script src="{{ asset('js/AlarmBox.js') }}"></script>
<script src="{{ asset('js/wakeOnLan.js') }}"></script>
<script src="{{ asset('js/TemperatureBox.js') }}"></script>
<script src="{{ asset('js/Event.js') }}"></script>
<script src="{{ asset('js/GarageBox.js') }}"></script>
<script>
    var token_id = "{{ Auth::user()->token_id }}";
    var token_key = "{{ Auth::user()->token_key}}";

 ReactDOM.render(React.createElement(Event, {tokenID: token_id, tokenKey: token_key}), document.getElementById('Event'));
  @foreach ($wakeOnLan as $wol)
    ReactDOM.render(React.createElement(wakeOnLan, { id: "{{ $wol->id }}", tokenID: token_id,
      tokenKey: token_key}), document.getElementById('wol{{ $wol->id }}'));
  @endforeach
  @foreach ($temperaturesDevices as $temperaturesDevice)
    ReactDOM.render(React.createElement(TemperatureBox, { id: "{{ $temperaturesDevice->id }}",
      tokenID: token_id, tokenKey: token_key}), document.getElementById('temp{{ $temperaturesDevice->id }}'));
  @endforeach
  @foreach ($alarms as $alarm)
    ReactDOM.render(React.createElement(AlarmBox, { id: "{{ $alarm->device->id }}",
      name: "{{ $alarm->device->name }}", tokenID: token_id, tokenKey: token_key}),
          document.getElementById('alarm{{ $alarm->id }}'));
  @endforeach
  @foreach ($garages as $garage)
    ReactDOM.render(React.createElement(GarageBox, { id: "{{ $garage->id }}", name: "{{ $garage->name }}",
      tokenID: token_id, tokenKey: token_key}), document.getElementById('garage{{ $garage->id }}'));
  @endforeach
</script>
@endsection
