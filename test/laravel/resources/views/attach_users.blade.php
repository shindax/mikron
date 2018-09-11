@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{!! asset('/css/inventory.css') !!}" type="text/css">
<script type='text/javascript' src="{!! asset('/js/inventory.js') !!}"></script>
<script>var attach_user = "{!! $attach_user !!}";</script>

<style>
.show{display:block; height:auto;}
.container-fluid{
	padding-left:5px;
	padding-right:5px;
}
h4{margin-left:100px;}
</style>
<div class="container-fluid">
<h4 class="text-dark">Подотчёт</h4>
	<div class="tab-content" id="myTabContent">
		<div role="tabpanel" class="tab-pane fade show active" id="home" aria-labelledby="home-tab">
			@if(count($catalog_list)>0)
			<div role="tablist">
				@foreach($catalog_list as $key=>$catalog)
				<div class="card alert-primary">
					<div class="card-header pt-1 pb-1" data-catalog="{{$key}}">
						<h5 class="float-left mt-2">
							<a class="collapsed text-dark" href="#0">
							{{$catalog}}
							</a>
						</h5>
					</div>
					<div data-catalog-body="{{$key}}" class="collapse">
						<div id="catalog_{{$key}}" class="card-body">
						</div>
					</div>
				</div>
				@endforeach
			</div>
			@endif
		</div>
	</div>
</div>
@endsection