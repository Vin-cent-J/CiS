@extends('layout.app')

@section("title", "PoS")

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{url('/pos/riwayat')}}">
    Laporan
  </a>
  @if (in_array(6, $activeConfigs) && $features->contains('id',3))
  <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#syarat">
    Syarat Diskon
  </a>
  @endif
</nav>
@endsection
@section("isi")
@php
  $taxActive = in_array(3, $activeDetails);
  $taxRate = $taxActive ? 0 : 0.11;
@endphp
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
      echo "<script>console.log(".json_encode($activeConfigs).")</script>";
      $total = 0;
      foreach (session('products', []) as $product) {
        $total += $product['price'] * $product['quantity'];
        
        if($product['discount_type'] == 2) {
          $diskon = ($product['price'] * $product['quantity']) * ($product['discount'] / 100);
          $total -= $diskon - session('saleTotalDisc', 0);
        } else{
          $total -= $product['discount'];
        }
      }
      $afterTax = $total + $total * $taxRate;
      @endphp
      <div class="row w-100 position-absolute bottom-0 p-1">
        <div class="diskontotal">
          @if (in_array(19, $activeDetails) && $features->contains('id',3))
          <div class="m-1">
            Diskon:
            <input id="totalDisc" type="number" class="form-control totalDisc" id="diskon-nota">
          </div>
          @endif
          <div class="text-end">
            @if ($taxActive)
            Pajak: <strong id="tax"> Rp. {{ number_format($total*$taxRate) }}</strong> <br>
            @endif
            Total: <strong id="total"> Rp.{{ number_format($afterTax)}}</strong>
            <hr>
          </div>
        </div>
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
      <div class="row p-2" style="overflow-y: scroll">
        @foreach ($products as $product)
        @if($product->variants->count() > 0)
          @foreach($product->variants as $variant)
            @php
            $p = [
              "name" => $product->name . " - " . $variant->name, 
              "id" => "$variant->id", 
              "type" => "variant",
              "price" => "$variant->price", 
              "quantity" => 1
            ];
            @endphp
            <div class="col-2 p-2 m-1 card" style="height: 18rem;">
                <img src="/storage/{{$product->photo}}" style="max-height: 60%;">
                <div class="p-2">
                <div>
                    <strong>{{$product->name}} - {{$variant->name}}</strong>
                </div>
                <div class="text-muted">
                    <small>Rp. {{number_format($variant->price)}} </small>
                </div>
                <button class="btn-sm btn-primary addToCart" style="float: right;" data-value='@json($p)'>
                    + Tambah
                </button>
                </div>
            </div>
          @endforeach

        @else
          @php
          $p = [
            "name" => $product->name, 
            "id" => "$product->id", 
            "type" => "product",
            "price" => "$product->price", 
            "quantity" => 1
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
              <button class="btn-sm btn-primary addToCart" style="float: right;" data-value='@json($p)'>
                + Tambah
              </button>
            </div>
          </div>
        @endif
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

<div class="modal fade" id="syarat" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Syarat Diskon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-bold">Minimal Pembelian</label>
            <input type="number" id="input-minimal" name="minimal" min="0" value="0" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Berlaku Untuk Kategori</label>
            <select id="select-category" name="categories[]" class="form-control" multiple style="height: 200px;">
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}: {{ $category->discountRule->minimum ?? 0 }}</option>
              @endforeach
            </select>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> 
                Tahan tombol <b>CTRL</b> (Windows) atau <b>Command</b> (Mac) untuk memilih lebih dari satu.
            </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-simpan">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section("js")
<script>
  const useTax = @json( in_array(3, $activeConfigs));
  const taxRate = useTax ? 0 : 0.11;

  const discountRules = @json($discountRules);

  $(document).ready(function () {
    function updateServerDiscount(productId, val, type) {
      $.ajax({
        url: '/pos/updateDiscount',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        contentType: 'application/json',
        data: JSON.stringify({
          productId: productId,
          discount: val,
          discount_type: type
        }),
        success: function(data) {
          location.reload(); 
        }
      });
    }

    @foreach (session('products', []) as $product)
        const productId = {{ $product['id'] }};
        const categoryId = {{ $product['categories_id'] ?? 'null' }};
        const quantity = {{ $product['quantity'] ?? 1 }};
        
        const discountInput = $('#discount-' + productId);
        const typeSelect = $('.type-' + productId);

        let rule = discountRules.find(r => r.products_id === productId);
        if (!rule) {
          rule = discountRules.find(r => r.categories_id === categoryId);
        }

        if (rule) {
            if (quantity < rule.minimum) {
              discountInput.prop('disabled', true);
              typeSelect.prop('disabled', true);

              if (parseFloat(discountInput.val()) > 0) {
                discountInput.val(0);
                updateServerDiscount(productId, 0, 1);
              }
            } else {
              discountInput.prop('disabled', false);
              typeSelect.prop('disabled', false);
            }
        } else {
          discountInput.prop('disabled', false);
          typeSelect.prop('disabled', false);
        }
    }
    @endforeach
  });

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
        'type': productData.type,
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

  $('#btn-simpan').click(function(e) {
    e.preventDefault();

    var minimalVal = $('#input-minimal').val();
    var categoryVal = $('#select-category').val();

    if (!categoryVal || categoryVal.length === 0) {
      alert("Pilih setidaknya satu kategori!");
      return;
    }

    var saveBtn = $(this);
    saveBtn.text('Menyimpan...').prop('disabled', true);

    $.ajax({
        url: "/discounts/insertRule",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: {
            minimal: minimalVal,
            categories: categoryVal
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
        location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Terjadi kesalahan. ' + errorThrown);
      }
    });
  });
</script>
@endsection
