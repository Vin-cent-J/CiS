@extends('layout.app')

@section('title', 'Penjualan')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{ url('/sales/create') }}">
    + Penjualan
  </a>
  <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#syarat">
    Syarat Bonus
  </a>
</nav>
@endsection
@section("isi")
@php
  use Carbon\Carbon;
  $status = request('status', '');
  $startDate = request('start_date', Carbon::now()->subDays()->format('Y-m-d'));
  $endDate = request('end_date', date('Y-m-d'));

  if ($startDate) {
    try {
      $startDate = Carbon::parse($startDate)->format('Y-m-d');
    } catch (\Exception $e) {
      $startDate = Carbon::now()->subDays()->format('Y-m-d');
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

  <div class="modal fade" id="syarat" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Syarat Diskon</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <hr> 
          <div class="mb-3">
            <label class="form-label fw-bold">Berlaku Untuk Kategori</label>
            <select id="select-category" name="categories[]" class="form-control" multiple style="height: 200px;">
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }} (Min: {{ $category->discountRule->minimum ?? 0 }})</option>
              @endforeach
            </select>
          </div>
          <hr> 
          <div class="mb-3">
            <label class="form-label fw-bold">Berlaku Untuk Produk Spesifik</label>
            <select id="select-product" name="products[]" class="form-control" multiple style="height: 150px;">
              @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} (Min: {{ $product->discountRule->minimum ?? 0 }}) | 
                  @if (isset($product->discountRule->bonusProduct))
                  Bonus: {{ $product->discountRule->bonusProduct->name ?? ""}} (Min: {{ $product->discountRule->bonus_minimum ?? "" }})
                  @endif
                </option>
              @endforeach
            </select>
          </div>
          <small class="text-muted">Pilih Produk/Category (Ctrl/Command untuk multiple)</small>
        </div>
        @if (in_array(5, $activeConfigs))
        <hr>
        <div class="mb-3 p-2">
          <label for="bonus-min" class="form-label fw-bold text-warning-emphasis">Minimal pembelian untuk bonus:</label>
          <input type="number" id="bonus-min" name="bonus_quantity" min="1" value="1" class="form-control">
          <label class="form-label fw-bold text-warning-emphasis"> Hadiah Bonus (Opsional)</label>
          <select name="bonus_product_id" id="select-bonus" class="form-control">
            <option value="">-- Tidak Ada Bonus --</option>
            @foreach ($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
          </select>
          <small class="text-muted">Jika syarat terpenuhi, pelanggan dapat gratis barang ini.</small>
        </div>
        @endif
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btn-simpan">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script>
  $('#statusO, #DateO, #DateMaxO').change(function() {
    const status = $('#statusO').val();
    const startDate = $('#DateO').val();
    const endDate = $('#DateMaxO').val();

    window.location.replace(`/sales?status=${status}&start_date=${startDate}&end_date=${endDate}`);
  });

  $('#monthPicker').datepicker({
    format: "yyyy-mm",
    startView: "months", 
    minViewMode: "months",
    autoclose: true
  });

  $('#btn-simpan').click(function(e) {
    e.preventDefault();

    minimal = $('#input-minimal').val();
    category = $('#select-category').val();
    product = $('#select-product').val();
    bonusQuantity = 1;
    bonusProductId = $('#select-bonus').val();

    if ((!category || category.length === 0) && (!product || product.length === 0)) {
      alert("Pilih setidaknya satu kategori atau produk.");
      return;
    }

    saveBtn = $(this);
    saveBtn.text('Menyimpan...').prop('disabled', true);

    $.ajax({
      url: "/discounts/insertRule",
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      data: {
        categories: category,
        products: product,
        bonus_minimum: bonusMinimum,
        bonus_product_id: bonusProductId,
        bonus_quantity: bonusQuantity
      },
      success: function(response) {
        if(response.status === 'success') {
          alert(response.message);
          $('#syarat').modal('hide');
          location.reload(); 
        }
      },
      error: function(xhr) {
        alert('Gagal menyimpan data.');
        console.log(xhr.responseText);
      },
      complete: function() {
        saveBtn.text('Simpan').prop('disabled', false);
      }
    });
  });
</script>
@endsection