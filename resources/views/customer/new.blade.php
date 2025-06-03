@extends('layout.app')
@section("nav")
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('customer')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
</div>
@endsection

@section('isi')
<div class="container card p-2">
  <form action="{{url('/customer')}}" method="post">
    @csrf
    <div class="form-group">
      <label for="name">Nama Customer: </label>
      <input type="text" class="form-control" name="name" id="name">
    </div>
    <div class="form-group">
      <label for="phone">Nomor HP: </label>
      <input type="number" class="form-control" name="phone" id="phone">
    </div>
    <div class="form-group">
      <label for="address">Alamat: </label>
      <input type="text" class="form-control" name="address" id="address">
    </div>
    <input class="btn btn-primary m-2" type="submit" value="Tambah"></input>
  </form>
</div>
@endsection