<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Interface d'administration</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
  <nav>
    <div class="nav-wrapper grey darken-1">
      <a href="#" class="brand-logo center">Raspberry Pi</a>
      <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="fa fa-bars"></i></a>

      <ul id="nav-mobile" class="left hide-on-med-and-down">
        <li><a href="{{ url('/') }}">Home</a></li>
        @if (Auth::guest())
          <li><a href="{{ url('/login') }}">Login</a></li>
          <li><a href="{{ url('/register') }}">Register</a></li>
        @else
          <li><a href="{{ url('/panel') }}">Panel</a></li>
          <li><a href="{{ url('/devices') }}">Devices</a></li>
          <li><a href="{{ url('/chart') }}">Chart</a></li>
          <li><a class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></a></li>
          <!-- Dropdown Structure -->
          <ul id='dropdown1' class='dropdown-content'>
            <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i>Mes infos</a></li>
            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
          </ul>
        @endif
      </ul>

      <ul class="side-nav" id="mobile-demo">
        <li><a href="{{ url('/') }}">Home</a></li>
        @if (Auth::guest())
          <li><a href="{{ url('/login') }}">Login</a></li>
          <li><a href="{{ url('/register') }}">Register</a></li>
        @else
          <li><a href="{{ url('/panel') }}">Panel</a></li>
          <li><a href="{{ url('/devices') }}">Devices</a></li>
          <li><a href="{{ url('/chart') }}">Chart</a></li>
          <li><a class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></a></li>
          <!-- Dropdown Structure -->
          <ul id='dropdown1' class='dropdown-content'>
            <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i>Mes infos</a></li>
            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
          </ul>
        @endif
      </ul>
    </div>
  </nav>


    @yield('content')

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-dom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.3.5/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>

    <script>
      $( document ).ready(function(){
        $(".dropdown-button").dropdown();
      });
	$('.button-collapse').sideNav();
    </script>

    @yield('JS')
</body>
</html>

