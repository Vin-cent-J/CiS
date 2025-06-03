@extends('layout.app')

@section("nav")
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/inventory')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
</div>
@endsection