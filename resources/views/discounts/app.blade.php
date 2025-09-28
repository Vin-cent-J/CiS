@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">

</nav>
@endsection
@section("isi")
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Syarat Diskon</h2>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ruleModal" id="addRuleBtn">
            + Tambah
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Berlaku Untuk</th>
                            <th>Minimal Pembelian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discountRules as $rule)
                        <tr>
                            <td>
                                @if($rule->categories_id !== null)
                                <span class="badge bg-info">Kategori</span>
                                <small class="d-block text-muted">{{ $rule->category->name }}</small>
                                @elseif($rule->products_id !== null)
                                <span class="badge bg-success">Produk</span>
                                <small class="d-block text-muted">{{ $rule->product->name }}</small>
                                @else
                                <span class="badge bg-secondary">Semua</span>
                                @endif
                            </td>
                            <td>{{ $rule->minimum }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary edit-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ruleModal"
                                        data-id="{{ $rule->id }}"
                                        data-minimum="{{ $rule->minimum }}">
                                    Ubah
                                </button>

                                <form class="d-inline" action="/discounts/{{ $rule->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-id="{{ $rule->id }}" id="btnDelete" onclick="return confirm('Apakah anda ingin menghapus syarat ini?');">Hapus</button>
                                </form>
                                
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada syarat diskon.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ruleModal" tabindex="-1" aria-labelledby="ruleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="ruleModalLabel">Tambah syarat baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="minimum_purchase" class="form-label" value="0" min="0" required>Minimal pembelian</label>
                        <input type="number" class="form-control" id="minimum_purchase" name="minimum_purchase" min="0">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="line_discount" class="form-label">Diskon</label>
                        <input type="text" class="form-control" id="line_discount" name="line_discount" required>
                    </div>
                </div>
                <hr>

                <div class="mb-3">
                    <label class="form-label">Berlaku untuk:</label>
                    <div class="form-check">
                        <label class="form-check-label" for="applyToCategories">
                            Kategori tertentu
                        </label>
                        <input class="form-check-input" type="radio" name="discount_type" id="applyToCategories" value="categories" checked>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label" for="applyToProducts">
                            Produk tertentu
                        </label>
                        <input class="form-check-input" type="radio" name="discount_type" id="applyToProducts" value="products">  
                    </div>
                    <div class="form-check">
                        <label class="form-check-label" for="applyToProducts">
                            Semua
                        </label>
                        <input class="form-check-input" type="radio" name="discount_type" id="applyToAll" value="all">
                    </div>
                </div>

                <div id="categories-container" class="mb-3">
                    <label for="category_ids" class="form-label">Pilih Kategori</label>
                    <select class="form-select " id="category_ids" name="category_ids">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="products-container" class="mb-3" style="display: none;">
                    <label for="product_ids" class="form-label">Pilih Produk</label>
                    <select class="form-select " id="product_ids" name="product_ids">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("js")
<script>
$('input[name="discount_type"]').on('change', function() {
    if (this.value === 'categories') {
        $('#categories-container').show();
        $('#products-container').hide();
    } else if (this.value === 'products') {
        $('#categories-container').hide();
        $('#products-container').show();
    } else {
        $('#categories-container').hide();
        $('#products-container').hide();
    }
});

var methods = "add";
var ruleId = 0;
$('#saveBtn').on('click', function() {

    if(methods == 'add') {
        $.ajax({
            url: '/discounts',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            contentType: 'application/json',
            data: JSON.stringify({
                name: $('#name').val(),
                minimum: $('#minimum_purchase').val(),
                category_ids: $('#category_ids').val(),
                product_ids: $('#product_ids').val(),
                line_discount: $('#line_discount').val(),
            }),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 409) {
                    alert('Syarat diskon dengan kategori atau produk yang sama sudah ada.');
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            }
        });
    } else {
        alert('update');
        $.ajax({
            url: '/discounts/updateRule',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            contentType: 'application/json',
            data: JSON.stringify({
                id: ruleId,
                name: $('#name').val(),
                minimum: $('#minimum_purchase').val(),
                category_ids: $('#category_ids').val(),
                product_ids: $('#product_ids').val(),
                line_discount: $('#line_discount').val(),
            }),
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status) {
                if (xhr.status === 409) {
                    alert('Syarat diskon dengan kategori atau produk yang sama sudah ada.');
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            }
        });
    }
    
});

$('#addRuleBtn').on('click', function() {
    methods = 'add';
    $('#ruleForm').trigger('reset');
    $('#ruleForm').attr('action', '{{ route('discounts.store') }}');
    $('#formMethod').val('POST');
    $('#ruleModalLabel').text('Tambah Syarat Diskon');
    $('#saveBtn').text('Simpan');
    
    $('#category_ids').val(null).trigger('change');
    $('#product_ids').val(null).trigger('change');
    
    $('#applyToCategories').prop('checked', true).trigger('change');
});

$('.edit-btn').on('click', function() {
    methods = 'edit';
    const button = $(this);
    const id = button.data('id');
    const minimum = button.data('minimum');
    const categories = button.data('categories');
    const products = button.data('products');

    ruleId = id;

    $('#ruleModalLabel').text('Ubah Syarat Diskon');
    $('#saveBtn').text('Update');

    $('#minimum_purchase').val(minimum);

    if (categories && categories.length > 0) {
        $('#applyToCategories').prop('checked', true).trigger('change');
        $('#category_ids').val(categories).trigger('change');
    } else if (products && products.length > 0) {
        $('#applyToProducts').prop('checked', true).trigger('change');
        $('#product_ids').val(products).trigger('change');
    } else {
        $('#applyToAll').prop('checked', true).trigger('change');
        $('#category_ids').val(null).trigger('change');
        $('#product_ids').val(null).trigger('change');
    }
});
</script>
@endsection