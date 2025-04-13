@extends("layout.app")
@section("isi")
<div class="row px-4 my-4 d-flex">
  @foreach ($features as $feature)
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route("$feature->route") }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="{{$feature->icon}} text-primary"></i> {{$feature->name}}</p>
      </div>
    </div>
  </a>
  @endforeach
  <!--
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('pos') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-shop text-primary"></i> Penjualan Ditempat</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('sales')}}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-cart2 text-primary"></i> Penjualan</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('purchase') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-credit-card text-primary"></i> Pembelian</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('inventory') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-box-seam text-primary"></i> Inventaris</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('report') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-clipboard-data text-primary"></i> Laporan</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('debt') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-cash-coin text-primary"></i> Piutang</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('customer') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-people text-primary"></i> Pelanggan</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('supplier') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-truck text-primary"></i> Supplier</p>
      </div>
    </div>
  </a>
  -->
</div>
@endsection