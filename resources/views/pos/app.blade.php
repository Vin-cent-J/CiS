@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/pos/history')}}">
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
            <strong class="product-total">Rp.{{number_format($product['price'] * $product['quantity'])}} </strong>
          </div>
        </div>
        <div class="text-muted">
          <strong class="product-qty">{{ $product['quantity'] }}</strong> <small>x Rp.{{number_format($product['price'])}} / Unit</small>
        </div>
        <div class="text-gray-600 flex justify-between items-center mt-1">
          @if (in_array(20, $activeDetails) && $features->contains('id',3))
          <p class="text-sm">Diskon: <input type="number" class="w-16 p-1 border border-gray-300 rounded-md text-sm product-discount-input" min="0">
            <select name="jenis" id="">
              @if (in_array(2, $activeDetails))
              <option value="persen">%</option>
              @endif
              @if (in_array(1, $activeDetails))
              <option value="tunai">Tunai</option>
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

      <div class="row card w-100 position-absolute bottom-0 p-1">
        @if ($features->contains('id',3))
        <div class="diskontotal">
          @if (in_array(19, $activeDetails) && $features->contains('id',3))
          <div class="m-1">
            Diskon:
            <input type="number" class="form-control">
          </div>
          @endif
          <div class="text-end">
            Total: <strong> Rp.{{ number_format(1000)}}</strong>
            <hr>
          </div>
        </div>
        @endif
        <div class="m-2 text-end">
          <a type="button" class="btn btn-warning" href="#">Simpan</a>
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
            <button id="addToCart" class="btn-sm btn-primary addToCart" style="float: right;" data-value='@json($p)'>
              + Tambah
            </button>
          </div>
        </div>
        @endforeach
      </div>
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
        <p class="text-sm">Diskon: <input type="number" class="w-16 p-1 border border-gray-300 rounded-md text-sm product-discount-input" min="0" max="100">%</p>
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
          <strong class="product-total">Rp.${formatCurrency(totalPrice)}</strong>
        </div>
      </div>
      <div class="text-muted">
        <strong class="product-qty">${quantity}</strong> <small>x Rp. ${formatCurrency(price)} / Unit</small>
      </div>
      ${discountSectionHtml}
    </div>`;
    return $(productHtml);
  }

  $('.addToCart').click(function() {
    const productData = JSON.parse(this.getAttribute('data-value'));
    if (!productData || typeof productData.id === 'undefined') {
      console.error('Product data is missing or invalid.');
      return;
    }

    $(this).prop('disabled', true).text('Menambahkan...');

    $.ajax({
      url: '/pos/setSession',
      method: 'POST',
      headers: {
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
          quantity: productData.quantity
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

  function updateItemDiscount(inputElement) {
    const productId = inputElement.dataset.productId;
    const newDiscount = inputElement.value;

    if (newDiscount === '' || isNaN(newDiscount) || newDiscount < 0 || newDiscount > 100) {
      alert('Diskon harus di antara 0-100%.');
      inputElement.value = inputElement.defaultValue;
      return;
    }

    fetch('/pos/update-item-discount', { 
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      body: JSON.stringify({
        productId: productId,
        discount: newDiscount
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log('Discount updated successfully:', data.cart);
        alert('Diskon berhasil diperbarui.');
      } else {
        console.error('Failed to update discount:', data.message);
        alert('Gagal memperbarui diskon: ' + data.message);
        inputElement.value = inputElement.defaultValue; 
      }
    })
    .catch(error => {
      console.error('AJAX Error:', error);
      alert('Terjadi kesalahan.');
      inputElement.value = inputElement.defaultValue;
    });
  }
</script>
@endsection
