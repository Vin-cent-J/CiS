@extends("layout.app")

@section("nav")
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/inventory')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
  <a type="button" class="btn btn-warning" href="{{url('/category/create')}}">
    <i class="bi bi-plus-lg"></i> Kategori
  </a>
</div>
@endsection

@section("isi")
<div class="container card" style="min-height: 25vh;">
  <Strong><i class="bi bi-box-seam-fill"></i>Kategori:</Strong>
  <table class="table table-striped">
    <tr>
      <th>Nama</th>
      <th>Aksi</th>
    </tr>
    @foreach ($categories as $category )
    <tr>
      <form action="{{ route('category.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah yakin ingin menghapus {{$category->name}}?Are you sure you want to delete this category?');">
        @csrf
        @method('DELETE')
      <td>{{$category->name}}</td>
      <td> <a href="{{url('category/'.$category->id.'/edit')}}" class="link">Ubah</a> 
        <button type="submit" class="btn btn-link text-danger py-0">Hapus</button>  
      </td>
      </form>
    </tr> 
    @endforeach
  </table>
</div>
@endsection