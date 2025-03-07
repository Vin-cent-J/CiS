@extends("layout.app")

@section("isi")
<div class="my-1 card" style="min-height: 100vh;">
  <div class="m-2 card">
    <div class="card-header">
      <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link @if($tab==1) active @endif" href="{{url('/settings/1')}}">Penjualan</a></li>
        <li class="nav-item"><a class="nav-link @if($tab==2) active @endif" href="{{url('/settings/2')}}">Retur & Pengembalian</a></li>
        <li class="nav-item"><a class="nav-link @if($tab==3) active @endif" href="{{url('/settings/3')}}">Manajemen Stok</a></li>
        <li class="nav-item ms-auto"><a type="button" class="btn-lg btn-warning text-decoration-none">Save</a></li>
      </ul>
    </div>
    @if ($tab==1)
    <div class="card-body penjualan">
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <label for="">Metode pembayaran</label>
          <div>
            <input class="mx-2" type="checkbox" name="metode" value="Tunai"> Tunai
            <input class="mx-2" type="checkbox" name="metode" value="Transfer"> Transfer
          </div>
        </li>
        <li class="list-group-item">
          <label for="">Harga</label>
          <div>
            <input class="form-check-input mx-2" type="radio" name="harga" checked> Termasuk Pajak
            <input class="form-check-input mx-2" type="radio" name="harga"> Tidak Termasuk Pajak
          </div>
        </li>
        <li class="list-group-item">
          <label for="">Diskon</label>
          <div>
            <ul class="list-group-flush">
              <li class="list-group-item">
                <label for="">Jenis Diskon</label>
                <div>
                  <input class="mx-2" name="jenisDiskon" type="checkbox"> Potongan Tunai
                  <input class="mx-2" name="jenisDiskon" type="checkbox"> Persen
                  <input class="mx-2" name="jenisDiskon" type="checkbox"> Bonus
                </div>
              </li>
              <li class="list-group-item">
                <label for="">Syarat Diskon</label>
                <div>
                  <input class="mx-2" name="syaratDiskon" type="checkbox"> Minimal Jumlah
                  <input class="mx-2" name="syaratDiskon" type="checkbox"> Jenis Barang
                  <input class="mx-2" name="syaratDiskon" type="checkbox"> Barang Tertentu
                </div>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
    @endif

    @if ($tab==2)
    <div class="card-body pengembalian">
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <label for="">Pengembalian</label>
          <div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <label for="">Lunas</label>
                <div>
                  <input class="mx-2" name="pengembalianLunas" type="checkbox"> Pengembalian Uang
                  <input class="mx-2" name="pengembalianLunas" type="checkbox"> Penggantian Barang
                </div>
              </li>
              <li class="list-group-item">
                <label for="">Hutang</label>
                <div>
                  <input class="mx-2" name="pengembalianHutang" type="checkbox"> Pengurangan Hutang
                  <input class="mx-2" name="pengembalianHutang" type="checkbox"> Penggantian Barang
                </div>
              </li>
            </ul>
          </div>
        </li>
        <li class="list-group-item">
          <label for="">Garansi:</label>
          <input type="checkbox">
        </li>
      </ul>
    </div>
    @endif

    @if ($tab==3)
    <div class="card-body manajemen">
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <label for="">Metode Pencatatan</label>
          <div>
            <input class="mx-2" name="pencatatan" type="checkbox"> Perpetual
            <input class="mx-2" name="pencatatan" type="checkbox"> Periodik
          </div>
        </li>
        <li class="list-group-item">
          <label for="">Metode HPP</label>
          <div>
            <input class="mx-2" name="metodeHpp" type="checkbox"> FIFO
            <input class="mx-2" name="metodeHpp" type="checkbox"> Average
          </div>
        </li>
      </ul>
    </div>
    @endif
  </div>
</div>
@endsection