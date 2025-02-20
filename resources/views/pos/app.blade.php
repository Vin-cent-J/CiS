@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <button type="button" class="btn btn-warning">
    History
  </button>
</nav>
@endsection
@section("isi")
<div class="p-3 container-fluid card bg-white" style="min-height: 785px;">
  <div class="row" style="height: 100%;">
    <div class="col-4 card bg-secondary" style="height: 100%;">
      abc
    </div>
    <div class="col-8 card bg-dark">
      abc
    </div>
  </div>
</div>

@endsection