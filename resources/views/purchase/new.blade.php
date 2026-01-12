@extends("layout.app")

@section('title', 'Pembelian | Tambah Baru')

@section('nav')
<nav class="px-3 bg-dark">
    <a class="btn btn-warning m-1" href="{{url('/purchase')}}"><i class="bi bi-arrow-return-left"></i></a>
</nav>
@endsection

@section("isi")
<div class="container card p-3" style="min-height: 82vh">
    <form action="{{url('/purchase')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <h5><strong>Supplier</strong></h5>
                <select class="form-select" name="supplier" id="supplier" style="width: 15rem" required>
                    @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" data-value="{{$supplier->address}}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @if (!$suppliers->isEmpty())
                <p class="p-2" id="alamat">
                    {{$suppliers->first()->address}}
                </p>
                @endif
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
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="list">
                    @foreach (session("purchase-products", []) as $item)
                    <tr>
                        <td>
                            <input type="text" class="form-control" value="{{ isset($item['product']) ? $item['product'] . ' - ' : '' }}{{ $item['name'] }}" readonly>
                            <input type="hidden" value="{{ $item['id'] }}">
                        </td>
                        <td> <input class="form-control qty" type="number" id="qty-{{$item['id']}}" data-value="{{$item['id']}}" value={{ $item['quantity'] }}></td>
                        <td>Rp. <input type="number" class="form-control price w-75" style="display: inline" id="price-{{$item['id']}}" data-value="{{$item['id']}}" value="{{$item['price']}}"></td>
                        <td>
                            <strong id="total-{{$item['id']}}">
                                Rp.{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </strong>
                        </td>
                        <td class="text-center"><a href=""><i class="bi bi-trash3-fill"></i></a></td>
                    </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>

        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-7">
                    <strong>Pengiriman: &nbsp;</strong> Rp. <input type="number" name="shipping_fee" class="w-25" value="0">
                </div>
                <div class="col-2 text-end" style="<?= $features->contains('id', 14) ? "" : "display: none" ?>">
                    Diskon:
                </div>
                <div class="col-3" style="<?= $features->contains('id', 14) ? "" : "display: none" ?>">
                    Rp. <input class="d-inline" id="globalDiscount" type="number"  name="discount" style="width: 60%">
                </div>
            </div>
            <div class="row align-items-center">
                @php
                    $total = 0;
                    foreach (session("purchase-products", []) as $item) {
                        $total += $item['price'] * $item['quantity'];
                    }
                @endphp
                <div class="col-7">
                    <p> 
                        <strong>Ditanggung:</strong>
                        <input type="radio" name="shipping" value="pembeli" checked> Pembeli &nbsp; 
                        <input type="radio" name="shipping" value="penjual"> Penjual
                    </p>
                </div>
                <div class="col-2 text-end">
                    <p class="fw-bold">Total:</p>
                </div>
                <div class="col-3">
                    @php
                        $cart = Session::get('purchase-products', []);
                        $grandTotal = 0;
                        foreach ($cart as $item) {
                            $grandTotal += ($item['price'] * $item['quantity']);
                        }
                    @endphp
                    <p class="fw-bold" id="total-o">Rp. {{ number_format($grandTotal, 0) }}</p>
                </div>
            </div>
            <div>
                <strong>Metode: </strong> 
                <select class="form-select" name="payment_method" id="metode" style="width: 8rem; display: inline;">
                    <option value="tunai">Tunai</option>
                    @if(in_array(24, $activeConfigs))
                    <option value="transfer">Transfer</option>
                    @endif
                    @if(in_array(25, $activeConfigs))
                    <option value="hutang">Hutang</option>
                    @endif
                </select>
            </div>
        </div>

        <div class="mt-3">
            <select name="" id="add-produk" class="form-select m-1" style="width: 12rem; display: inline;">
                @foreach ($products as $product)
                    @if($product->variants->count() > 0)
                        @foreach($product->variants as $variant)
                            @php
                                $vData = [
                                    'id' => $variant->id,
                                    'name' => $product->name . ' - ' . $variant->name,
                                    'price' => $variant->price,
                                    'type' => 'variant'
                                ];
                            @endphp
                            <option data-value='@json($vData)'>{{$product->name}} - {{$variant->name}}</option>
                        @endforeach
                    @else
                        @php
                            $pData = [
                                'id' => $product->id,
                                'name' => $product->name,
                                'price' => $product->price,
                                'type' => 'product'
                            ];
                        @endphp
                        <option data-value='@json($pData)'>{{$product->name}}</option>
                    @endif
                @endforeach
            </select>
            <a class="btn btn-warning" id="tambah">Tambah produk</a>
            <input class="btn btn-warning mx-2" type="submit" style="float: right" value="Simpan" <?= session('purchase-products') == [] ? 'disabled' : '' ?>>
        </div>
    </form>
</div>

@endsection

@section('js')
<script>
    let address = $('#supplier option:selected').data('value');
    $('#alamat').text(address);

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }

    $('#kustomer').change(function() {
        let address = $('#supplier option:selected').data('value');
        $('#alamat').text(address);
    });

    $('#tambah').click(function() {
        let product = $('#add-produk option:selected').data('value');
        $(this).prop('disabled', true).text('Menambahkan...');
        $.ajax({
            url: '/purchases/setSession',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            contentType: 'application/json',
            data: JSON.stringify({
                'id': product.id,
                'type': product.type,
                'name': product.name,
                'price': product.price,
                'quantity': 1
            }),
            success: function(data) {
                location.reload();
            },
            complete: function() {
                $(this).prop('disabled', false).text('+ Tambah');
            }
        });
    });

    $('.qty').on('keyup change', function(){
        const id = $(this).data('value')
        const qty = $(this).val()
        
        $.ajax({
            url: '/purchases/updateQty/',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            contentType: 'application/json',
            data: JSON.stringify({
                'id': id,
                'quantity': qty
            }),
            success: function(data){
                $('#total-'+id).text('Rp.' + formatCurrency($('#price-'+id).val() * qty));
                console.log(data);
            },
            fail: function(){
                alert('Gagal memperbarui jumlah');
            }
        })
    });

    $('.price').on('keyup change', function(){
        const id = $(this).data('value')
        const price = $(this).val()
        
        $.ajax({
            url: '/purchases/updatePrice/',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            contentType: 'application/json',
            data: JSON.stringify({
                'id': id,
                'price': price
            }),
            success: function(data){
                $('#total-'+id).text('Rp.' + formatCurrency(price * $('#qty-'+id).val()));
                reloadTotal($('#globalDiscount').val());
            },
            fail: function(){
                alert('Gagal memperbarui harga');
            }
        })
    });

    // $('.price, .qty').on('blur',function(){
    //     location.reload();
    // })

    $('#globalDiscount').on('keyup',function(){
        const discount = $(this).val();
        reloadTotal(discount);
    });

    function reloadTotal(discount = 0) {
        const cart = @json(session('purchase-products', []));
        let total = 0;
        for (const item of cart) {
            total += item.price * item.quantity
        }
        $('#total-o').text('Rp.' + formatCurrency(total - discount));
        console.log(total);
    }
</script>
@endsection