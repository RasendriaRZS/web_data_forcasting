<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Website Data Forcasting</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Plus Jakarta Sans' rel='stylesheet'>

  {{-- animasi --}}
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


    {{-- CSS  --}}
    <link rel="stylesheet" href="css/navbar.css">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script> 
{{-- 
    <style>
    </style> --}}

  </head>
<body>
    {{-- navbar vertikal --}}
{{-- <div class="navbar">
    <div class="brand">
        <img src="logo.png" alt="Logo">
        <h1>Data <br>Forcasting</h1>
    </div>
    <a href="{{ route('index') }}" class="active"><i class="fas fa-home"></i> Home</a>
    <a href="{{ route('analytics') }}"><i class="fas fa-info-circle"></i> analytics</a>
    <a href="{{ route('settings') }}"><i class="fas fa-cogs"></i> settings</a>

 </div> --}}
 
 <div class="navbar">
    <div class="brand">
        <img src="logo.png" alt="Logo">
        <h1>Brand</h1>
    </div>
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
        <i class="fas fa-home"></i> Home
    </a>
    <a href="/analytics" class="{{ request()->is('analytics') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Analytics
    </a>
    <a href="/services" class="{{ request()->is('settings') ? 'active' : '' }}">
        <i class="fas fa-cogs"></i> settings
    </a>
</div>



<div class="main">
    @yield('main')
</div>


  
  
  {{-- manggil footer --}}
  {{-- @include('footer') --}}

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    

    {{-- animasi  --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>

{{-- javascript --}}
<script src="..."></script> 
  </body>

</html>