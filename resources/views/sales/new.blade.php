@extends("layout.app")

@section("isi")
<div class="container card p-3" style="min-height: 85vh">
    <div class="row">
        <div class="col-md-6">
            <h5>Customer</h5>
            <p><strong>Deco Addict</strong><br>
                77 Santa Barbara Rd<br>
                Pleasant Hill CA 94523<br>
                United States - US12345673
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <p><strong>Order Date:</strong> 09/10/2023 19:10:12</p>
            <p><strong>Recurrence:</strong> -</p>
            <p><strong>Pricelist:</strong> Benelux (USD)</p>
            <p><strong>Payment Terms:</strong> 30 Days</p>
        </div>
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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Garden Lamp</td>
                    <td>1.00</td>
                    <td>Rp.1350</td>
                    <td>0.00</td>
                    <td>Rp.1350</td>
                    <td class="text-center"><a href=""><i class="bi bi-trash3-fill"></i></a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <button class="btn btn-warning">Add a product</button>
        <button class="btn btn-warning">Save</button>
    </div>
</div>

@endsection