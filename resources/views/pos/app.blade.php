@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{route('poshistory')}}">
    riwayat
  </a>
</nav>
@endsection
@section("isi")
<div class="container-fluid card" style="min-height: 84vh;">
  <div class="row flex-grow-1">

    <div class="col-4 p-2 card">
      <h5><strong><i class="bi bi-cart"></i>Keranjang:</strong></h5>
      <div class="row p-1 bg-light card">
        <div class="col d-flex justify-content-between">
          <div>
            <strong>Barang 1</strong>
          </div>
          <div>
            <strong>Rp.1000</strong>
          </div>
        </div>
        <div class="text-muted">
          <strong>1 </strong><small>x Rp.1000 / Unit</small>
        </div>
        <div class="text-muted d-flex justify-content-between">
          <p>Diskon: <input type="number">%</p> 
          <a href="#"><i class="bi bi-trash"></i></a>
        </div>
      </div>

      
      <div class="row card w-100 position-absolute bottom-0 p-1">
        <div class="diskontotal">
          <div class="m-1">
            Diskon:
            <input type="number" class="form-control">
          </div>
          <div class="text-end">
            Total: <strong>Rp.1000</strong>
            <hr>
          </div>
        </div>
        <div class="m-2 text-end">
          <a type="button" class="btn btn-warning" href="#">Simpan</a>
        </div>        
      </div>
    </div>

    <div class="col-8 bg-light p-2">
      <Strong><i class="bi bi-box-seam-fill"></i>Katalog:</Strong>
      <div class="col-2 m-2 card" style="height: 27vh;">
        <img src="https://picsum.photos/200/300" style="max-height: 50%;">
        <div class="p-2">
          <div>
            <strong>Barang 1</strong>
          </div>
          <div class="text-muted">
            <small>Rp. 1000</small>
          </div>
          <button class="btn-sm btn-primary" style="float: right;">
            + Tambah
          </button>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection