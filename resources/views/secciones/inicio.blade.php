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
								<button id="btn-nuevo" class="btn btn-primary btn-sm mr-1 btn-formulario">
									<i class="far fa-file"></i> Nuevo
								</button>
								<button id="btn-buscar" class="btn btn-primary btn-sm btn-formulario">
									<i class="fas fa-search"></i> Buscar
								</button>
							</div>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-md-3">
							<label for="id_distrito_federal_">Distrito Federal</label>
							<select name="id_distrito_federal_" id="id_distrito_federal_" class="custom-select custom-select-sm">
					        	<option value=""></option>
								@foreach($distritos_federales as $k => $row)
									<option value="{{ $row->id}}">{{ $row->descripcion}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-md-3">
							<label for="id_distrito_local_">Distrito Local</label>
							<select name="id_distrito_local_" id="id_distrito_local_" class="custom-select custom-select-sm">
					        	<option value=""></option>
								@foreach($distritos_locales as $k => $row)
									<option value="{{ $row->id}}">{{ $row->descripcion}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body">
	  			<table id="tbl-data" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th>Sección</th>
							<th>Distrito Federal</th>
							<th>Distrito Local</th>
							<th>Responsable</th>
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

@include('secciones.modals.modal')
@include('secciones.modals.casilla')
@include('modals.modals')
@include('modals.spinner')

@stop

@section('scripts')

<script src="js/secciones/js.js"></script>

@stop