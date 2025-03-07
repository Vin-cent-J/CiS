@extends("layout.app")

@section("nav")
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{route('newinventory')}}">
    <i class="bi bi-plus-lg"></i> Item
  </a>
</div>
@endsection

@section("isi")
<Strong><i class="bi bi-box-seam-fill"></i>Catalog:</Strong>
<div class="col-2 m-2 card" style="height: 25vh;">
  <img src="https://picsum.photos/200/300" style="max-height: 60%;">
  <div class="p-2">
    <div>
      <strong>Barang 1</strong>
    </div>
    <div class="text-muted">
      Rp. 1000
    </div>
  </div>
</div>
@endsection