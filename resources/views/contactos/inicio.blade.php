@extends('inicio.inicio')

@section('contenido')

@section('styles')

<link rel="stylesheet" href="css/contactos/css.css">

@stop

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

						<div class="form-group col-md-3">
							<label for="id_municipio_">Municipio</label>
							<select name="id_municipio_" id="id_municipio_" class="custom-select custom-select-sm">
					        	<option value=""></option>
								@foreach($municipios as $k => $row)
									<option value="{{ $row->id_municipio}}">
										{{ $row->descripcion}}
									</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-md-3">
							<label for="id_asentamiento_">Asentamiento</label>
							<select name="id_asentamiento_" id="id_asentamiento_" class="custom-select custom-select-sm">
					        	<option value=""></option>
								@foreach($asentamientos as $k => $row)
									<option value="{{ $row->id}}" id_municipio="{{ $row->id_municipio}}">
										{{ $row->descripcion}}, {{ $row->municipio}}
									</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-md-3">
							<label for="status_">Status</label>
							<select name="status_" id="status_" class="custom-select custom-select-sm">
					        	<option value=""></option>
					        	<option value="1" selected>Activo</option>
					        	<option value="0">Inactivo</option>
							</select>
						</div>
					</div>
					
				</form>
			</div>
			<div class="card-body">
	  			<table id="tbl-data" class="table table-bordered table-striped table-sm order-column" style="width:100%">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Casilla</th>
							<th>Sección</th>
							<th>Teléfono</th>
							<th>Email</th>
							<th>Dto. Fed.</th>
							<th>Dto. Loc.</th>
							<th>Asentamiento</th>
							<th>Dirección</th>
							<th>Referente</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Nombre</td>
							<td>Sección</td>
							<td>Sección</td>
							<td>Teléfono</td>
							<td>Email</td>
							<td>Distrito Federal</td>
							<td>Distrito Local</td>
							<td>Asentamiento</td>
							<td>Dirección</td>
							<td>Referente</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
	  </div>
	  <!-- /.card-body -->
	<!-- /.card -->
</div>

@include('contactos.modals.modal')
@include('modals.modals')
@include('modals.spinner')

@stop

@section('scripts')

<script src="js/contactos/js.js"></script>

@stop