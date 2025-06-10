@extends("layout.app")

@section('nav')
<nav class="px-3 bg-dark">
    <a type="button" class="btn btn-warning m-1" href="{{url('/sales')}}"><i class="bi bi-arrow-return-left"></i></a>
</nav>
@endsection

@section("isi")
<div class="container card p-3" style="min-height: 85vh">
    <div class="row">
        <div class="col-md-6">
            <h5>Kustomer</h5>
            <p><strong>{{$sale->customer->name}}</strong><br>
                {{$sale->customer->address}}<br>
                {{$sale->customer->phone_number}}
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <p><strong>Tanggal Order:</strong> {{$sale->date}} </p>
            <p><strong>Total Hutang:</strong> Rp.{{$sale->total_debt}} </p>
            <p><strong>Jangka Pembayaran:</strong> 30 Hari</p>
        </div>
    </div>

    <hr>
    @if ($features->contains('id',11) && $sale->total_debt > 0)
    <div class="text-end">
        <p>Kurangi Hutang: <input type="number" name="r_debt" style="width: 15%" value="0"></p>
        <a type="button" class="btn btn-warning">Simpan</a>
    </div>
    @endif

    <hr>
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Unit</th>
                    <th>Diskon</th>
                    <th>Total</th>
                    @if ($features->contains('id',9) || $features->contains('id',10))
                    <th>Garansi & Pengembalian</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->salesDetails as $detail)
                <tr>
                    <td>{{$detail->product->name}}</td>
                    <td>{{$detail->amount}}</td>
                    <td>Rp.{{ number_format($detail->price, 0, ',', '.') }}</td>
                    @if ($features->contains('id',8) || $detail->discount > 0)
                    <td>
                    @if ($detail->discounts_id == 1)
                        Rp.
                    @endif
                    {{ number_format($detail->discount, 0, ',', '.') }}
                    @if ($detail->discounts_id == 2)
                    %
                    @endif
                    </td>
                    @else
                    <td>-</td>
                    @endif
                    <td>
                    @if ($detail->discounts_id == 1)
                    Rp.{{ number_format(($detail->price * $detail->amount) - $detail->discount, 0, ',', '.') }}
                    @else
                    Rp.{{ number_format(($detail->price * $detail->amount) - (($detail->price * $detail->amount) * ($detail->discount / 100)), 0, ',', '.') }}
                    @endif
                    </td>
                    @if ($features->contains('id',9) || $features->contains('id',10))
                    <td>
                        @if ($features->contains('id',10))
                        <a type="button" class="btn btn-warning" href="">Pengembalian</a>
                        @endif
                        @if ($features->contains('id',9))
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