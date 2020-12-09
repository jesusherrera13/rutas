<!-- Modal -->
<div class="modal fade" id="modal-registro" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Casillas</h5>
				
				<div>
					<button id="btn-guardar" class="btn btn-sm btn-primary">
						<i class="fas fa-save"></i>
					</button>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>

			<div class="modal-body">

				<div class="row">

					<div class="col-12">
						<form id="form-registro" class="form-horizontal" action="" method="post" enctype="multipart/form-data">

							<input type="hidden" name="id" id="id">
							<input type="hidden" name="id_seccion" id="id_seccion">
							<input type="hidden" name="id_tipo_casilla" id="id_tipo_casilla">
							<input type="hidden" name="no_casilla" id="no_casilla">
							<input type="hidden" name="no_distrito_federal" id="no_distrito_federal">
							<input type="hidden" name="no_distrito_local" id="no_distrito_local">
							<input type="hidden" name="id_asentamiento" id="id_asentamiento">
							
							@csrf

							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" id="ruta-tab" data-toggle="tab" href="#ruta" role="tab" aria-controls="ruta" aria-selected="true">Casilla</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="casillas-tab" data-toggle="tab" href="#casillas" role="tab" aria-controls="casillas" aria-selected="false">Representantes</a>
								</li>
							</ul>

							<div class="tab-content pt-1" id="myTabContent">
								<div class="tab-pane fade show active" id="ruta" role="tabpanel" aria-labelledby="ruta-tab">
									
									<div class="row">
										<div class="col-6">

											<div class="form-group row">
		    
											    <label for="asentamiento" class="col-sm-4 col-form-label">Asentamiento:</label>
											    <div class="col-sm-8">
											    	<div id="d-asentamiento">
														<input type="text" id="asentamiento" class="typeahead form-control form-control-sm" placeholder="Asentamientos de MEX">
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
										<div class="col-12">
											<div class="row">
												<div class="col-6">
													<table id="tbl-representantes" class="table table-bordered table-striped table-sm order-column" style="width:100%">
														<thead>
															<tr>
																<th>Nombre</th>
																<th></th>
																<th></th>
															</tr>
														</thead>
														<tbody></tbody>
													</table>
												</div>
												<div class="col-6">
													<table id="tbl-contactos" class="table table-bordered table-striped table-sm order-column" style="width:100%">
														<thead>
															<tr>
																<th>Contactos</th>
																<th></th>
															</tr>
														</thead>
														<tbody></tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>


			</div>

			<!-- <div class="modal-footer">
				<button class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fas fa-times"></i> Cerrar
				</button>
				<button id="btn-eliminar" class="btn btn-danger btn-sm">
					<i class="fas fa-trash-alt"></i> Eliminar
				</button>
				<button id="btn-guardar" class="btn btn-sm btn-primary">
					<i class="fas fa-save"></i> Guardar cambios
				</button>
			</div> -->
		</div>
	</div>
</div> 
