@extends('inicio.inicio')

@section('contenido')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<form id="form">

					@csrf
					
					<div class="row">
						<div class="col-md-12">
							<div class="d-flex justify-content-end">
								<button id="btn-nuevo" class="btn btn-primary btn-sm mr-1">
									<i class="far fa-file"></i> Nuevo
								</button>
								<button id="btn-buscar" class="btn btn-primary btn-sm">
									<i class="fas fa-search"></i> Buscar
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body">
	  			<table id="tbl-data" class="table table-bordered table-striped table-sm order-column" style="width:100%">
					<thead>
						<tr>
							<th>Descripción</th>
							<th>URL</th>
							<th>Icon</th>
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

@include('modulos.modals.modal')
@include('modals.spinner')
@include('modals.modals')

@stop

@section('scripts')

<script src="js/modulos/js.js"></script>

@stop