@extends("layout.app")

@section('nav')
<nav class="px-3 bg-dark">
    <p class="text-light py-1"><a href="{{route('purchase')}}">purchase</a> / 1</p>
</nav>
@endsection

@section("isi")
<div class="container card p-3" style="min-height: 85vh">
    <div class="row">
        <div class="col-md-6">
            <h5>Supplier</h5>
            <p><strong>Deco Addict</strong><br>
                77 Santa Barbara Rd<br>
                Pleasant Hill CA 94523<br>
                United States - US12345673
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <p><strong>Order Date:</strong> 09/10/2023 19:10:12</p>
            <p><strong>Total Debt:</strong> Rp.0</p>
            <p><strong>Payment Terms:</strong> 30 Days</p>
        </div>
    </div>

    <hr>
    <div class="text-end">
        <p>Reduce Debt by: <input type="number" name="r_debt" style="width: 15%" value="0"></p>
        <a type="button" class="btn btn-warning">Save</a>
    </div>

    <hr>
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Disc.%</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Garden Lamp</td>
                    <td>1.00</td>
                    <td>Rp.1350</td>
                    <td>0.00</td>
                    <td>Rp.1350</td>
                    <td><a type="button" class="btn btn-warning" href="">Refund</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection