@extends("layout.app")

@section('title', 'Pembelian | Detail')

@section('nav')
<nav class="px-3 bg-dark">
    <a type="button" class="btn btn-warning m-1" href="{{url('/purchase')}}"><i class="bi bi-arrow-return-left"></i></a>
</nav>
@endsection

@section("isi")
<div class="container card p-3" style="min-height: 85vh">
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="max-width: 400px;">
              <div class="card-body">
                <h5 class="fw-bold text-primary">Supplier</h5>
                <p><strong>{{$purchase->supplier->name}}</strong><br>
                    {{$purchase->supplier->address}}<br>
                    {{$purchase->supplier->phone_number}}
                </p>
                <h6 class="fw-bold text-primary mb-3">📦 Detail Pengiriman</h6>
                <div class="d-flex mb-1">
                  <span class="text-muted">Ditanggung:</span>
                  <span class="fw-semibold text-dark">{{ ucfirst($purchase->shipping) }}</span>
                </div>
                <div class="d-flex">
                  <span class="text-muted">Ongkos Kirim:</span>
                  <span class="fw-semibold">Rp{{ number_format($purchase->shipping_fee, 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end">
            <p class="fw-bold"><strong>Tanggal Order:</strong> {{$purchase->date}} </p>
            <p class="fw-bold"><strong>Total Hutang:</strong> Rp.{{$purchase->total_debt}} </p>
        </div>
    </div>

    
    @if ($features->contains('id',11) && $purchase->total_debt > 0)
    <hr>
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
                    <th>Total</th>
                    @if ($features->contains('id',9) || $features->contains('id',10))
                    <th>Garansi & Pengembalian</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->purchaseDetails as $detail)
                <tr>
                    <td>{{$detail->products->name}}</td>
                    <td>{{$detail->amount}}</td>
                    <td>Rp.{{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>
                        {{ number_format(($detail->price * $detail->amount) - $detail->discount, 0, ',', '.') }}
                    </td>
                    @if ($features->contains('id',9) || $features->contains('id',10))
                    <td>
                        <button class="btn-pengembalian btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#pengembalian" data-value="{{ $detail }}" <?= ($detail->amount - $detail->total_return == 0) ? 'disabled' : '' ?>>Pengembalian</button>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="container card">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
            <h4 class="text-primary fw-bold">Pengembalian</h4>
            </li>
            @foreach ($returns as $return)
            @if ($return->amount > 0)
            <li class="list-group-item fw-bold">
            {{ $return->product->name }}: {{ $return->amount }}  <span class="fw-light">({{ $return->type }})</span> <span style="float: right">{{$return->date}}</span>
            </li>
            @endif
            @endforeach
        </ul>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="pengembalian" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Pengembalian</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
              <div class="modal-body">
                <div class="mb-3">
                  <p class="fw-bold" id="pJumlah"></p>
                  <label for="jumlah" id="labelJumlah" class="form-label">Jumlah Pengembalian</label>
                  <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" pattern="[0-9]" required>
              </div>
              <div class="modal-footer">
                @if (in_array(16, $activeDetails) && $purchase->total_debt == 0)
                <input type="submit" value="Kembalikan uang" class="btn btn-sm btn-warning btn-kembalian"></input>
                @endif
                @if (in_array(15, $activeDetails) || in_array(18, $activeDetails))
                <input type="submit" value="Ganti barang" class="btn btn-sm btn-warning btn-kembalian"></input>
                @endif
                @if (in_array(17, $activeDetails ) && $purchase->total_debt > 0)
                <input type="submit" value="Kurangi piutang" class="btn btn-sm btn-warning btn-kembalian"></input>
                @endif
              </div>
          </div>
        </div>
      </div>
</div>
@endsection

@section('js')
<script>
  $("#btn-hutang").click(function(){
    const box = $(".fadein");
    if (box.is(":visible")) {
      box.animate({ opacity: 0 }, 350, function() {
        box.slideUp(300);
      });
    } else {
      box.slideDown(300).css("opacity", 0).animate({ opacity: 1 }, 350);
    }
  });

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID').format(amount);
  }

  $("#btnBayar").click(function(){
    const jumlahBayar = parseInt($("#jumlahBayar").val());
    const totalDebt = parseInt({{ $purchase->total_debt }});

    if (isNaN(jumlahBayar) || jumlahBayar <= 0) {
      alert("Pembayaran tidak benar.");
      return;
    }

    if (jumlahBayar > totalDebt) {
      alert("Pembayaran melebihi total piutang.");
      return;
    }

    $.ajax({
      url: "{{ url('/purchases/updateDebt') }}",
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      data: JSON.stringify({
        id: {{ $purchase->id }},
        paid: jumlahBayar,
      }),
      success: function(data) {
        $("#hutang").text("Rp. " + formatCurrency(data.debt));
        if (data.debt === 0) {
          location.reload();
        }
      },
      error: function(xhr, status, error) {
        alert("Terjadi kesalahan saat memproses pembayaran piutang.");
      }
    });
  });

  var detailBarang;

  $('.btn-pengembalian').click(function() {
    const detail = $(this).data('value');
    detailBarang = detail;
    $('#jumlah').attr('max', detail.amount);
    $('#pJumlah').text(detail.product.name);
    $('#labelJumlah').text('Jumlah Pengembalian (Maks: ' + detail.amount + ')');
  })

  $('.btn-kembalian').click(function() {
    const productId = detailBarang.products_id;
    const jumlah = $('#jumlah').val() 
    const type = $(this).val();
    const purchaseId = {{ $purchase->id }};

    $.ajax({
      url: "{{ url('/purchases/return') }}",
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      data: JSON.stringify({
        purchase_id: purchaseId,
        product_id: productId,
        amount: jumlah,
        type: type,
      }),
      success: function(data) {
        location.reload();
      },
      error: function(xhr, status, error) {
        alert("Terjadi kesalahan saat membuat pengembalian.");
      }
    });
  });
</script>

<script>
  $(function() {
    const quantityInput = $('#jumlah');

    quantityInput.on('invalid', function() {
      if (this.validity.rangeOverflow) {
        const max = $(this).attr('max');
        this.setCustomValidity(`Pengembalian melebihi pembelian, ${max} barang.`);
      } else if (this.validity.rangeUnderflow) {
        const min = $(this).attr('min');
        this.setCustomValidity(`Jumlah minimum pengembalian adalah ${min}.`);
      } else if (event.target.validity.valueMissing) {
        this.setCustomValidity('Masukkan jumlah barang yang ingin dikembalikan.');
      }
    });
  });
  
</script>
@endsection