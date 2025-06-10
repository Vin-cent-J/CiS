@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{ url('/sales/create') }}">
    + Penjualan
  </a>
</nav>
@endsection
@section("isi")
<div class="p-3 container card bg-white" style="min-height: 840px;">
  <table class="table">
    <thead>
      <t>
        <th scope="col">#</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Kustomer</th>
        <th scope="col">Total</th>
        <th scope="col">Status</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($sales as $sale)
      <tr>
        <th scope="row">{{$sale->id}}</th>
        <td>{{$sale->date}}</td>
        <td>{{$sale->customer->name}}</td>
        <td>Rp. {{number_format($sale->total, 0, '.')}}</td>
        <td>
          @if ($sale->total_debt > 0)
          <span class="badge bg-danger">Belum Lunas</span>
          @else
          <span class="badge bg-success">Lunas</span>
          @endif
        </td>
        <td><a class="btn btn-warning" href="{{url('sales/'.$sale->id)}}">Detail</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection