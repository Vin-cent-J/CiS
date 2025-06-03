@extends("layout.app")

@section("nav")
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('category')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
</div>
@endsection

@section("isi")
<div class="container card p-2" style="height: 25vh; width: 30%;">
    <form action="{{'/category'}}" method="post">
      @csrf
      <label for="categoryName">Nama Kategori: </label>
      <input type="text" class="form-control" id="categoryName" name="name" style="width: 70%"><br>
      <input type="submit" value="Tambah" class="btn btn-primary" style="width: 80px;">
    </form>
</div>
@endsection