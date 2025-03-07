@extends("layout.app")

@section('isi')
<div class="container p-3 card">
  <div>
    <div class="form-group">
      <label for="prodName">Name: </label>
      <input type="text" class="form-control" id="prodName">
    </div>
    <div class="form-group">
      <label for="prodPrice">Price: </label>
      <input type="text" class="form-control" id="prodPrice">
    </div>
    <div class="form-group">
      <label for="prodStock">stock: </label>
      <input type="number" class="form-control" id="prodStock">
    </div>
    <div class="form-group m-2">
      <label for="prodPhoto">Photo:</label>
      <input type="file" class="form-control-file" id="prodPhoto" name="photo">
    </div>

    <a type="button" class="btn btn-warning">Add</a>
  </div>
</div>
@endsection