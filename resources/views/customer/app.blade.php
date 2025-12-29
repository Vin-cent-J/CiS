@extends('layout.app')

@section('title', 'Pelanggan')

@section('nav')
<div class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/customer/create')}}">
    <i class="bi bi-plus-lg"></i>Pelanggan 
  </a>
</div>
@endsection
@section('isi')
<div class="container card p-2" style="min-height: 80vh">
  <p><i class="bi bi-person-fill"></i>Customer:</p>
  <ul class="list-group list-group-flush">
    @foreach ($customers as $customer)
    @if (!$customer->deleted_at)
    <li class="list-group-item">
      <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash-fill"></i>
        </button>
      </form>
      <a href="{{url('/customer/'.$customer->id.'/edit')}}">
        <i class="bi bi-person-fill"> </i>{{$customer->name}}
      </a>
    </li>
    @endif
    @endforeach
  </ul>
</div>
@endsection