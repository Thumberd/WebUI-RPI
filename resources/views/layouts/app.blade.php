<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Interface d'administration | Raspberry Pi</title>

    <!-- Fonts -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">

  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}">

    <style>
        p {
            font-family: 'Quicksand', 'Roboto', sans-serif;
        }
	.card-title {
	    font-family: 'Quicksand', sans-serif;
	}
    </style>
</head>

<body id="app-layout">
  <nav>
    <div class="nav-wrapper grey darken-4">
      <a href="#" class="brand-logo center"><img style="height: 70px;" src="{{ asset('media/logo.png') }}"></a>
      <a href="#" data-activates="mobile-demo" class="button-collapse7"><i class="fa fa-bars"></i></a>

      <ul id="nav-mobile" class="left hide-on-med-and-down">
        @if (Auth::guest())
          <li><a href="{{ url('/login') }}">Connexion</a></li>
        @else
          <li><a href="{{ url('/panel') }}">Panel</a></li>
          <li><a href="{{ url('/devices') }}">Périphériques</a></li>
          <li><a href="{{ url('/chart') }}">Courbes</a></li>
          <li><a class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></a></li>
          <!-- Dropdown Structure -->
          <ul id='dropdown1' class='dropdown-content'>
            <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i>Mes infos</a></li>
            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Déconnexion</a></li>
          </ul>
        @endif
      </ul>

      <ul class="side-nav" id="mobile-demo">
        @if (Auth::guest())
          <li><a href="{{ url('/login') }}">Connexion</a></li>
        @else
          <li><a href="{{ url('/panel') }}">Panell</a></li>
          <li><a href="{{ url('/devices') }}">Périphériques</a></li>
          <li><a href="{{ url('/chart') }}">Courbes</a></li>
          <li><a class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></a></li>
          <!-- Dropdown Structure -->
          <ul id='dropdown1' class='dropdown-content'>
            <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i>Mes infos</a></li>
            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Déconnexion</a></li>
          </ul>
        @endif
      </ul>
    </div>
  </nav>


    @yield('content')


    <!-- Compiled and minified JavaScript -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/materialize.min.js') }}"></script>
    <script>
      $( document ).ready(function(){
        $(".dropdown-button").dropdown();
      });
	$('.button-collapse').sideNav();
    </script>

    @yield('JS')
</body>
</html>

