<!-- Modal -->
<div class="modal fade" id="modal-registro" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Secciones</h5>

				<div>
					<button id="btn-reset" class="btn btn-success btn-sm">
						<i class="far fa-file"></i> Nuevo
					</button>
					<button id="btn-grabar" class="btn btn-sm btn-primary">
						<i class="fas fa-save"></i> Guardar cambios
					</button>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-6">
						<form id="form-registro" class="form-horizontal" action="" method="post" enctype="multipart/form-data">

							<input type="hidden" name="id" id="id">
							
							@csrf

							<div class="form-group row">
							    <label for="no_seccion" class="col-sm-4 col-form-label">No. Sección:</label>
							    <div class="col-sm-8">
							        <input type="number" name="no_seccion" id="no_seccion" class="form-control form-control-sm" min="0">
							    </div>
							</div>

							<div class="form-group row">
							    <label for="id_distrito_federal" class="col-sm-4 col-form-label">Distrito Federal:</label>
							    <div class="col-sm-8">
							        <select name="id_distrito_federal" id="id_distrito_federal" class="custom-select custom-select-sm">
							        	<option value=""></option>
            							@foreach($distritos_federales as $k => $row)
            								<option value="{{ $row->id}}">{{ $row->descripcion}}</option>
            							@endforeach
            						</select>
							    </div>
							</div>

							<div class="form-group row">
							    <label for="id_distrito_local" class="col-sm-4 col-form-label">Distrito Local:</label>
							    <div class="col-sm-8">
							        <select name="id_distrito_local" id="id_distrito_local" class="custom-select custom-select-sm">
            							
            						</select>
							    </div>
							</div>

							<div class="form-group row">
							    <label for="status" class="col-sm-4 col-form-label">Status:</label>
							    <div class="col-sm-8">
							        <select name="status" id="status" class="custom-select custom-select-sm">

            							<option value="1">Activo</option>
            							<option value="0">Inactivo</option>
            						</select>
							    </div>
							</div>
						</form>
					</div>

					<div class="col-6">

						<div class="row">
							<div class="col-md-6">
								Casillas
							</div>
							<div class="col-md-6">
								<div class="d-flex justify-content-end">
									<button id="btn-agregar" class="btn btn-primary btn-sm mr-1">
										<i class="fas fa-plus-circle"></i> Agregar
									</button>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
					  			<table id="tbl-casillas" class="table-bordered table-striped table-sm display compact" style="width:100%">
									<thead>
										<tr>
											<th>Casilla</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- <div class="modal-footer">
				<button class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fas fa-times"></i> Cerrar
				</button>
				<button id="btn-reset" class="btn btn-success btn-sm">
					<i class="far fa-file"></i> Nuevo
				</button>
				<button id="btn-grabar" class="btn btn-sm btn-primary">
					<i class="fas fa-save"></i> Grabar cambios
				</button>
			</div> -->
		</div>
	</div>
</div> 
