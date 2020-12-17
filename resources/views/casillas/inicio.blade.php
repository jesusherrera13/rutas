@extends('inicio.inicio')

@section('contenido')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">

				@php
				
				$acc_impresion = "";

				foreach($accesos_impresion as $row) {

					if($acc_impresion) $acc_impresion.= ";";

					$acc_impresion.= $row->id_formato;
				}

				@endphp

				<form id="form" acc_impresion="{{$acc_impresion}}">

					@csrf

					<div class="row">
						<div class="col-md-12">
							<div class="d-flex justify-content-end">
								<button id="btn-nuevo" class="btn btn-primary btn-sm mr-1 btn-formulario">
									<i class="far fa-file"></i> Nuevo
								</button>
								<button id="btn-buscar" class="btn btn-primary btn-sm btn-formulario">
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
							<th>Casilla</th>
							<th>RCs</th>
							<th>DF</th>
							<th>DL</th>
							<th>Ruta</th>
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

@include('casillas.modals.modal')
@include('casillas.modals.filtro')
@include('modals.spinner')
@include('modals.modals')

@stop

@section('scripts')

<script src="js/casillas/js.js"></script>

@stop