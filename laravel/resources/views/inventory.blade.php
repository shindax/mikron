@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{!! asset('/css/inventory.css') !!}" type="text/css">
<script type='text/javascript' src="{!! asset('/js/inventory.js') !!}"></script>

<style>
.show{display:block; height:auto;}
</style>

<div class="container-fluid">
<h5 class="text-dark mt-3 mb-3">Инвентаризация</h5>
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a href="#home" id="home-tab" class="nav-link active" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">
				<strong>Разделы <span class="oi oi-layers"></span></strong>
			</a>
		</li>
		@if($user['role'] < 2)
		<li class="nav-item">
			<a href="#profile" id="profile-tab" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab">
				<strong>Пользователи <span class="oi oi-people"></span></strong>
			</a>
		</li>
		@endif
	</ul>
	<div class="tab-content" id="myTabContent">
		<div role="tabpanel" class="tab-pane fade show active" id="home" aria-labelledby="home-tab">
				@if(count($parent_catalog_list)>0)
				<div class="input-group input-group-search mt-3 mb-3">
					<input type="text" class="form-control border border-info border-right-0 search" placeholder="Искать" aria-label="Искать">
					<div class="input-group-append">
						<button id="search_reset" class="btn btn-outline-info border-left-0 d_none" type="button" title="Сбросить"><span class="oi oi-delete"></span></button>
						<button id="search" class="btn btn-info" type="button" title="Искать"><span class="oi oi-magnifying-glass"></span></button>
					</div>
				</div>
				<div id="catalog_conteiner" role="tablist">
					@foreach($parent_catalog_list as $catalog)
					<div class="card alert-primary">
						<div class="card-header pt-1 pb-1" data-catalog="{{$catalog['id']}}">
							<h5 class="float-left mt-2">
								<a class="collapsed text-dark" href="#0">
								{{$catalog['name']}}
								</a>
							</h5>
							<div class="btn-toolbar add_edit_delete" role="toolbar">
								<div class="btn-group btn-group-sm float-right" role="group">
									<button type="button" class="btn btn-outline-success add_child" title="Добваить дочерний раздел"><span class="oi oi-plus"></span></button>
									<button type="button" class="btn btn-outline-warning edit_catalog" title="Редактировать раздел"><span class="oi oi-pencil"></span></button>
									<button type="button" class="btn btn-outline-danger delete_catalog" title="Удалить раздел"><span class="oi oi-trash"></span></button>
								</div>
								<div class="btn-group btn-group-sm float-right mr-4" role="group">
									<button type="button" class="btn btn-outline-secondary add_string" title="Добваить строку в таблице"><span class="oi oi-spreadsheet"></span></button>
									<button type="button" class="btn btn-outline-info modal_structure" title="Структура таблицы"><span class="oi oi-fork"></span></button>
								</div>
								<div class="btn-group btn-group-sm float-right mr-4" role="group">
									<button type="button" class="btn btn-outline-primary table_list" title="Ведомость"><span class="oi oi-document"></span></button>
								</div>
							</div>
						</div>
						<div data-catalog-body="{{$catalog['id']}}" class="collapse">
							<div id="catalog_{{$catalog['id']}}" class="card-body">
							</div>
						</div>
					</div>
					@endforeach
				</div>
				@endif
			@if($user['role'] < 2)
			<button type="button" class="btn btn-outline-success add_cat"><span class="oi oi-plus"></span> Новый раздел</button>
			@endif
		</div>
		@if($user['role'] < 2)
		<div role="tabpanel" class="tab-pane fade" id="profile" aria-labelledby="profile-tab">
			@if(isset($user_list))
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-sm users">
					<thead>
						<tr>
							<th>ФИО</th>
							<th>e-mail</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					@foreach($user_list as $row)
						<tr data-user-id="{{$row->id}}">
							<td>{{$row->name}}</td>
							<td>{{$row->email}}</td>
							<td>
								<div class="btn-group btn-group-sm" role="group">
									<button type="button" class="btn btn-light text-warning edit_user" title="Редактировать пользователя"><span class="oi oi-pencil"></span></button>
									<button type="button" class="btn btn-light text-danger delete_user" title="Удалить пользователя"><span class="oi oi-trash"></span></button>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			@endif
			<h5 class="text-warning">Добавить нового пользователя</h5>
			<div class="row">
				<div class="col-4">
					<div class="input-group">
						<select class="form-control" name="user">
							<option class="font-weight-bold" value="" selected="">-- Пользователи --</option>
							@foreach($old_users as $row)
							<option value="{{$row->ID}}">{{$row->FIO}}</option>
							@endforeach
						</select>
						<div class="dropdown">
							<button class="btn bg-white rounded-0 dropdown-toggle" type="button" id="button_users">
								Разделы инвентаризации
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" id="user_role_cat">
							@if($catalog_chechbox_list !== '')
								<li>
									<b data-toggle="tooltip" data-html="true" data-placement="top" title="Модератор">М</b>&nbsp;
									<b data-toggle="tooltip" data-html="true" data-placement="top" title="Пользователь">П</b>
								</li>
								@foreach($catalog_chechbox_list as $checkbox)
								@if(isset($checkbox['class']))
								<li data-catalog-id="{{$checkbox['id']}}" data-level="{{$checkbox['level']}}" class="{{$checkbox['class']}}">
								@else
								<li data-catalog-id="{{$checkbox['id']}}" data-level="{{$checkbox['level']}}">
								@endif
									<div class="checkbox">
										<label>
											<input type="checkbox" name="role" value="1">
											<input type="checkbox" name="role" value="2">
											{{$checkbox['level_html']}}{{$checkbox['name']}}
										</label>
									</div>
								</li>
								@endforeach
							@endif
							</ul>
						</div>
						<div class="input-group-append">
							<span class="input-group-btn"><button type="button" class="btn btn-info add_user">Добавить</button></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>




