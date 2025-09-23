@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/pos/riwayat')}}">
    riwayat
  </a>
</nav>
@endsection
@section("isi")
<div class="container-fluid card" style="min-height: 84vh;">
  <div class="row flex-grow-1">
    <div class="col-3 p-2 card">
      <h5><strong><i class="bi bi-cart"></i>Keranjang:</strong></h5>
      <div id="keranjang-products" class="mb-2">
        @if (session('products', []) == [])
          <div class="text-muted text-center">Keranjang masih kosong</div>
        @else
        @foreach ( session('products', []) as $product)
        <div class="row p-1 bg-light card" id="product-{{$product['id']}}">
          <div class="col d-flex justify-content-between">
            <div>
              <strong> {{$product['name']}} </strong>
            </div>
            <div>
              <strong id="total-{{$product['id']}}">
                @if ($product['discount_type'] == 1)
                Rp.{{number_format($product['price'] * $product['quantity'] - $product['discount'])}}
                @else
                Rp.{{number_format($product['price'] * $product['quantity'] * (1-$product['discount']/100))}} 
                @endif
                
              </strong>
            </div>
          </div>
          <div class="text-muted">
            <strong class="qty-{{$product['id']}}">{{ $product['quantity'] }}</strong> <small>x Rp.{{number_format($product['price'])}} / Unit</small>
          </div>
          <div class="text-gray-600 flex justify-between items-center mt-1">
            @if (in_array(20, $activeDetails) && $features->contains('id',3))
            <p class="text-sm">
            Diskon: <input type="number" class="w-16 p-1 border border-gray-300 rounded-md text-sm discount" data-value="{{$product['id']}}" id="discount-{{$product['id']}}" min="0" value="{{$product['discount']}}">
              <select name="discount_type" class="type-{{$product['id']}} type" data-value="{{$product['id']}}">
                @if (in_array(1, $activeDetails))
                <option value="1" <?= $product['discount_type'] == 1 ? 'selected' : ''; ?> >Tunai</option>
                @endif
                @if (in_array(2, $activeDetails))
                <option value="2" <?= $product['discount_type'] == 2 ? 'selected': ''; ?> >%</option>
                @endif
              </select>
            </p>
            @endif
            <a class="btn btn-danger text-red-500 hover:text-red-700 focus:outline-none float-right deleteButton" data-value="{{$product['id']}}">
              <i class="bi bi-trash"></i> 
            </a>
          </div>
        </div>
        @endforeach
        @endif
      </div>
      
      @php
      $total = 0;
      foreach (session('products', []) as $product) {
        $total += $product['price'] * $product['quantity'];
        echo "<script>console.log(".json_encode($product).")</script>";
        if($product['discount_type'] == 2) {
          $diskon = ($product['price'] * $product['quantity']) * ($product['discount'] / 100);
          $total -= $diskon - session('saleTotalDisc', 0);
        } else{
          $total -= $product['discount'];
        }
      }
      @endphp
      <div class="row w-100 position-absolute bottom-0 p-1">
        @if ($features->contains('id',3))
        <div class="diskontotal">
          @if (in_array(19, $activeDetails) && $features->contains('id',3))
          <div class="m-1">
            Diskon:
            <input id="totalDisc" type="number" class="form-control totalDisc" id="diskon-nota">
          </div>
          @endif
          <div class="text-end">
            Total: <strong id="total"> Rp.{{ number_format($total)}}</strong>
            <hr>
          </div>
        </div>
        @endif
        <div class="m-2 text-end">
          <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#pembayaran" <?= session('products', []) == [] ? 'disabled' : '' ?>>
            Bayar
          </button>
        </div>
      </div>
    </div>

    <div class="col-8 bg-light p-2">
      <!-- Todo: Filter barang -->
      <Strong><i class="bi bi-box-seam-fill"></i>Katalog:</Strong>
      <div class="row p-2">
        @foreach ($products as $product)
        @php
        $p = [
          "name" => $product->name, "id" => "$product->id", "price" => "$product->price", "quantity" => 1
        ];
        @endphp
        <div class="col-2 p-2 m-1 card" style="height: 18rem;">
          <img src="/storage/{{$product->photo}}" style="max-height: 60%;">
          <div class="p-2">
            <div>
              <strong>{{$product->name}}</strong>
            </div>
            <div class="text-muted">
              <small>Rp. {{number_format($product->price)}} </small>
            </div>
            
            <button id="addToCart" class="btn-sm btn-primary addToCart" style="float: right;" data-value='@json($p)' >
              + Tambah
            </button>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="pembayaran" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('pos.store')}}" method="post">
        <div class="modal-body">
          @csrf
          <label for="cust">Kustomer:</label>
          <select name="customers_id" id="cust">
            <option value="">Pilih Kustomer</option>
            @foreach ($customers as $customer)
            <option value="{{$customer->id}}">{{$customer->name}}</option>
            @endforeach
          </select>
          <p>Total: <strong id="total-m">Rp. {{$total}} </strong></p>
          <label for="metode">Metode Pembayaran:</label>
          <select class="form-select" name="payment_method" id="metode">
            <option value="tunai">Tunai</option>
            @if(in_array(2, $activeConfigs))
            <option value="transfer">Transfer</option>
            @endif
            @if(in_array(21, $activeConfigs))
            <option value="piutang">Piutang</option>
            @endif
          </select>
        </div>
        <div class="modal-footer">
          <input type="submit" value="Bayar" class="btn btn-warning"></input>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section("js")
