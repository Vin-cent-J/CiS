@extends("layout.app")

@section('isi')
<div class="container p-3 card">
  <div>
    <p>Name: </p><input type="text">
    <p>Price: </p><input type="text">
    <p>Stock: </p><input type="text">
    <p>Photo: <input type="file" name="photo"></p>

    <a type="button" class="btn btn-warning">Add</a>
  </div>
</div>
@endsection