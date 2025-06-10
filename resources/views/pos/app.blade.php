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
                @if ($product['discount_id'] == 1)
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
              <select name="discount_id" class="type-{{$product['id']}} type" data-value="{{$product['id']}}">
                @if (in_array(2, $activeDetails))
                <option value="2">%</option>
                @endif
                @if (in_array(1, $activeDetails))
                <option value="1">Tunai</option>
                @endif
              </select>
            </p>
            @endif
            <a class="btn btn-danger text-red-500 hover:text-red-700 focus:outline-none float-right" href="{{ url('/pos/deleteSession', $product['id']) }}">
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
        if($product['discount_id'] == 2) {
          $diskon = ($product['price'] * $product['quantity']) * ($product['discount'] / 100);
          $total -= $diskon;
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
            <input type="number" class="form-control" id="diskon-nota">
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


<div class="modal fade" id="pembayaran" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{url('/pos')}}" method="post">
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
          @if ($features->contains('id',15))
          <input type="number" name="paid" placeholder="Jumlah Yang Dibayarkan" class="form-control"><br>
          @endif
          <label for="metode">Metode Pembayaran:</label>
          <select class="form-select" name="payment_method" id="metode">
            <option value="tunai">Tunai</option>
            <option value="transfer">Transfer</option>
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
        'quantity': productData.quantity
      }),
      success: function(data) {
        const newProduct = {
          id: productData.id,
          name: productData.name,
          price: productData.price,
          quantity: productData.quantity,
          discount_id: type,
        };

        const updatedCartItems = data.products;
        if (productList && productList.length) {
          productList.empty();
          if (updatedCartItems.length === 0) {
            productList.append('<br><div class="text-muted text-center">Keranjang masih kosong</div>');
          } else {
            location.reload();
            updatedCartItems.forEach(item => {
              productList.append(createProductElement(item));
            });
          }
        }
      },
      complete: function() {
        $(this).prop('disabled', false).text('+ Tambah');
      }
    });
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
        discount_id: type
      }),
      success: function(data) {
        if (data.success) {
          const product = data.product;
          console.log('Diskon diperbarui:', product);

          if (type == 1) {
            $('#total-' + id).text('Rp.' + formatCurrency(parseInt(product.price) * parseInt(product.quantity) - product.discount));
          } else {
            $('#total-' + id).text('Rp.' + formatCurrency(product.price * product.quantity * (1 - product.discount / 100)));
          }

          let total = 0;
          for (const item of data.products) {
            if (item.discount_id == 2) {
              total += (item.price * item.quantity) * (1 - item.discount / 100);
            } else {
              total += (item.price * item.quantity) - item.discount;
            }
          }
          $('#pembayaran #total-m').text('Rp.' + formatCurrency(total));
          $('#total').text('Rp.' + formatCurrency(total));
        } else {
          alert('Gagal memperbarui diskon: ' + data.message);
        }
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

    fetch('/pos/updateDiscount', { 
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      body: JSON.stringify({
        productId: id,
        discount: newDiscount,
        discount_id: type
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const product = data.product;
        console.log('Diskon diperbarui:', product);

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
