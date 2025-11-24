@extends("layout.app")

@section('title', 'Barang')

@section("nav")
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('inventory/create')}}">
    <i class="bi bi-plus-lg"></i> Barang
  </a>
  <a type="button" class="btn btn-warning" href="{{url('category')}}">
    Kategori
  </a>
</div>
@endsection

@section("isi")
<div class="col-2 p-2 m-2 card" style="min-height: 80vh; width: 99%;">
  <Strong><i class="bi bi-box-seam-fill"></i>Katalog:</Strong>
  <hr>
  <div class="row px-2">
  @foreach ($products as $product)
    <div class="card col-1 p-2 mx-1">
      <a href="{{url('inventory/'.$product->id.'/edit')}}" class="text-decoration-none text-dark">
        <img src="/storage/{{$product->photo}}" style="max-height: 60%; max-width: 7vw;">
        <div>
          <strong>{{$product->name}}</strong>
        </div>
        <div class="fw-bold text-secondary">
          Rp. {{$product->price}} <br>
          Stok: {{ $product->stock }}
        </div>
        <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah yakin ingin menghapus {{$product->name}}?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger p-0 px-1" style="float: right"><i class="bi bi-trash-fill"></i></button>
        </form>
      </a>
    </div>
  @endforeach
  </div>
</div>
@endsection