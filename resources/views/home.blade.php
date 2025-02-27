@extends("layout.app")
@section("isi")
<div class="row px-4 my-4 d-flex">
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('pos') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-shop text-primary"></i> Point of Sales</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('sales')}}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-cart2 text-primary"></i> Sales</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('purchase') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-credit-card text-primary"></i> Purchase</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('inventory') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-box-seam text-primary"></i> Inventory</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('report') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-clipboard-data text-primary"></i> Report</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('debt') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-cash-coin text-primary"></i> Debt Tracker</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('customer') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-people text-primary"></i> Customer</p>
      </div>
    </div>
  </a>
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{ route('customer') }}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="bi bi-truck text-primary"></i> Supplier</p>
      </div>
    </div>
  </a>
</div>
@endsection