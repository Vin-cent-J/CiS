@extends('layout.app')

@section('nav')
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/supplier')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
</div>
@endsection

@section('isi')
<div class="container card p-2">
  <form method="post" action="{{route('supplier.store')}}">
    @csrf
    <div class="form-group">
      <label for="prodName">Nama pemasok<span class="text-danger">*</span> : </label>
      <input type="text" class="form-control" name="name" id="prodName" required>
    </div>
    <div class="form-group">
      <label for="custEmail">email: </label>
      <input type="text" class="form-control" name="email" id="custEmail">
    </div>
    <div class="form-group">
      <label for="prodPrice">No. HP<span class="text-danger">*</span> : </label>
      <input type="number" class="form-control" name="phone" id="prodPrice" required>
    </div>
    <div class="form-group mb-2">
      <label for="prodStock">Alamat<span class="text-danger">*</span> : </label>
      <input type="text" class="form-control" name="address" id="prodStock" required>
    </div>
    <button type="submit" class="btn btn-warning w-100" value="Submit">Simpan</button>
  </form>
</div>
@endsection