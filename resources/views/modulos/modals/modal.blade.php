<!-- Modal -->
<div class="modal fade" id="modal-registro" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Módulo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<div class="row">

					<div class="col-12">
						<form id="form-registro" class="form-horizontal" action="" method="post" enctype="multipart/form-data">

							<input type="hidden" name="id" id="id">
							<input type="hidden" name="row_id" id="row_id">
							<input type="hidden" name="DT_RowIndex" id="DT_RowIndex">
							
							@csrf

							<div class="form-group row">
    
							    <label for="descripcion" class="col-sm-4 col-form-label">Descripción:</label>
							    <div class="col-sm-8">
							        <input type="text" name="descripcion" id="descripcion" class="form-control form-control-sm">
							    </div>
							</div>

							<div class="form-group row">
							    <label for="url" class="col-sm-4 col-form-label">URL:</label>
							    <div class="col-sm-8">
							        <input type="text" name="url" id="url" class="form-control form-control-sm" min="0">
							    </div>
							</div>

							<div class="form-group row">
							    <label for="icon" class="col-sm-4 col-form-label">Icon:</label>
							    <div class="col-sm-8">
							        <input type="text" name="icon" id="icon" class="form-control form-control-sm" min="0">
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
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fas fa-times"></i> Cerrar
				</button>
				<button id="btn-eliminar" class="btn btn-danger btn-sm">
					<i class="fas fa-trash-alt"></i> Eliminar
				</button>
				<button id="btn-grabar" class="btn btn-sm btn-primary">
					<i class="fas fa-save"></i> Grabar cambios
				</button>
			</div>
		</div>
	</div>
</div> 
