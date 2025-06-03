@extends("layout.app")

@section("nav")
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/inventory')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
</div>
@endsection

@section('isi')
<div class="container p-3 card">
  <div>
    <form action="{{url('/inventory')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="prodCategory">Kategori: </label>
        <select name="category_id" id="prodCategory">
          @foreach ($categories as $category )
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="prodName">Nama: </label>
        <input type="text" class="form-control" id="prodName" name="name">
      </div>
      <div class="form-group">
        <label for="prodPrice">Price: </label>
        <input type="text" class="form-control" id="prodPrice" name="price">
      </div>
      <div class="form-group">
        <label for="prodStock">stock: </label>
        <input type="number" class="form-control" id="prodStock" name="stock">
      </div>
      <div class="form-group m-2">
        <label for="prodPhoto">Photo:</label>
        <input type="file" class="form-control-file" id="prodPhoto" name="photo">
      </div>
      <input type="submit" value="Tambah" class="btn btn-primary">
    </form>
  </div>
</div>
@endsection