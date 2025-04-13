@extends("layout.app")

@section("isi")
<div class="my-1 card" style="min-height: 100vh;">
  <div class="m-2 card">
    <div class="card-header">
      <ul class="nav nav-tabs">
        @foreach ($features as $feature)
          <li class="nav-item"><a class="nav-link @if($tab==$feature->route) active @endif" href="{{url('/settings/'.$feature->route)}}">{{$feature->name}}</a></li>
        @endforeach
      </ul>
    </div>
<!--
    @foreach ($features as $feature)
    @if ($tab==$feature->route)
    <div class="card-body">
      <ul class="list-group list-group-flush">
      @foreach ($feature->subFeatures as $item)
      <li class="list-group-item">
        <div>
          <label for="">{{$item->name}}</label>
          <input class="mx-2" name="pengembalianLunas" type="checkbox" @if($item->is_active == 1) checked @endif @if($item->mandatory) checked disabled @endif>
          <ul class="list-group list-group-flush">
            @foreach ($item->configurations as $config)
            <li class="list-group-item">
            <div>
            <label for="">{{$config->name}}</label>
            <input class="mx-2" name="pengembalianLunas" type="checkbox" @if($config->is_active == 1) checked @endif @if($config->mandatory) checked disabled @endif>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <div>
                    @foreach ($config->detailConfigurations as $detail)
                    <input class="mx-2" name="pengembalianLunas" type="checkbox" @if($detail->is_active == 1) checked @endif>
                    <label for="">{{$detail->name}}</label>
                    @endforeach
                  </div>
                </li>
              </ul>
            </div>
            </li>
            @endforeach
          </ul>
        </div>
      </li>
      @endforeach
      </ul>
    </div>    
    @endif
    @endforeach
  -->
  @foreach ($features as $feature)
  @if ($tab == $feature->route)
    <div class="card-body">
      <ul class="list-group list-group-flush">
        @foreach ($feature->subFeatures as $item)
          <li class="list-group-item">
            <div class="d-flex align-items-center mb-2">
              <label class="fw-bold me-2">{{ $item->name }}</label>
              <input type="checkbox"
                     name="pengembalianLunas"
                     class="form-check-input"
                     @if($item->is_active) checked @endif
                     @if($item->mandatory) checked disabled @endif>
            </div>
            @if ($item->configurations->count())
              <ul class="list-group list-group-flush ms-4">
                @foreach ($item->configurations as $config)
                  <li class="list-group-item">
                    <div class="d-flex align-items-center mb-2">
                      <label class="me-2">{{ $config->name }}</label>
                      <input type="checkbox"
                             name=""
                             class="form-check-input"
                             @if($config->is_active) checked @endif
                             @if($config->mandatory) checked disabled @endif>
                    </div>
                    @if ($config->detailConfigurations->count())
                      <ul class="list-group list-group-flush ms-4">
                        @foreach ($config->detailConfigurations as $detail)
                          <li class="list-group-item">
                            <div class="d-flex align-items-center">
                              <input type="checkbox"
                                     name="pengembalianLunas"
                                     class="form-check-input me-2"
                                     @if($detail->is_active) checked @endif>
                              <label>{{ $detail->name }}</label>
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    @endif
                  </li>
                @endforeach
              </ul>
            @endif
          </li>
        @endforeach
      </ul>
    </div>
  @endif
@endforeach
    @if ($tab==1)
    <div class="card-body penjualan">
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <label for="">Metode pembayaran</label>
          <div>
            <input class="mx-2" type="checkbox" name="metode" @if(1) checked @endif> Tunai
            <input class="mx-2" type="checkbox" name="metode" @if(1) checked @endif> Transfer
          </div>
        </li>
        <li class="list-group-item">
          <label for="">Harga</label>
          <div>
            <input class="form-check-input mx-2" type="radio" name="harga" checked> Termasuk Pajak
            <input class="form-check-input mx-2" type="radio" name="harga" @if(1) checked @endif> Tidak Termasuk Pajak
          </div>
        </li>
        <li class="list-group-item">
          <label for="">Diskon</label>
          <input class="mx-2" name="diskon" type="checkbox" @if(1) checked @endif> 
          <div>
            <ul class="list-group-flush">
              <li class="list-group-item">
                <label for="">Jenis Diskon</label>
                <div>
                  <input class="mx-2" name="jenisDiskon" type="checkbox" @if(1) checked @endif> Potongan Tunai
                  <input class="mx-2" name="jenisDiskon" type="checkbox" @if(1) checked @endif> Persen
                  <input class="mx-2" name="jenisDiskon" type="checkbox" @if(1) checked @endif> Bonus
                </div>
              </li>
              <li class="list-group-item">
                <label for="">Syarat Diskon</label>
                <div>
                  <input class="mx-2" name="syaratDiskon" type="checkbox" @if(1) checked @endif> Minimal Jumlah
                  <input class="mx-2" name="syaratDiskon" type="checkbox" @if(1) checked @endif> Jenis Barang
                  <input class="mx-2" name="syaratDiskon" type="checkbox" @if(1) checked @endif> Barang Tertentu
                </div>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
    @endif
  </div>
</div>
@endsection