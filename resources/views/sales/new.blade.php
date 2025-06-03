@extends("layout.app")

@section('nav')
<nav class="px-3 bg-dark">
    <a type="button" class="btn btn-warning m-1" href="{{url('/sales')}}"><i class="bi bi-arrow-bar-left"></i>Penjualan</a>
</nav>
@endsection

@section("isi")
<div class="container card p-3" style="min-height: 82vh">
    <div class="row">
        <div class="col-md-6">
            <h5>Kustomer</h5>
            <p><strong>Deco Addict</strong><br>
                77 Santa Barbara Rd<br>
                Pleasant Hill CA 94523<br>
                United States - US12345673
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <p><strong>Tanggal Order:</strong> 09/10/2023 19:10:12</p>
            <p><strong>Total Hutang:</strong> Rp. 0</p>
            <p><strong>Jangka Pembayaran:</strong> 30 Hari</p>
        </div>
    </div>

    <hr>

    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Unit</th>
                    <th>Diskon %</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Lampu Taman</td>
                    <td>1.00</td>
                    <td>Rp.135000</td>
                    <td>0.00</td>
                    <td>Rp.135000</td>
                    <td class="text-center"><a href=""><i class="bi bi-trash3-fill"></i></a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <button class="btn btn-warning">Tambah produk</button>
        <button class="btn btn-warning">Simpan</button>
    </div>
</div>

@endsection