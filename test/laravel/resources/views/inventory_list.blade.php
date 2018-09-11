@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{!! asset('/css/inventory.css') !!}" type="text/css">
<script type='text/javascript' src="{!! asset('/js/inventory.js') !!}"></script>

<style>
.show{display:block; height:auto;}
</style>

<div class="container-fluid">
<h5 class="text-warning">Ведомость</h5>
	<div class="tab-content" id="myTabContent">
		<div role="tabpanel" class="tab-pane fade show active" id="home" aria-labelledby="home-tab">
			@if(count($catalog_list)>0)
			<div role="tablist">
				@foreach($catalog_list as $catalog)
				<div class="card alert-primary">
					<div class="card-header pt-1 pb-1" data-catalog="{{$catalog->id}}">
						<h5 class="float-left mt-2">
							<a class="list_table text-dark" href="#0">
							{{$catalog->name}}
							</a>
						</h5>
					</div>
					<div data-catalog-body="{{$catalog->id}}" class="collapse">
						<div id="catalog_{{$catalog->id}}" class="card-body">
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