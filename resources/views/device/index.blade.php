@extends('layouts.app')

@section('content')
    <!-- Create Task Form... -->

    <!-- Display Validation Errors -->
       @include('common.errors')
<div class="row">
  <div class="col s12 m6 l12">
    <div class="card blue-grey darken-1">
      <div class="card-content white-text">
        <span class="card-title">Devices</span>
        <p>Ici se trouve la liste de tous les périhpériques.</p>
      </div>
    </div>
  </div>

  <!-- Current Devices -->
  @if (count($devices) > 0)
  <div class="col s8">
    <div class="card blue-grey darken-1">
      <div class="card-content white-text">
        <span class="card-title">Devices</span>
        <table>
          <thead>
            <tr>
                <th data-field="id">Id</th>
                <th data-field="name">Name</th>
                <th data-field="code">Code</th>
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
                    <i class="fa fa-download"></i> <!-- INPUT -->
                  @elseif ($device->type == '3')
                    <i class="fa fa-server"></i> <!-- WOL -->
                  @elseif ($device->type == '4')
                    <i class="fa fa-leaf"></i> <!-- TEMPERATURE -->
                  @endif
                   {{ $device->name }}
                </td>
                <td>
                  {{ $device-> code }}
                </td>
                <td>
                  <form action="{{ url('device/'.$device->id) }}" method="POST">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="waves-effect waves-teal btn red" id="delete-task-{{ $device->id }}">
                        <i class="fa fa-trash left"></i> Supprimer
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif
   <div class="col s4 ">
     <!-- New Task Form -->
     <div class="card grey lighten-3">
       <div class="card-content black-text">
         <span class="card-title">Ajout</span>
           <form action="{{ url('/device') }}" method="POST" class="form-horizontal">
              {!! csrf_field() !!}
              <div class="row">
                <div class="input-field col s12">
                  <input id="name" type="text" name="name" class="validate">
                  <label for="name">Name</label>
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
             </div>
           </form>
         </div>
       </div>
   </div>
</div>
@endsection
