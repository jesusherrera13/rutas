<!-- Modal -->
<div class="modal fade" id="modal-registro" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Coordinadores</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<div class="row">

					<div class="col-12">
						<form id="form-registro" class="form-horizontal" action="" method="post" enctype="multipart/form-data">

							<input type="hidden" name="id" id="id">
							<input type="hidden" name="id_tipo_casilla" id="id_tipo_casilla">
							<input type="hidden" name="no_casilla" id="no_casilla">
							<input type="hidden" name="no_distrito_federal" id="no_distrito_federal">
							<input type="hidden" name="no_distrito_local" id="no_distrito_local">
							<input type="hidden" name="id_asentamiento" id="id_asentamiento">
							
							@csrf

							<div class="row">
								<div class="col-12">
									<div class="row">
										<div class="col-6">
											<table id="tbl-referentes" class="table table-bordered table-striped table-sm order-column" style="width:100%">
												<thead>
													<tr>
														<th>Nombre</th>
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
