@extends('layouts.app')

@section('content')
<div class="row">
        <div class="col s12 m6 l12">
          <div class="card orange lighten-3">
            <div class="card-content grey-text">
              <span class="card-title">Bienvenue</span>
              @if (Auth::guest())
              <p>Connectez vous pour accéder à l'interface de gestion de votre système.</p>
              @else
              <p>
                Redirection nécéssaire, cliquez sur continuer.
              </p>
              @endif
            </div>
            <div class="card-action ">
              @if (Auth::guest())
              <a href="{{ url('/login') }}" class="waves-effect waves-teal btn-flat white-text">Connexion</a>
              @else
              <a href="{{ url('/panel') }}" class="waves-effect waves-teal btn-flat white-text">Continuer</a>
              @endif
            </div>
          </div>
        </div>
      </div>@endsection
