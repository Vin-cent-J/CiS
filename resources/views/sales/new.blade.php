@extends("layout.app")

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
                <h5><strong>Kustomer</strong></h5>
                <select class="form-select" name="kustomer" id="kustomer" style="width: 15rem">
                    @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" data-value="{{$customer->address}}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <p id="alamat">
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                @if ($features->contains('id',11))
                <p><strong>Total Hutang:</strong> Rp. <input type="number" name="debt" style="width: 15rem"> </p>
                <p><strong>Jangka Pembayaran:</strong> <input type="number" name="jangka" style="width: 4rem"> Hari</p>
                @endif
                <p>
                    <strong>Metode: </strong> 
                    <select class="form-select" name="payment_method" id="metode" style="width: 8rem; display: inline;">
                        @if (in_array(9, $activeConfigs))
                        <option value="1">Tunai</option>
                        @elseif (in_array(10, $activeConfigs))
                        <option value="2">Transfer</option>
                        @endif
                    </select>
                </p>
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
                        <td><select class="form-select select-p">
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
                            @if ($item['discount_id'] == 1)
                                Rp.
                            @endif
                            <input class="form-control discount" style="width:60%; display: inline;" type="number" data-value="{{$item['id']}}" id="disc-{{$item['id']}}" value="{{$item['discount']}}">
                            @if ($item['discount_id'] == 2)
                                %
                            @endif
                            <select id="type-{{$item['id']}}" class="form-select" style="width: 7rem; display: inline;">
                                @if (in_array(10, $activeDetails))
                                <option value="1">Tunai</option>
                                @elseif (in_array(11, $activeDetails))
                                <option value="2">Persen</option>
                                @endif
                            </select> 
                        </td>
                        @endif
                        <td>
                            <strong id="total-{{$item['id']}}">
                                @if ($item['discount_id'] == 1)
                                Rp.{{ number_format(($item['price'] * $item['quantity']) - $item['discount'], 0, ',', '.') }}
                                @else
                                Rp.{{ number_format(($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity']) * ($item['discount'] / 100)), 0, ',', '.') }}
                                @endif
                            </strong>
                        </td>
                        <td class="text-center"><a href=""><i class="bi bi-trash3-fill"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="float-end">
                <tr>
                    @php
                        $total = 0;
                        foreach (session("sale-products", []) as $item) {
                            if ($item['discount_id'] == 2) {
                                $total += ($item['price'] * $item['quantity']) * (1 - $item['discount'] / 100);
                            } else {
                                $total += ($item['price'] * $item['quantity']) - $item['discount'];
                            }
                        }
                    @endphp
                    <th>Total: </th>
                    <td id="total-o">Rp. {{$total}}</td>
                </tr>
            </table>
        </div>

        <div class="mt-3">
            <select name="" id="add-produk" class="form-select m-1" style="width: 12rem; display: inline;">
                @foreach ($products as $product)
                    <option data-value="{{$product}}">{{$product->name}}</option>
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
                'discount_id': $('#type-'+ idBefore).val()
            }),
            success: function(data) {
                location.reload();
            }
        })
    })

    $('.qty').keyup(function(){
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
            }
        })
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
                discount_id: type
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
                    if(item.discount_id == 2) {
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
        discount_id: type
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
          if(item.discount_id == 2) {
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