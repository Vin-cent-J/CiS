@extends("layout.app")

@section('title', 'Penjualan | Buat Penjualan Baru')

@section('nav')
<nav class="px-3 bg-dark">
    <a type="button" class="btn btn-warning m-1" href="{{url('/sales')}}">
        <i class="bi bi-arrow-return-left"></i>
    </a>
</nav>
@endsection

@section("isi")
<div class="container card p-3" style="min-height: 82vh">
    <form action="{{url('/sales')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <h5><strong>Pelanggan</strong></h5>
                <select class="form-select" name="customer" id="customer" style="width: 15rem" required>
                    @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" data-value="{{$customer->address}}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                @if (!$customers->isEmpty())
                <p class="p-2" id="alamat">
                    {{$customers->first()->address}}
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
                        @if ($features->contains('id', 8))
                        <th>
                            Diskon
                        </th>
                        @endif
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="list">
                    @foreach (session("sale-products", []) as $item)
                    <tr>
                        <td>
                            <select class="form-select select-p">
                            @foreach ($products as $product)
                            @if (!in_array($product->id, session("added")) || ($product->id == $item['id']))
                                <option value="{{$product->id}}" <?= $product->id == $item['id'] ? 'selected' : '' ?>>
                                    {{$product->name}}
                                </option>
                            @endif
                            @endforeach
                            </select>
                        </td>
                        <td> <input class="form-control qty" type="number" id="qty-{{$item['id']}}" data-value="{{$item['id']}}" value="{{$item['quantity']}}"></td>
                        <td>Rp.{{number_format($item['price'],0, ',', '.')}}</td>
                        @if ($features->contains('id', 8))
                        <td>
                            @if ($item['discount_type'] == 1)
                                Rp.
                            @endif
                            <input class="form-control discount" style="width:60%; display: inline;" type="number" data-value="{{$item['id']}}" id="disc-{{$item['id']}}" value="{{$item['discount']}}">
                            @if ($item['discount_type'] == 2)
                                %
                            @endif
                            <select id="type-{{$item['id']}}" class="form-select" style="width: 7rem; display: inline;">
                                @if (in_array(10, $activeDetails))
                                <option value="1" <?= $product['discount_type'] == 1 ? 'selected' : ''; ?> >Tunai</option>
                                @endif
                                @if (in_array(11, $activeDetails))
                                <option value="2" <?= $product['discount_type'] == 2 ? 'selected': ''; ?> >%</option>
                                @endif
                            </select> 
                        </td>
                        @endif
                        <td>
                            <strong id="total-{{$item['id']}}">
                                @if ($item['discount_type'] == 1)
                                Rp.{{ number_format(($item['price'] * $item['quantity']) - $item['discount'], 0, ',', '.') }}
                                @else
                                Rp.{{ number_format(($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity']) * ($item['discount'] / 100)), 0, ',', '.') }}
                                @endif
                            </strong>
                        </td>
                        <td class="text-center"><a href=""><i class="bi bi-trash3-fill"></i></a></td>
                    </tr>
                    @endforeach
                    @if (in_array(21, $activeDetails))
                        
                    @endif
                    
                </tbody>
            </table>
        </div>

        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-7">
                    <strong>Pengiriman: &nbsp;</strong> Rp. <input type="number" name="shipping_fee" class="w-25" value="0">
                </div>
                @if (in_array(21, $activeDetails))
                <div class="col-2 text-end">
                    Diskon:
                </div>
                <div class="col-3">
                    Rp. <input class="d-inline" id="globalDiscount" type="number" name="discount" style="width: 60%">
                </div>
                @endif
            </div>
            <div class="row align-items-center">
                @php
                    $total = 0;
                    foreach (session("sale-products", []) as $item) {
                        if ($item['discount_type'] == 2) {
                            $total += ($item['price'] * $item['quantity']) * (1 - $item['discount'] / 100);
                        } else {
                            $total += ($item['price'] * $item['quantity']) - $item['discount'];
                        }
                    }
                @endphp
                <div class="col-7">
                    <p class="fw-bold">Ditanggung:
                        <input type="radio" name="shipping" value="pembeli" checked> Pembeli &nbsp; 
                        <input type="radio" name="shipping" value="penjual"> Penjual
                    </p>
                </div>
                <div class="col-2 text-end">
                    <p class="fw-bold">Total:</p>
                </div>
                <div class="col-3">
                    @php
                        $cart = Session::get('sale-products', []);
                        $grandTotal = 0;
                        foreach ($cart as $item) {
                            if ($item['discount_type'] == 2) {
                                $grandTotal += ($item['price'] * $item['quantity']) * (1 - $item['discount'] / 100);
                            } else {
                                $grandTotal += ($item['price'] * $item['quantity']) - $item['discount'];
                            }
                        }
                    @endphp
                    <p class="fw-bold" id="total-o">Rp. {{ number_format($grandTotal, 0) }}</p>
                </div>
            </div>

            <div>
                <strong>Metode Pembayaran: </strong> 
                <select class="form-select" name="payment_method" id="metode" style="width: 8rem; display: inline;">
                    <option value="tunai">Tunai</option>
                    @if(in_array(10, $activeConfigs))
                    <option value="transfer">Transfer</option>
                    @endif
                    @if(in_array(22, $activeConfigs))
                    <option value="piutang">Piutang</option>
                    @endif
                </select>
            </div>
        </div>

        <div class="mt-3">
            <select name="" id="add-produk" class="form-select m-1" style="width: 12rem; display: inline;">
                @foreach ($products as $product)
                    @if ($product->variants->count())
                    <optgroup label="{{ $product->name }}">
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}">
                                {{ $product->name }} - {{ $variant->name }}
                            </option>
                        @endforeach
                    </optgroup>
                    @else
                    <option data-value="{{$product}}">{{$product->name}}</option>
                    @endif
                @endforeach
            </select>
            <button class="btn btn-warning" id="tambah">Tambah produk</button>
            <input class="btn btn-warning mx-2" type="submit" style="float: right" value="Simpan" <?= session('sale-products') == [] ? 'disabled' : '' ?>>
        </div>
    </form>
</div>

@endsection

@section("js")
<script>
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }

    const products = @json($products);
    $(document).ready(function() {
        let address = $('#kustomer option:selected').data('value');
        $('#alamat').text(address);
    });

    $('#kustomer').change(function() {
        let address = $('#kustomer option:selected').data('value');
        $('#alamat').text(address);
    });

    $('#tambah').click(function() {
        let product = $('#add-produk option:selected').data('value');
        $(this).prop('disabled', true).text('Menambahkan...');
        $.ajax({
            url: '/sales/setSession',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            contentType: 'application/json',
            data: JSON.stringify({
                'id': product.id,
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

    var idBefore = 0;
    $('.select-p').on('focus', function(){
        idBefore = $(this).val();
    }).change(function(){
        $.ajax({
            url: '/sales/changeProduct',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            contentType: 'application/json',
            data: JSON.stringify({
                'productId': idBefore,
                'newId': $(this).val(),
                'quantity': $('#qty-' + idBefore).val(),
                'discount': $('#disc-'+ idBefore).val(),
                'discount_type': $('#type-'+ idBefore).val()
            }),
            success: function(data) {
                location.reload();
            }
            
        })
    })

    $('.qty').on('change', function(){
        const id = $(this).data('value')
        const qty = $(this).val()
        
        $.ajax({
            url: '/sales/updateQty/',
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
                location.reload();
            },
            fail: function(){
                alert('Gagal memperbarui jumlah');
            }
        })
    });

    $('#globalDiscount').on('keyup',function(){
        const discount = $(this).val();
        const cart = @json(session('sale-products', []));
        let total = 0;
        for (const item of cart) {
            if(item.discount_type == 2) {
                total += (item.price * item.quantity) * (1-item.discount/100);
            } else {
                total += (item.price * item.quantity) - item.discount;
            }
        }
        $('#total-o').text('Rp.' + formatCurrency(total - discount));
    });

    $('.discount').on('keyup',function(){
        const id = $(this).data('value')
        const discount = $(this).val()
        const type = $('#type-' + id).val()

        $.ajax({
            url: '/sales/updateDiscount/',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            contentType: 'application/json',
            data: JSON.stringify({
                productId: id,
                discount: discount,
                discount_type: type
            }),
            success: function(data){
                const product = data.product;
                const products = data.products;
                if (type == 1) {
                    $('#total-' + id).text('Rp.' + formatCurrency(parseInt(product.price) * parseInt(product.quantity) - product.discount));
                } else {
                    $('#total-' + id).text('Rp.' + formatCurrency(product.price * product.quantity * (1 - product.discount / 100)));
                }

                let total = 0;
                for (const item of products) {
                    if(item.discount_type == 2) {
                        total += (item.price * item.quantity) * (1-item.discount/100);
                    } else {
                        total += (item.price * item.quantity) - item.discount;
                    }
                }
                
                $('#total-o').text('Rp.' + formatCurrency(total));
            },
        })
    });

    $('.type').change(function(){
        const id = this.getAttribute('data-value');
        let discount = $("#discount-"+id).val();
        const type = this.value;

        if(type==2 & discount>100){
            discount = 100;
        }

        fetch('/sales/updateDiscount', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            productId: id,
            discount: discount,
            discount_type: type
        })
        })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
            const product = data.product;

            if(type == 1){
            $('#total-'+id).text('Rp.' + formatCurrency(parseInt(product.price) * parseInt(product.quantity) - product.discount ));
            } else {
            $('#total-'+id).text('Rp.' + formatCurrency(product.price * product.quantity * (1 - product.discount / 100)));
            }

            let total = 0;
            for (const item of data.products) {
                if(item.discount_type == 2) {
                    total += (item.price * item.quantity) * (1-item.discount/100);
                } else {
                    total += (item.price * item.quantity) - item.discount;
                }
            }
            
            $('#pembayaran #total-m').text('Rp.' + formatCurrency(total));
            $('#total').text('Rp.' + formatCurrency(total));
            
        } else {
            alert('Gagal memperbarui diskon: ' + data.message);
        }
        })
        .catch(error => {
        alert('Terjadi kesalahan. '+error);
        });
    });
</script>
@endsection