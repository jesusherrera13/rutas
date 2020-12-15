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
						<div class="col-md-6">
							<a class="btn btn-primary btn-sm btn-formulario" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
								Filtros
							</a>
						</div>
						<div class="col-md-6">
							<div class="d-flex justify-content-end">
								<button id="btn-nuevo" class="btn btn-primary btn-sm mr-1 btn-formulario">
									<i class="far fa-file"></i> Nuevo
								</button>
								@if(Auth::user()->id == 1)
								<button id="btn-importar" type="button" class="btn btn-mx btn-sm mr-1 btn-formulario">
									<i class="fas fa-upload"></i> Importar
								</button>
								@endif
								<button id="btn-buscar" class="btn btn-primary btn-sm btn-formulario">
									<i class="fas fa-search"></i> Buscar
								</button>
							</div>
						</div>
					</div>

					<div class="collapse" id="collapseExample">
						<div class="card card-body">
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
									<label for="id_coordinador_">Coordinador</label>
									<select name="id_coordinador_" id="id_coordinador_" class="custom-select custom-select-sm">
							        	<option value=""></option>
										@foreach($coordinadores as $k => $row)
											<option value="{{ $row->id_contacto}}">
												{{ $row->contacto_corto }}
											</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-md-3">
									<label for="id_referente_">Referente</label>
									<select name="id_referente_" id="id_referente_" class="custom-select custom-select-sm">
							        	<option value=""></option>
										@foreach($referentes as $k => $row)

											<option value="{{ $row->id_contacto}}">
												{{ $row->contacto_corto}}
											</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-md-3">
									<label for="status_">Status</label>
									<select name="status_" id="status_" class="custom-select custom-select-sm">
							        	<option value=""></option>
							        	<option value="1" selected>Activo</option>
							        	<option value="0">Inactivo</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body">
	  			<table id="tbl-data" class="table table-striped table-sm order-column datatable" style="width:100%">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Casilla</th>
							<th>Sección</th>
							<th>Teléfono</th>
							<th>Clave de Elector</th>
							<th>DF</th>
							<th>DL</th>
							<th>Asentamiento</th>
							<th>Dirección</th>
							<th>Referente</th>
							<th>Coordinador</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
	  </div>
	  <!-- /.card-body -->
	<!-- /.card -->
</div>

@include('contactos.modals.modal')
@include('contactos.modals.importar')
@include('modals.modals')
@include('modals.spinner')

@stop

@section('scripts')

<script src="js/contactos/js.js"></script>

@stop