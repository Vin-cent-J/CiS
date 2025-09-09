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

    {{-- Field Hutang --}}
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
              @if ($detail->discount_type == 1)
                Rp.
              @endif
              {{ number_format($detail->discount, 0, ',', '.') }}
              @if ($detail->discount_type == 2)
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
            @if ($features->contains('id',4) && $features->contains('id',5) && (in_array(7, $activeDetails) || in_array(8, $activeDetails)))
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
    @if ($sale->discount)
      <tr>
        <td>Discount</td>
        <td>-</td>
        <td>Rp.{{number_format($sale->discount, 0, ',', '.')}}</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
      </tr>
    @endif
    </tbody>
  </table>
  <table style="width: 25%; float: right;">
    <tr>
      <th>Total Akhir</th>
      <th>Rp.{{number_format($sale->total - $sale->discount, 0, ',', '.')}}</th>
    </tr>
  </table>
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
</script>
@endsection