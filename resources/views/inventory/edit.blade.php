@extends("layout.app")

@section('title', 'Barang | Ubah Barang')

@section("nav")
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/inventory')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
</div>
@endsection

@section('isi')
<div class="container p-3 card">
  <form action="{{route('inventory.update', $product->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div>
      <div class="form-group">
        <label for="prodName">Nama: </label>
        <input type="text" class="form-control" id="name" name="name" value="{{$product->name}}">
      </div>
      <label for="prodCategory">Kategori: </label>
      <select name="category_id" id="prodCategory">
        @foreach ($categories as $category )
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
      <div class="form-group">
        <label for="prodPrice">Harga: </label>
        <input type="text" class="form-control" id="price" name="price" value="{{$product->price}}">
      </div>
      <div class="form-group">
        <label for="prodStock">stok: </label>
        <input type="number" class="form-control" id="stock" name="stock" value="{{$product->stock}}">
      </div>
      <div class="form-group m-2">
        <label for="foto">Foto:</label>
        <input type="file" class="form-control-file" id="foto" name="photo"><br>
        @if (isset($product->photo))
        <img src="/storage/{{$product->photo}}" alt="Product Image" style="max-height: 200px; max-width: 200px;">
        @endif
      </div>
  
      <input type="submit" value="Simpan" class="btn btn-primary">
    </div>
  </form>
</div>

@if ($features->contains(12))
<div class="container p-3 card">    
  <h4><strong>Varian</strong></h4>
  <hr>
  <button id="tambahVarian" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">+ Tambah</button>
  <ul class="list-group mt-3">
    @foreach ($product->variants as $variant )
      <li class="list-group-item">
        <strong>{{ $product->name }}</strong> - {{ $variant->name }} <br>
        Rp.{{ $variant->price }} <br>
        Stok: {{ $variant->stock }}
      </li>
    @endforeach
  </ul>
</div>
@endif

<div id="tambahModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Varian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/inventory/addVariant')}}" method="post">
          @csrf
          <input type="hidden" name="id" value="{{ $product->id }}">
          <input type="text" name="name" placeholder="Nama Varian baru" class="form-control m-2">
          <input type="number" name="price" placeholder="Harga Varian" class="form-control m-2">
          <input type="number" name="stock" placeholder="Stok Varian" class="form-control m-2">
          <input type="submit" value="Simpan" class="btn btn-primary m-2">
        </form>
      </div>
    </div>
  </div>

</div>
@endsection