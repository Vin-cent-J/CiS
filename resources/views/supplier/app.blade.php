@extends('layout.app')

@section('nav')
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{route('newsupplier')}}">
    <i class="bi bi-plus-lg"></i>Supplier 
  </a>
</div>
@endsection
@section('isi')
<div class="container card p-2" style="min-height: 80vh">
  <p><i class="bi bi-person-fill"></i>Supplier:</p>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><a href=""><i class="bi bi-truck"></i>Supplier 1</a></li>
    <li class="list-group-item"><a href=""><i class="bi bi-truck"></i>Supplier 2</a></li>
  </ul>
</div>
@endsection