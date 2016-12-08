@extends('layouts.app')

@section('content')
<script type="text/javascript" src="/js/canvasjs.min.js"></script>
<div class="row">
   <div class="col s12 white-text">
     <div class="card grey">
        <div class="card-content">
        <span class="card-title">Température</span>
	<div id="chartContainer" style="height: 300px; width: 100%;"></div>
        </div>
     </div>
   </div>

   <div class="col s12 white-text">
     <div class="card grey">
        <div class="card-content">
        <span class="card-title">Humidité</span>
        <div id="humContainer" style="height: 300px; width: 100%;"></div>
        </div>
     </div>
   </div>

 <div class="col s12 white-text">
     <div class="card grey">
        <div class="card-content">
        <span class="card-title">Humidité</span>
        <div id="PhumContainer" style="height: 300px; width: 100%;"></div>
        </div>
     </div>
   </div>
@endsection

@section('JS')
<script type="text/javascript">
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
        text: "Température"
    },
    axisX:{
        title: "Date",
    },
    axisY: {
        title: "Température"
    },
    data: [
    @foreach($tempDevices as $device)
    {        
        type: "line",
	name: '{{ $device['name'] }}',
	showInLegend: true,
        dataPoints: [//array
        @foreach($temps[$device['id']] as $temp)
        { x: new Date('{{ $temp['created_at'] }}'), y: {{ $temp['value'] }}},
	@endforeach
        ]
    },
    @endforeach
    ]
});
    var HumChart = new CanvasJS.Chart("humContainer",
    {
      title:{
        text: "Humidité"
    },
    axisX:{
        title: "Date",
    },
    axisY: {
        title: "Humidité"
    },
    data: [
    @foreach($tempDevices as $device)
    {
        type: "line",
	name: '{{ $device['name'] }}',
	showInLegend: true,
        dataPoints: [//array
        @foreach($hums[$device['id']] as $hum)
        { x: new Date('{{ $hum['created_at'] }}'), y: {{ $hum['value'] }}},
        @endforeach
        ]
    },
    @endforeach
    ]
});

var PHumChart = new CanvasJS.Chart("PhumContainer",
    {
      title:{
        text: "Humidité des Plantes"
    },
    axisX:{
        title: "Date",
    },
    axisY: {
        title: "Humidité"
    },
    data: [
    @foreach($tempDevices as $device)
    {
        type: "line",
        name: '{{ $device['name'] }}',
        showInLegend: true,
        dataPoints: [//array
        @foreach($pHum[$device['id']] as $hum)
        { x: new Date('{{ $hum['created_at'] }}'), y: {{ $hum['value'] }}},
        @endforeach
        ]
    },
    @endforeach
    ]
});
     PHumChart.render();
     HumChart.render();
    chart.render();
}
</script>
@endsection
