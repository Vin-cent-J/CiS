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
    <strong>Total: </strong>Rp. {{number_format($sale->total, 0, ',', '.')}} <br>
    <strong>Metode Pembayaran: </strong>Tunai <br>
    @if ($features->contains('id',15) || $sale->total_debt > 0)
    <strong>Hutang: </strong>Rp. {{ number_format($sale->total_debt, 0, ',', '.') }} <br>
    <button class="btn btn-warning" id="btn-hutang">Kurangi hutang</button>
    @endif

    <hr>
    <table class="table table-bordered">
      <thead class="table-light">
          <tr>
              <th>Produk</th>
              <th>Jumlah</th>
              <th>Harga</th>
              <th>Diskon</th>
              <th>Total</th>
              @if ($features->contains('id',4) && $features->contains('id',5) && (in_array(7, $activeDetails) || in_array(8, $activeDetails)))
              <th>Garansi & Pengembalian</th>
              @endif
          </tr>
      </thead>
      <tbody>
        @foreach ($sale->salesDetails as $detail)
        <tr>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->amount }}</td>
            <td>Rp.{{ number_format($detail->price, 0, ',', '.') }}</td>
            <td>
              @if ($detail->discounts_id == 1)
                Rp.
              @endif
              {{ number_format($detail->discount, 0, ',', '.') }}
              @if ($detail->discounts_id == 2)
              %
              @endif
            </td>
            <td>
              @if ($detail->discounts_id == 1)
              Rp.{{ number_format(($detail->price * $detail->amount) - $detail->discount, 0, ',', '.') }}
              @else
              Rp.{{ number_format(($detail->price * $detail->amount) - (($detail->price * $detail->amount) * ($detail->discount / 100)), 0, ',', '.') }}
              @endif
            </td>

            @if ($features->contains('id',4) || ($features->contains('id',5) && (in_array(7, $activeDetails) || in_array(8, $activeDetails))))
                <td>
                    @if ($features->contains('id',5) && (in_array(7, $activeDetails) || in_array(8, $activeDetails)))
                        <a type="button" class="btn btn-warning" href="">Pengembalian</a>
                    @endif
                    @if ($features->contains('id',4))
                        <a type="button" class="btn btn-warning" href="">Garansi</a>
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
      </tbody>
  </table>
  </div>
</div>
@endsection