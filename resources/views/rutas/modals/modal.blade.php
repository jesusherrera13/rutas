<!-- Modal -->
<div class="modal fade" id="modal-registro" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ruta-modal-label" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="ruta-modal-label">Rutas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<div class="row">

					<div class="col-12">
						<form id="form-registro" class="form-horizontal" action="" method="post" enctype="multipart/form-data">

							<input type="hidden" name="id" id="id">
							<input type="hidden" name="id_rg" id="id_rg">
							
							@csrf

							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" id="ruta-tab" data-toggle="tab" href="#ruta" role="tab" aria-controls="ruta" aria-selected="true">Ruta</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="casillas-tab" data-toggle="tab" href="#casillas" role="tab" aria-controls="casillas" aria-selected="false">Casillas</a>
								</li>
							</ul>

							<div class="tab-content pt-1" id="myTabContent">
								<div class="tab-pane fade show active" id="ruta" role="tabpanel" aria-labelledby="ruta-tab">
									
									<div class="row">
										<div class="col-6">
											<div class="form-group row">
											    <label for="id_distrito_federal" class="col-sm-4 col-form-label">Distrito Federal:</label>
											    <div class="col-sm-8">
											        <select name="id_distrito_federal" id="id_distrito_federal" class="custom-select custom-select-sm">
											        	<option value=""></option>
				            							@foreach($distritos_federales as $k => $row)
				            								<option value="{{ $row->id}}" no_distrito="{{ $row->no_distrito}}">{{ $row->descripcion}}</option>
				            							@endforeach
				            						</select>
											    </div>
											</div>

											<div class="form-group row">
											    <label for="id_distrito_local" class="col-sm-4 col-form-label">Distrito Local:</label>
											    <div class="col-sm-8">
											        <select name="id_distrito_local" id="id_distrito_local" class="custom-select custom-select-sm">
											        	<option value=""></option>
				            						</select>
											    </div>
											</div>

											<div class="form-group row">
											    <label for="descripcion" class="col-sm-4 col-form-label">Descripción:</label>
											    <div class="col-sm-8">
											        <input type="text" name="descripcion" id="descripcion" class="form-control form-control-sm">
											    </div>
											</div>

											<div class="form-group row">
											    <label for="representante_general" class="col-sm-4 col-form-label">RG:</label>
											    <div class="col-sm-8">
											    	<div id="d-representante_general">
														<input type="text" id="representante_general" class="typeahead form-control form-control-sm" placeholder="Representante General">
													</div>
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
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="casillas" role="tabpanel" aria-labelledby="casillas-tab">
									<div class="row">
										<div class="col-6">
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
										<div class="col-6">
											<table id="tbl-casillas" class="table table-bordered table-striped table-sm order-column" style="width:100%">
												<thead>
													<tr>
														<th>Casillas</th>
														<th></th>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>

				
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fas fa-times"></i> Cerrar
				</button>
				<!-- <button id="btn-eliminar" class="btn btn-danger btn-sm">
					<i class="fas fa-trash-alt"></i> Eliminar
				</button> -->
				<button id="btn-guardar" class="btn btn-sm btn-primary">
					<i class="fas fa-save"></i> Guardar cambios
				</button>
			</div>
		</div>
	</div>
</div> 
