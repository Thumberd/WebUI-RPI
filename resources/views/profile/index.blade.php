@extends('layouts.app')

@section('content')
      <div class="row">
        <div class="col s12 m6 l12">
          <div class="card blue-grey darken-1">
            <div class="card-content white-text">
              <span class="card-title">User information</span>
              <p>Ici se retrouve toutes les informations que le système aimerais connaitre de vous. La raison de ce besoin est détaillée dans chaque cas.</p>
            </div>
          </div>
        </div>
        <div class="col s12 m12 l12">
          <div class="col s6">
            <div class="card grey lighten-3">
              <div class="card-content black-text">
                <span class="card-title">Mail</span>
                <p>Afin de pouvoir vous envoyer des mails il est essentiel de nous le donner !</p>
              </div>
            </div>
          </div>
          <div class="col s6">
            <div class="card grey lighten-3">
              <div class="card-content black-text">
                <div class="row valign-wrapper">
                  <form action="{{ url('/profile/mail') }}" method="POST" class="form-horizontal">
                     {!! csrf_field() !!}
                    <div class="input-field col s8 valign">
                      <input id="mail" name="mail" type="text" class="validate" value="{{ Auth::user()->email }}"/>
                      <label for="mail">Mail</label>
                    </div>
                    <div class="input-field col s4 valign">
                      <button type="submit" class="waves-effect waves-light btn">
                        Save
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Api Free -->
        <div class="col s12 m12 l12">
          <div class="col s6">
            <div class="card grey lighten-3">
              <div class="card-content black-text">
                <span class="card-title">Api Free's key</span>
                <p>In order to provide you have to give us the credentials.</p>
              </div>
            </div>
          </div>
          <div class="col s6">
            <div class="card grey lighten-3">
              <div class="card-content black-text">
                <div class="row valign-wrapper">
                  <form action="{{ url('/profile/apifree') }}" method="POST" class="form-horizontal">
                     {!! csrf_field() !!}
                     <div class="input-field col s4 valign">
                       <input id="user" name="user" type="text" class="validate" value="{{ $apifree->user or ' ' }}"/>
                       <label for="user">User</label>
                     </div>
                     <div class="input-field col s4 valign">
                       <input id="password" name="password" type="text" class="validate" value="{{ $apifree->key or ' ' }}"/>
                       <label for="password">Password</label>
                     </div>
                    <div class="input-field col s4 valign">
                      <button type="submit" class="waves-effect waves-light btn">
                        Save
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RFID tag -->
        <div class="col s12 m12 l12">
          <div class="col s6">
            <div class="card grey lighten-3">
              <div class="card-content black-text">
                <span class="card-title">RFID tag</span>
                <p>In order to authenticate give us your RFID tag</p>
              </div>
            </div>
          </div>
          <div class="col s6">
            <div class="card grey lighten-3">
              <div class="card-content black-text">
                <div class="row valign-wrapper">
                  <form action="{{ url('/profile/apifree') }}" method="POST" class="form-horizontal">
                     {!! csrf_field() !!}
                     <div class="input-field col s4 valign">
                       <input disabled id="disabled" name="user" type="text" class="validate" value="{{ Auth::user()->RFID }}"/>
                       <label for="user">RFID</label>
                     </div>
                    <div class="input-field col s4 valign">
                      <button type="submit" class="waves-effect waves-light btn">
                        Save
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
            @endsection
