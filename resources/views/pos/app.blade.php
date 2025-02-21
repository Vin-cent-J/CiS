@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <button type="button" class="btn btn-warning">
    History
  </button>
</nav>
@endsection
@section("isi")
<div class="container-fluid card" style="min-height: 84vh;">
  <div class="row flex-grow-1">

    <div class="col-4 p-2 card">
      <h5><strong><i class="bi bi-cart"></i>Cart:</strong></h5>
      <div class="row p-2 bg-light card">
        <div class="col d-flex justify-content-between">
          <div>
            <strong>Barang 1</strong>
          </div>
          <div>
            <strong>Rp.1000</strong>
          </div>
        </div>
        <div class="text-muted">
          <strong>1 </strong><small>x Rp.1000 / Units</small>
        </div>
      </div>
    </div>

    <div class="col-8 bg-light p-2">
      <Strong><i class="bi bi-box-seam-fill"></i>Catalog:</Strong>
      <div class="col-2 m-2 card" style="height: 27vh;">
        <img src="https://picsum.photos/200/300" style="max-height: 60%;">
        <div class="p-2">
          <div>
            <strong>Barang 1</strong>
          </div>
          <div class="text-muted">
            <small>Rp. 1000</small>
          </div>
          <button class="btn-sm btn-primary" style="float: right;">
            + Add
          </button>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection