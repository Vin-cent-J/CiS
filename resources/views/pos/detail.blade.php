@extends('layout.app')

@section('nav')
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/pos/riwayat')}}">
    Riwayat
  </a>
</nav>
@endsection

@section('isi')
<div class="container card p-2">
  <div class="m-1">
    <strong>Nota: </strong>1 <br>
    <strong>Tanggal: </strong>12-03-2025 <br>
    <strong>Total: </strong>Rp. 1000 <br>
    <strong>Metode Pembayaran: </strong>Tunai <br>

    <hr>
    <table class="table table-bordered">
      <thead class="table-light">
          <tr>
              <th>Produk</th>
              <th>Jumlah</th>
              <th>Harga</th>
              <th>Diskon</th>
              <th>Total</th>
              <th>Aksi</th>
          </tr>
      </thead>
      <tbody>
          <tr>
              <td>Garden Lamp</td>
              <td>1.00</td>
              <td>Rp.1350</td>
              <td>Rp. 0</td>
              <td>Rp.1350</td>
              <td>
                @if ($features->contains('id',5) && (in_array(7, $activeDetails) || in_array(8, $activeDetails)))
                <a type="button" class="btn btn-warning" href="">Pengembalian</a>
                @endif
                @if ($features->contains('id',4))
                <a type="button" class="btn btn-warning" href="">Garansi</a>
                @endif
              </td>
          </tr>
      </tbody>
  </table>
  </div>
</div>
@endsection