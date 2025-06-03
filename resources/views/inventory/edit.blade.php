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
@endsection