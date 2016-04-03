@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col s12 m6 l12">
    <div class="card orange lighten-3">
      <div class="card-content white-text">
        <span class="card-title">Panel</span>
        <p>Infos générales</p>
      </div>
    </div>
  </div>

   <div class="col s4 " id="**AlarmBox">
     <div class="card indigo lighten-4">
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

   <div class="col s4">
     <div class="card grey darken-3">
       <div class="card-content white-text">
         <span class="card-title">Wake On Lan</span>
         <table>
          <thead>
            <tr>
                <th data-field="id">Name</th>
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

    <div class="col s4">
      <div class="card grey lighten-3">
        <div class="card-content black-text">
          <span class="card-title">Temperature</span>
          <table>
           <thead>
             <tr>
                 <th data-field="id">Name</th>
                 <th data-field="value">Value</th>
             </tr>
           </thead>

           <tbody id="TemperatureBox">

           </tbody>
         </table>
        </div>
      </div>
    </div>
@endsection

@section('JS')
<script src="/js/AlarmBox.js"></script>
<script src="/js/wakeOnLan.js"></script>
<script src="/js/TemperatureBox.js"></script>
<script>
  @foreach ($wakeOnLan as $wol)
    ReactDOM.render(React.createElement(wakeOnLan, { id: "{{ $wol->id }}", tokenID: "{{ Auth::user()->token_id}}", tokenKey: "{{ Auth::user()->token_key }}"}), document.getElementById('wol{{ $wol->id }}'));
  @endforeach
  @foreach ($temperaturesDevices as $temperaturesDevice)
    ReactDOM.render(React.createElement(TemperatureBox, { id: "{{ $temperaturesDevice->id }}", name: "{{ $temperaturesDevice->name }}", tokenID: "{{ Auth::user()->token_id}}", tokenKey: "{{ Auth::user()->token_key }}"}), document.getElementById('TemperatureBox'));
  @endforeach
  @foreach ($alarms as $alarm)
    ReactDOM.render(React.createElement(AlarmBox, { id: "{{ $alarm->device->id }}", name: "{{ $alarm->device->name }}", tokenID: "{{ Auth::user()->token_id}}", tokenKey: "{{ Auth::user()->token_key }}"}), document.getElementById('alarm{{ $alarm->id }}'));
  @endforeach

</script>
@endsection
