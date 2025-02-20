@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <button type="button" class="btn btn-warning">
    New Purchase +
  </button>
</nav>
@endsection
@section("isi")
<div class="p-3 container card bg-white" style="min-height: 840px;">
  <table class="table">
    <thead>
      <t>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Supplier</th>
        <th scope="col">Total</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>2000-01-01</td>
        <td>Ex</td>
        <td>0</td>
        <td><button class="btn btn-warning">Detail</button></td>
      </tr>
    </tbody>
  </table>
</div>

@endsection