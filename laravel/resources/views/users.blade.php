@extends('layouts.app')

@section('content')

@if (count($users) > 0)
<div class="panel panel-default">
	<div class="panel-heading">Пользователи</div>
	<div class="panel-body">
		<table class="table table-striped task-table">
			<thead>
				<th>id</th>
				<th>ФИО</th>
				<th>email</th>
			</thead>
			<tbody>
				@foreach ($users as $user)
				<tr>
					<td class="table-text">
						<div>{{ $user->ID }}</div>
					</td>
					<td class="table-text">
						<div>{{ $user->FIO }}</div>
					</td>
					<td class="table-text">
						<div>{{ $user->email }}</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection