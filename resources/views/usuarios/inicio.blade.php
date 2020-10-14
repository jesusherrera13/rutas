@extends('inicio.inicio')

@section('contenido')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<form id="form">
					@csrf
					<button id="btn-nuevo" class="btn btn-primary">Nuevo</button>
					<button id="btn-buscar" class="btn btn-primary">Buscar</button>
				</form>
			</div>
			<div class="card-body">
	  			<table id="tbl-data" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Email</th>
							<th></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
	  </div>
	  <!-- /.card-body -->
	<!-- /.card -->
</div>

@include('usuarios.modals.modal')
@include('modals.modals')
@include('modals.spinner')

@stop

@section('scripts')

<script src="js/usuarios/js.js"></script>

@stop