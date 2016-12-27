@extends('layouts.app')

@section('content')

@include('common.errors')
<div class="row">
  <div class="col s12 m6 l12">
    <div class="card blue lighten-1">
      <div class="card-content white-text">
        <span class="card-title">Périphériques</span>

          @if (count($devices) > 0)
              <table>
                  <thead>
                  <tr>
                      <th data-field="id">Id</th>
                      <th data-field="name">Name</th>
                      <th data-field="code">Code / IP</th>
                      <th data-field="user">User / Password</th>
                      <th data-field="api">API token</th>
                      <th data-field="delete"> </th>
                  </tr>
                  </thead>

                  <tbody>
                  @foreach ($devices as $device)
                      <tr>
                          <td>
                              {{ $device->id }}
                          </td>
                          <td>
                              @if ($device->type == '1')
                                  <i class="fa fa-upload"></i> <!-- OUTPUT -->
                              @elseif ($device->type == '2')
                                  <i class="fa fa-bug"></i> <!-- ALARM -->
                              @elseif ($device->type == '3')
                                  <i class="fa fa-server"></i> <!-- WOL -->
                              @elseif ($device->type == '4')
                                  <i class="fa fa-leaf"></i> <!-- TEMPERATURE -->
                              @endif
                              {{ $device->name }}
                          </td>
                          <td>
                              {{ $device->code or '/' }} / {{ $device->ip or '/' }}
                          </td>
                          <td>
                              {{ $device->user or '/' }}:{{ $device->password or '/' }}
                          </td>
                          <td>
                              ID:  {{ $device->token_id or '/' }}<br /> Key: {{ $device->token_key or '/' }}
                          </td>
                          <td>
                              <form action="{{ url('device/'.$device->id) }}" method="POST">
                                  {!! csrf_field() !!}
                                  {!! method_field('DELETE') !!}
                                  <button type="submit" class="waves-effect waves-teal btn red" id="delete-task-{{ $device->id }}">
                                      <i class="fa fa-trash"></i>
                                  </button>
                              </form>
                              <a href="" class="waves-effect waves-teal btn teal" onclick="generateToken({{ $device->id }})"><i class="fa fa-repeat"></i></a>
                          </td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>
          @endif
      </div>
    </div>
  </div>
    <!-- Modal Trigger -->


    <!-- Modal Structure -->
    <div id="modal1" class="modal modal-fixed-footer">
        <form action="{{ url('/device') }}" method="POST" class="form-horizontal">
        <div class="modal-content">
            <h4>Ajout d'un périphérique</h4>
                {!! csrf_field() !!}
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" type="text" name="name" class="validate">
                        <label for="name">Name</label>
                    </div>
                    <div class="input-field col s4">
                        <input id="ip" type="text" name="ip"class="validate">
                        <label for="ip">IP</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="code" type="text" name="code"class="validate">
                        <label for="code">Code</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="type" type="number" name="type" class="validate">
                        <label for="type">Type</label>
                    </div>
                </div>
                <!-- Add Button -->
                <div class="card-action">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus left"></i> Ajouter
                    </button>
                </div>
            </div>
            </form>
        <div class="modal-footer">
            <button href="#!" type="submit" class="modal-action modal-close waves-effect waves-green btn-flat ">Ajouter</button>
        </div>
    </div>
@endsection


@section('JS')
<script>
$('.modal-opener').click(function(){
	 $('#modal1').openModal();
});

</script>
@endsection
