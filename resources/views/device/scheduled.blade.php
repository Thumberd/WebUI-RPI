@extends('layouts.app')

@section('content')
	@if (count($scheduled) > 0)
		<div class="col s12">
		    <div class="card grey">
		      <div class="card-content white-text">
		        <span class="card-title">Scheduled Alarms</span>
		        <table>
		          <thead>
		            <tr>
                		<th data-field="id">Id</th>
		                <th data-field="name">Name</th>
                		<th data-field="code">Begin Time</th>
		                <th data-field="user">End Time</th>
		            </tr>
		          </thead>
		          <tbody>
		            @foreach ($scheduled as $sAlarm)
		              <tr>
				<td>{{ $sAlarm->id }}</td>
				<td>{{ $sAlarm->alarm->device->name }}</td>
				<td>{{ $sAlarm->beginHour }}:{{ $sAlarm->beginMinute }}</td>
				<td>{{ $sAlarm->endHour }}:{{ $sAlarm->endMinute }}</td>
			      </tr>
			    @endforeach
		 	 </tbody>
			</table>
		     </div>
		</div>
	      </div>
	@endif
	<div class="col s12">
		<div class="card white">
			<div class="card-content grey-text">
				<span class="card-title">Add</span>
				<form action="{{ url('/alarms/scheduled/add') }}" method="POST" class="form-horizontal">
              {!! csrf_field() !!}
              <div class="row">
		<div class="input-field col s2">
                  <input id="alarmId" type="number" name="alarmId" class="validate">
                  <label for="alarmId">Begin Hour</label>
                </div>
                <div class="input-field col s2">
                  <input id="beginHour" type="number" name="beginHour" class="validate">
                  <label for="beginHour">Begin Hour</label>
                </div>
                <div class="input-field col s2">
                  <input id="beginMinute" type="number" name="beginMinute" class="validate">
                  <label for="beginMinute">Begin Minute</label>
                </div>
                <div class="input-field col s2">
                  <input id="endHour" type="number" name="endHour" class="validate">
                  <label for="endHour">End Hour</label>
                </div>
                <div class="input-field col s2">
                  <input id="endMinute" type="number" name="endMinute" class="validate">
                  <label for="endMinute">End Minute</label>
                </div>
              </div>
               <!-- Add Button -->
               <div class="card-action">
                 <button type="submit" class="btn btn-default">
                    <i class="fa fa-plus left"></i> Ajouter
                 </button>
                </div>
              </div>
             </div>
           </form>

			</div>
		</div>
	</div>
@endsection

