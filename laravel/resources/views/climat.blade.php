@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">

<script src="{!! asset('/js/hchart/code/highcharts.js') !!}"></script>

<link rel="stylesheet" href="{!! asset('/css/climat.css') !!}" type="text/css">
<script type='text/javascript' src="{!! asset('/js/climat.js') !!}"></script>

<div class="container-fluid">
	<h5 class="text-dark mt-4">Климат - мониторинг рабочих мест</h5>
	
	@if($user_id == 1)
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="monitoring-tab" data-toggle="tab" href="#monitoring" role="tab" aria-controls="monitoring" aria-selected="true"><strong>Мониторинг <i class="fas fa-chart-line"></i></strong></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="workplaces-tab" data-toggle="tab" href="#workplaces" role="tab" aria-controls="workplaces" aria-selected="false"><strong>Рабочие места <i class="fas fa-bed"></i></strong></a>
		</li>
	</ul>
	<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade show active" id="monitoring" role="tabpanel" aria-labelledby="monitoring-tab">
	@endif
		
		<div class="alert alert-primary mt-1" role="alert">
			<div class="dropdown float-left mr-5">
				<button class="btn btn-outline-primary dropdown-toggle" type="button" id="button_workplaces">Рабочие места</button>
				<ul class="dropdown-menu" id="list_workplaces" style="display: none;">
				@if(!empty($workplaces))
					<div class="checkbox pl-1">
						<label>
							<input type="checkbox" name="all_places" id="all_places">
							<strong>Выбрать все</strong>
						</label>
					</div>
					@foreach($workplaces as $key=>$row)
					<div class="checkbox bg-light pl-1">
						<strong class="text-primary">{{$row['name']}}</strong>
					</div>
					@if(isset($row['children']))
						@foreach($row['children'] as $r)
						<div class="checkbox pl-1">
							<label>
								<input type="checkbox" name="workplace" value="{{$r['id']}}">
								{{$r['name']}}
							</label>
						</div>
						@endforeach
					@endif
					@endforeach
				@endif
				</ul>
			</div>
			<form class="form-inline">
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
					<label class="btn btn-outline-primary">
						<input type="radio" name="options" id="today" autocomplete="off">За день
					</label>
					<label class="btn btn-outline-primary">
						<input type="radio" name="options" id="week" autocomplete="off">За неделю
					</label>
					<label class="btn btn-outline-primary">
						<input type="radio" name="options" id="month" autocomplete="off">За месяц
					</label>
				</div>
				
				<div class="input-group pr-3 pl-5 text-secondary">За период:</div>
				<div class="input-group">
					<div class="input-group-prepend border border-primary rounded-left">
						<span class="input-group-text text-primary">
							<i class="far fa-calendar-alt"></i>
						</span>
					</div>
					<input type="date" id="date_start" class="form-control border border-primary text-primary" value="">
				</div>
				<div class="input-group px-3 text-secondary">–</div>
				<div class="input-group pr-3">
					<div class="input-group-prepend border border-primary rounded-left">
						<span class="input-group-text text-primary">
							<i class="far fa-calendar-alt"></i>
						</span>
					</div>
					<input type="date" id="date_end" class="form-control border border-primary text-primary" value="">
				</div>
				<!-- <button type="button" class="btn btn-success mr-3" id="filter"><i class="fas fa-check"></i></button> -->
				<!-- <button type="button" class="btn btn-danger" id="clear"><i class="fas fa-times"></i></button> -->
			</form>
		</div>
		
		<div class="row">
			<div class="col-6">
				<div id="temperature"></div>
			</div>
			<div class="col-6">
				<div id="humidity"></div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-12">
				<table class="table table-sm table-bordered table-striped table-hover" id="table_result">
					<thead>
						<tr>
							<th scope="col" rowspan="2">Рабочее место</th>
							<th scope="col" rowspan="2">Актуальные данные</th>
							<th scope="col" colspan="3">Температура ℃</th>
							<th scope="col" colspan="3">Влажость %</th>
						</tr>
						<tr>
							<th scope="col">Мин.</th>
							<th scope="col">Средн.</th>
							<th scope="col">Макс.</th>
							<th scope="col">Мин.</th>
							<th scope="col">Средн.</th>
							<th scope="col">Макс.</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
		
	@if($user_id == 1)
		</div>
		<div class="tab-pane fade" id="workplaces" role="tabpanel" aria-labelledby="workplaces-tab">
			<div class="row">
				<div class="col-4">
					<div class="table">
						<table id="workplace" class="table table-hover table-sm">
							<tbody>
							@if(!empty($workplaces))
								@foreach($workplaces as $key=>$row)
								<tr class="active" data-catalog="{{$key}}">
									<td width="30">@if(isset($row['children']))<button type="button" class="btn btn-link text-secondary p-0 m-0 open" title="Развернуть"><i class="fas fa-bars"></i></button>@endif</td>
									<td><strong>{{$row['name']}}</strong></td>
									<td width="110" class="text-right">
										<div class="btn-group btn-group-sm d_none" role="group">
											<button type="button" class="btn btn-light text-success add" title="Добавить рабочее место"><i class="fas fa-plus"></i></button>
											<button type="button" class="btn btn-light text-warning edit" title="Редактировать расположение"><span class="oi oi-pencil"></span></button>
											<button type="button" class="btn btn-light text-danger delete" title="Удалить расположение"><span class="oi oi-trash"></span></button>
										</div>
									</td>
								</tr>
								@if(isset($row['children']))
								@foreach($row['children'] as $r)
								<tr class="parent_{{$key}} d_none" data-file="{{$r['id']}}" data-parent="{{$key}}">
									<td width="30"></td>
									<td class="pl-3">{{$r['name']}}</td>
									<td width="110" class="text-right">
										<div class="btn-group btn-group-sm d_none" role="group">
											<button type="button" class="btn btn-light text-warning edit" title="Редактировать рабочее место"><span class="oi oi-pencil"></span></button>
											<button type="button" class="btn btn-light text-danger delete" title="Удалить рабочее место"><span class="oi oi-trash"></span></button>
										</div>
									</td>
								</tr>
								@endforeach
								@endif
								@endforeach
							@endif
								<tr>
									<td colspan="3"><button type="button" class="btn btn-sm btn-success new_area"><i class="fas fa-plus"></i> Новое расположение</button></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
</div>
@endsection