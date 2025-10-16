@extends('layout.app')

@section('nav')
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/supplier/create')}}">
    <i class="bi bi-plus-lg"></i>Supplier
  </a>
</div>
@endsection
@section('isi')
<div class="container card p-2" style="min-height: 80vh">
  <p><i class="bi bi-person-fill"></i>Supplier:</p>
  <ul class="list-group list-group-flush">
    @foreach ($suppliers as $supplier)
    <li class="list-group-item">
      <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash-fill"></i>
        </button>
      </form>
      <a href="{{ url("/supplier/$supplier->id/edit") }}">
        <i class="bi bi-truck"></i>{{ $supplier->name }}
      </a>
    </li>
    @endforeach
  </ul>
</div>
@endsection