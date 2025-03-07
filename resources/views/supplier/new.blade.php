@extends('layout.app')

@section('isi')
<div class="container card p-2">
  <div class="form-group">
    <label for="prodName">Name: </label>
    <input type="text" class="form-control" id="prodName">
  </div>
  <div class="form-group">
    <label for="custEmail">email: </label>
    <input type="text" class="form-control" id="custEmail">
  </div>
  <div class="form-group">
    <label for="prodPrice">Phone number: </label>
    <input type="number" class="form-control" id="prodPrice">
  </div>
  <div class="form-group">
    <label for="prodStock">Address: </label>
    <input type="text" class="form-control" id="prodStock">
  </div>
  <a type="button" class="btn btn-warning">Add</a>
</div>
@endsection