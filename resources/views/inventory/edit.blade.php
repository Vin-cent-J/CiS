@extends("layout.app")

@section('isi')
<div class="container p-3 card">
  <div>
    <div class="form-group">
      <label for="prodName">Nama: </label>
      <input type="text" class="form-control" id="name">
    </div>
    <div class="form-group">
      <label for="prodPrice">Harga: </label>
      <input type="text" class="form-control" id="price">
    </div>
    <div class="form-group">
      <label for="prodStock">stok: </label>
      <input type="number" class="form-control" id="stock">
    </div>
    <div class="form-group m-2">
      <label for="prodPhoto">Foto:</label>
      <input type="file" class="form-control-file" id="foto" name="photo">
    </div>

    <a type="button" class="btn btn-warning">Ubah</a>
  </div>
</div>
@endsection