@extends('layout.app')

@section('nav')
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{route('newcustomer')}}">
    <i class="bi bi-plus-lg"></i>Customer 
  </a>
</div>
@endsection
@section('isi')
<div class="container card p-2" style="min-height: 80vh">
  <p><i class="bi bi-person-fill"></i>Customer:</p>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><a href=""><i class="bi bi-person-fill"></i>Customer 1</a></li>
    <li class="list-group-item"><a href=""><i class="bi bi-person-fill"></i>Customer 2</a></li>
  </ul>
</div>
@endsection