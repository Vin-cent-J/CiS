<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield("judul")</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <style>
  a .card {
    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  }

  a .card:hover {
    border-color: var(--bs-primary);
    box-shadow: var(--bs-box-shadow-lg);
  }

  li a:hover {
    text-shadow: var(--bs-box-shadow-lg);
  }
  </style>

</head>
<body class="bg-light">
  <header class="px-3 py-2 bg-dark text-white fs-5 border-bottom">
    <div class="container-fluid">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <div class="me-auto">
          <p class=" my-auto d-flex flex-wrap justify-content-center justify-content-lg-start"><i class="bi bi-building-fill"></i></p>
        </div>
        <div>
          <ul class="nav col-12 col-lg-auto text-small">
            <li>
              <a class="nav-link text-white" href="{{ url("/") }}"><i class="bi bi-house-fill text-warning"></i>&nbsp;Beranda</a>
            </li>
            <li>
              <a class="nav-link text-white" href="{{ url("/settings") }}"><i class="bi bi-gear-fill text-warning"></i>&nbsp;Pengaturan</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>
  @yield("nav")

  <div class="container-fluid px-2 py-3">
    @yield("isi")
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  @yield("js")
</body>
</html>