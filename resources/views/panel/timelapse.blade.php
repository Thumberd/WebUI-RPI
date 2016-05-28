@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col s12">
	<div class="card teal white-text">
	    <div class="card-image">
		<img id="timelapse" src="/media/img001.jpg" style="height:500px;"/>
		<span class="card-title">Timelapse</span>
	    </div>
	    <div class="card-action">
		<a onclick="Stop()" style="waves-effect btn waves-red teal darken-3">Pause</a>
		<a onclick="Continue()" style="waves-efect waves-light btn teal">Reprendre</a>
	    </div>
	</div>
    </div>
</div>
@endsection

@section('JS')
<script>
	var count = 0;
	function change(){
		console.log('change');
		count += 1;
		if(count < 10) {
			$('#timelapse').attr('src', '/media/img00' + count + '.jpg');
		}
		else if (count == 21) {
			count = 0;
		}
		else {
			$('#timelapse').attr('src', '/media/img0' + count + '.jpg');
		}
	}
	
	var interval = setInterval(change, 1000);
	function Stop() {
		clearInterval(interval);
	}
	
	function Continue() {
		interval = setInterval(change, 1000);
	}
</script>
@endsection
