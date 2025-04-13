@extends('layout.app')

@section('nav')
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{route('pos')}}">
    Penjualan
  </a>
</nav>
@endsection

@section('isi')
<div class="container card">
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Tanggal</th>
        <th>Total</th>
        <th>Metode Pembayaran</th>
        <th>Detail</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>12-03-2025</td>
        <td>Rp. 1000</td>
        <td>Tunai</td>
        <td><a type="button" class="btn btn-warning" href="{{route('posdetail', ['id'=>1])}}">Detail</a></td>
      </tr>
    </tbody>
  </table>
</div>
@endsection