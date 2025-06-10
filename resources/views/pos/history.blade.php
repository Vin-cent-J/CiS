@extends('layout.app')

@section('nav')
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/pos')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
</nav>
@endsection

@section("isi")
<div class="container card">
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Tanggal</th>
        <th>Total</th>
        <th>Metode Pembayaran</th>
        <th>Status</th>
        <th>Detail</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($sales as $sale)
      <tr>
        <td> {{$sale->id}} </td>
        <td> {{$sale->date}} </td>
        <td>Rp. {{number_format($sale->total)}} </td>
        <td> {{$sale->payment_methods}} </td>
        <td>
          @if ($sale->total_debt > 0)
          <span class="badge bg-danger">Belum Lunas</span>
          @else
          <span class="badge bg-success">Lunas</span>
          @endif
        </td>
        <td>
          <a type="button" class="btn btn-warning" href="{{url('/pos/'.$sale->id)}}">Detail</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection