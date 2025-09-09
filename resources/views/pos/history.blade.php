@extends('layout.app')

@section('nav')
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/pos')}}">
    <i class="bi bi-arrow-return-left"></i>
  </a>
</nav>
@endsection

@section("isi")
@php
  use Carbon\Carbon;
  $status = request('status', '');
  $startDate = request('start_date', date('Y-m-d', strtotime('-30 days')));
  $endDate = request('end_date', date('Y-m-d'));

  if ($startDate) {
    try {
      $startDate = Carbon::parse($startDate)->format('Y-m-d');
    } catch (\Exception $e) {
      $startDate = Carbon::now()->format('Y-m-d');
    }
  }

  if ($endDate) {
    try {
      $endDate = Carbon::parse($endDate)->format('Y-m-d');
    } catch (\Exception $e) {
      $endDate = Carbon::now()->format('Y-m-d');
    }
  }
@endphp
<div class="container">
  <label for="statusO" class="form-label">Status:</label>
  <select class="form-select m-1" id="statusO" style="width: 10rem; display: inline;">
    <option value="">Semua</option>
    <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Lunas</option>
    <option value="belum" {{ $status == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
  </select>

  <label for="DateO">Rentang hari:</label>
  <input type="date" id="DateO" value="{{ $startDate }}"> - <input type="date" id="DateMaxO" value="{{ $endDate }}"> 
</div>
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
        <td>Rp. {{number_format(($sale->total - $sale->discount))}} </td>
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

@section('js')
<script>
  $('#statusO, #DateO, #DateMaxO').change(function() {
    const status = $('#statusO').val();
    const startDate = $('#DateO').val();
    const endDate = $('#DateMaxO').val();

    window.location.replace(`/pos/riwayat?status=${status}&start_date=${startDate}&end_date=${endDate}`);
  });
</script>
@endsection