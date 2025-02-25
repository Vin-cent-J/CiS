@extends('layout.app')

@section("nav")
<nav class="px-3 py-2 bg-dark">
  <a type="button" class="btn btn-warning" href="{{ route('newsales') }}">
    + Sales
  </a>
</nav>
@endsection
@section("isi")
<div class="p-3 container card bg-white" style="min-height: 840px;">
  <table class="table">
    <thead>
      <t>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Customer</th>
        <th scope="col">Total</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>2000-01-01</td>
        <td>Ex</td>
        <td>0</td>
        <td>Paid</td>
        <td><a class="btn btn-warning">Detail</a></td>
      </tr>
    </tbody>
  </table>
</div>

@endsection