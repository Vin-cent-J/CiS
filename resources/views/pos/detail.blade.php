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
    <strong>Nota: </strong>{{$sale->id}} <br>
    <strong>Tanggal: </strong>{{$sale->date}} <br>
    <strong>Metode Pembayaran: </strong>{{$sale->payment_methods}} <br>
    @if (in_array('21', $activeConfigs) || $sale->total_debt > 0)
    <strong>Piutang: </strong> <span id="hutang">Rp. {{ number_format($sale->total_debt, 0, ',', '.') }}</span> <br>
    @if($sale->total_debt > 0)

    <button class="btn btn-warning" id="btn-hutang">Kurangi piutang</button>

    {{-- Container Hutang --}}
    <div class="p-2 bg-light fadein" style="display: none;">
      <div id="form-hutang" >
        <div class="mb-3">
          <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
          <input type="number" class="form-control" id="jumlahBayar" name="jumlahBayar" required>
        </div>
        <button class="btn btn-warning" id="btnBayar">Bayar</button>
      </div>
    </div>
    @endif
    @endif
    
    {{-- Detail --}}
    <hr>
    <table class="table table-bordered">
      <thead class="table-light">
          <tr>
              <th class="col-2">Produk</th>
              <th class="col-2">Jumlah</th>
              <th class="col-2">Harga</th>
              <th class="col-2">Diskon</th>
              <th class="col-2">Total</th>
              @if ($features->contains('id',5))
              <th class="col-1">Pengembalian</th>
              @endif
          </tr>
      </thead>
      <tbody>
        @foreach ($sale->salesDetails as $detail)
          <tr>
            <td>{{ $detail->product->name }}</td>
            <td>
              {{ $detail->amount }}  
              @if ($detail->return_amount > 0)
              <span class="fw-light">(Dikembalikan: {{+$detail->return_amount}})</span>
              @endif
            </td>
            <td>Rp.{{ number_format($detail->price, 0, ',', '.') }}</td>
            <td>
              @if ($detail->discount_type == 1)
                Rp.
              @endif
              {{ number_format($detail->discount, 0, ',', '.') }}
              @if ($detail->discount_type == 2)
              %
              @endif
            </td>
            <td>
              @if ($detail->discount_type == 1)
              Rp.{{ number_format((($detail->price * ($detail->amount - $detail->return_amount)) - $detail->discount), 0, ',', '.') }}
              @else
              Rp.{{ number_format(($detail->price * ($detail->amount - $detail->return_amount)) - (($detail->price * ($detail->amount - $detail->return_amount)) * ($detail->discount / 100)), 0, ',', '.') }}
              @endif
            </td>
            @if ($features->contains('id',5))
              <td>
                <a class="btn-pengembalian btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#pengembalian" data-value="{{ $detail }}" <?= ($detail->amount - $detail->return_amount == 0) ? 'disabled' : '' ?>>Pengembalian</a>
              </td>
            @endif
          </tr>
        @endforeach
    @if ($sale->discount)
      <tr>
        <td>Discount</td>
        <td>-</td>
        <td>Rp.{{number_format($sale->discount, 0, ',', '.')}}</td>
        <td>-</td>
        <td>-</td>
        @if ($features->contains('id',5))
        <td>-</td>
        @endif
      </tr>
    @endif
    </tbody>
  </table>
  <table style="width: 25%; float: right;">
    <tr>
      <th>Total Akhir</th>
      <th>Rp.{{number_format($sale->total, 0, ',', '.')}}</th>
    </tr>
  </table>
  </div>
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
          @if (in_array(7, $activeDetails))
          <input type="submit" value="Kembalikan uang" class="btn btn-sm btn-warning btn-kembalian"></input>
          @endif
          @if (in_array(6, $activeDetails) || in_array(9, $activeDetails))
          <input type="submit" value="Ganti barang" class="btn btn-sm btn-warning btn-kembalian"></input>
          @endif
          @if (in_array(8, $activeDetails) && $detail->total_debt > 0)
          <input type="submit" value="Kurangi hutang" class="btn btn-sm btn-warning btn-kembalian"></input>
          @endif
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
    const totalDebt = parseInt({{ $sale->total_debt }});

    if (isNaN(jumlahBayar) || jumlahBayar <= 0) {
      alert("Pembayaran tidak benar.");
      return;
    }

    if (jumlahBayar > totalDebt) {
      alert("Pembayaran melebihi total piutang.");
      return;
    }

    $.ajax({
      url: "{{ url('/pos/updateDebt') }}",
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      data: JSON.stringify({
        id: {{ $sale->id }},
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
    const saleId = {{ $sale->id }};

    $.ajax({
      url: "{{ url('/pos/return') }}",
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      data: JSON.stringify({
        sale_id: saleId,
        product_id: productId,
        amount: jumlah,
        type: type,
      }),
      success: function(data) {
        alert(data);
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