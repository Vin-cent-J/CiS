@extends("layout.app")

@section('title', 'Beranda')

@section("isi")
<div class="row px-4 my-4 d-flex">
  @foreach ($features as $feature)
  <a class="col-3 text-decoration-none text-dark mb-3" href="{{$feature->route}}">
    <div class="card shadow" style="height: 15rem;">
      <div class="card-body fs-4 d-flex align-items-center">
        <p class="mx-auto"><i class="{{$feature->icon}} text-primary"></i> {{$feature->name}}</p>
      </div>
    </div>
  </a>
  @endforeach
</div>
@endsection