<!-- Modal -->
<div class="modal fade" id="modal-registro" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ruta-modal-label" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="ruta-modal-label">Rutas</h5>
				<div>
					<button id="btn-casillas" class="btn btn-sm btn-primary mr-1">
						<i class="fas fa-tasks"></i> Casillas
					</button>
					<button id="btn-guardar" class="btn btn-sm btn-primary">
						<i class="fas fa-save"></i> Guardar cambios
					</button>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>

			<div class="modal-body">
				
				<form id="form-registro" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="id_rg" id="id_rg">

					<div class="row">
						<div class="col-5">
							<div class="form-group">
								<label for="id_distrito_federal">Distrito Federal</label>
								<select name="id_distrito_federal" id="id_distrito_federal" class="custom-select custom-select-sm">
									<option value=""></option>
									@foreach($distritos_federales as $k => $row)
										<option value="{{ $row->id}}" no_distrito="{{ $row->no_distrito}}">{{ $row->descripcion}}</option>
									@endforeach
								</select>
							</div>

							<div class="form-group">
								<label for="id_distrito_local">Distrito Local</label>
								<select name="id_distrito_local" id="id_distrito_local" class="custom-select custom-select-sm">
									<option value=""></option>
								</select>
							</div>

							<div class="form-group">
								<label for="descripcion">Descripción</label>
								<input type="text" name="descripcion" id="descripcion" class="form-control form-control-sm">
							</div>

							<div class="form-group">
								<label for="representante_general">Representante General</label>
								<div id="d-representante_general">
									<input type="text" id="representante_general" class="typeahead form-control form-control-sm" placeholder="Representante General">
								</div>
							</div>

							<div class="form-group">
								<label for="status">Status</label>
								<select name="status" id="status" class="custom-select custom-select-sm">
									<option value="1">Activo</option>
									<option value="0">Inactivo</option>
								</select>
							</div>
						</div>
						<div class="col-7">
							<table id="tbl-ruta_casillas" class="table table-bordered table-striped table-sm order-column" style="width:100%">
								<thead>
									<tr>
										<th>Ruta</th>
										<th></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div> 
