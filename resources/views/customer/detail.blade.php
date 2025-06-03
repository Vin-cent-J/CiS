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
  <form action="{{url('/customer/'.$customer->id)}}" method="post">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="name">Nama Customer: </label>
      <input type="text" class="form-control" name="name" id="name" value="{{ $customer->name }}">
    </div>
    <div class="form-group">
      <label for="phone">Nomor HP: </label>
      <input type="text" class="form-control" name="phone" id="phone" value="{{ $customer->phone_number }}">
    </div>
    <div class="form-group">
      <label for="address">Alamat: </label>
      <input type="text" class="form-control" name="address" id="address" value="{{ $customer->address }}">
    </div>
    <input class="btn btn-primary m-2" type="submit" value="Ubah"></button>
  </form>
</div>
@endsection