<script>
  let features = @json($features);
  let activeConfigs = {{json_encode($activeDetails)}};
  const productList = $('#keranjang-products');

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID').format(amount);
  }

  function createProductElement(product) {
    const quantity = Number(product.quantity) || 1;
    const price = Number(product.price) || 0;
    const totalPrice = price * quantity;

    let discountSectionHtml = '';

    if (activeConfigs.includes(1) && features.some(feature => feature.id === 3)) {
      discountSectionHtml =
      `<div class="text-gray-600 flex justify-between items-center mt-1">
        <p class="text-sm">Diskon: <input type="number" class="w-16 p-1 border border-gray-300 rounded-md text-sm discount" data-value="${product.id}" min="0" max="100">%</p>
        <a class="btn btn-danger text-red-500 hover:text-red-700 focus:outline-none" data-product-id="${product.id}">
          <i class="bi bi-trash m-0"></i> </a>
      </div>`;
    } else {
      discountSectionHtml =
      `<div class="text-gray-600 flex justify-end items-center mt-1">
        <a class="btn btn-danger py-0 text-red-500 hover:text-red-700 focus:outline-none" data-product-id="${product.id}">
          <i class="bi bi-trash m-0"></i> </a>
      </div>`;
    }
 
    const productHtml =
    `<div class="row p-1 bg-light card" id="product-${product.id}">
      <div class="col d-flex justify-content-between">
        <div>
          <strong>${product.name}</strong>
        </div>
        <div>
          <strong class="total-${product.id}">Rp.${formatCurrency(totalPrice)}</strong>
        </div>
      </div>
      <div class="text-muted">
        <strong class="qty-${product.id}">${quantity}</strong> <small>x Rp. ${formatCurrency(price)} / Unit</small>
      </div>
      ${discountSectionHtml}
    </div>`;
    return $(productHtml);
  }

  $('.addToCart').click(function() {
    const productData = JSON.parse(this.getAttribute('data-value'));
    const type = $('.type-' + productData.id).val();

    if (!productData || typeof productData.id === 'undefined') {
      console.error('Product data is missing or invalid.');
      return;
    }

    $(this).prop('disabled', true).text('Menambahkan...');

    $.ajax({
      url: '/pos/setSession',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      contentType: 'application/json',
      data: JSON.stringify({
        'id': productData.id,
        'name': productData.name,
        'price': productData.price,
        'quantity': productData.quantity,
      }),
      success: function(data) {
        const updatedCartItems = data.products;
        if (productList && productList.length) {
          productList.empty();
          if (updatedCartItems.length === 0) {
            productList.append('<br><div class="text-muted text-center">Keranjang masih kosong</div>');
          } else {
            location.reload();
          }
        }
      },
      complete: function() {
        $(this).prop('disabled', false).text('+ Tambah');
      }
    });
  });

  $('.totalDisc').on('keyup', function() {
    const totalDisc = this.value;

    $.ajax({
      url: '/pos/setSaleTotalDisc',
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      contentType: 'application/json',
      data: JSON.stringify({
        saleTotalDisc: totalDisc
      }),
      success: function(data) {
        const products = data.products;
        let total = 0;
        for (const item of products) {
          if(item.discount_type == 2) {
            total += (item.price * item.quantity) * (1-item.discount/100);
          } else {
            total += (item.price * item.quantity) - item.discount;
          }
        }
        totalF = total - totalDisc;
        if(totalF < 0){
          totalF = 0;
        }
        $('#pembayaran #total-m').text('Rp.' + formatCurrency(totalF));
        $('#total').text('Rp.' + formatCurrency(totalF));
      },
    })
  });

  $('.discount').on('keyup', function() {
    const id = this.getAttribute('data-value');
    const newDiscount = this.value;
    const type = $('.type-' + id).val();

    $.ajax({
      url: '/pos/updateDiscount',
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      contentType: 'application/json',
      data: JSON.stringify({
        productId: id,
        discount: newDiscount,
        discount_type: type
      }),
      success: function(data) {
        const product = data.product;
        const disc = data.saleTotalDisc;

        if (type == 1) {
          $('#total-' + id).text('Rp.' + formatCurrency(parseInt(product.price) * parseInt(product.quantity) - product.discount));
        } else {
          $('#total-' + id).text('Rp.' + formatCurrency(product.price * product.quantity * (1 - product.discount / 100)));
        }

        let total = 0;
        for (const item of data.products) {
          if (item.discount_type == 2) {
            total += (item.price * item.quantity) * (1 - item.discount / 100) - disc;
          } else {
            total += (item.price * item.quantity) - item.discount - disc;
          }
        }

        if(total < 0){
          total = 0;
        }

        $('#pembayaran #total-m').text('Rp.' + formatCurrency(total));
        $('#total').text('Rp.' + formatCurrency(total));
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Terjadi kesalahan. ' + errorThrown);
      }
    });
  });

  $('.type').change(function(){
    const id = this.getAttribute('data-value');
    const newDiscount = $("#discount-"+id).val();
    const type = this.value;

    $.ajax({
      url: '/pos/updateDiscount',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      contentType: 'application/json',
      data: JSON.stringify({
        productId: id,
        discount: newDiscount,
        discount_type: type
      }),
      success: function(data) {
        const product = data.product;
        console.log('Diskon diperbarui:', product);

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
      },
    })
  });

  $('.deleteButton').click(function() {
    const productId = $(this).data('value');
    $.ajax({
      url: '/pos/deleteSession/'+productId,
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      contentType: 'application/json',
      success: function(data) {
        //location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Terjadi kesalahan. ' + errorThrown);
      }
    });
  });
</script>
@endsection