<div class="modal fade" id="modal_structure" tabindex="-1" role="dialog" aria-labelledby="modal_structure_label" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-info" id="modal_structure_label"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- <div class="table-responsive">
					<table class="table table-bordered table-hover table-sm" id="table_structure">
						<thead>
							<tr>
								<th></th>
								<th>Наименование ячейки</th>
								<th>Тип поля</th>
								<th title="Журнал изменений"><span class="oi oi-spreadsheet"></span></th>
								<th></th>
							</tr>
						</thead>
						<tbody id="sortable"></tbody>
					</table>
				</div> -->
				
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm border-top border-bottom"></div>
								<div class="col-sm-6 border border-left-0 font-weight-bold">Наименование ячейки</div>
								<div class="col-sm-3 border border-left-0 font-weight-bold" data-type="text">Тип поля</div>
								<div class="col-sm border-top border-bottom" title="Журнал изменений"><span class="oi oi-spreadsheet"></span></div>
								<div class="col-sm border-top border-bottom"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="container sortable" id="table_structure">
				</div>
				
				<h6 id="input_group_structure_header" class="text-info mt-3">Добавить новую ячейку</h6>
				<div id="input_group_structure" class="input-group input-group-sm">
					<div class="input-group-prepend" data-toggle="tooltip" data-placement="top" title="Вести журнал изменений">
						<div class="input-group-text">
							<input type="checkbox" name="log_flag" value="1" disabled>
						</div>
					</div>
					<input type="text" class="form-control form-control-sm" name="name" value="" placeholder="Наименование ячейки">
					<select class="form-control form-control-sm" id="structure_select">
						<option class="font-weight-bold" value="" selected disabled>-- Поместить в ячейку --</option>
					</select>
					<select class="form-control form-control-sm" id="type_fields_select">
						<option class="font-weight-bold" value="" selected disabled>-- Тип поля --</option>
					</select>
					<div class="input-group-append">
						<button type="button" id="add_structure" class="btn btn-info">Добавить</button>
					</div>
				</div>
				
				<h6 class="text-success mt-5">Мои выпадающие списки</h6>
				
				<div id="selection_custom_fields" class="row">
					<div class="col-5">
						<div class="input-group input-group-sm">
							<input type="text" name="selection_custom" value="" class="form-control" placeholder="Наименование списка" aria-label="Наименование списка">
							<div class="input-group-append">
								<button class="btn btn-success add_selection_custom" type="button">Добавить</button>
							</div>
						</div>
					</div>
					<div class="col-7">
						<div class="input-group input-group-sm">
							<input type="text" name="selection_element" value="" class="form-control" placeholder="Элемент списка" aria-label="Элемент списка">
							<div class="input-group-append">
								<button class="btn btn-success add_selection_element" type="button">Добавить</button>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row mt-2" id="selection_custom">
				</div>
			</div>
		</div>
	</div>
</div>
@endsection