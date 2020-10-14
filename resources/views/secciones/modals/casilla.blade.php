<!-- Modal -->
<div class="modal fade" id="modal-casilla" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="casilla-label" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="casilla-label">Casilla</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<form id="form-casilla" class="form-horizontal" action="" method="post" enctype="multipart/form-data">

							@csrf

							<div class="form-group">
							    <label for="id_tipo_casilla">Tipo:</label>
						        <select id="id_tipo_casilla" class="custom-select custom-select-sm">
						        	<option value=""></option>
        							@foreach($casillas_tipos as $k => $row)
        								<option value="{{ $row->id_tipo_casilla}}">{{ $row->descripcion}}</option>
        							@endforeach
        						</select>
							</div>

							<div class="row">
							    <div class="col-sm-12">
							        <button id="btn-add" class="btn btn-primary btn-sm btn-block">
							        	<i class="fas fa-plus-circle"></i>
							        </button>
							    </div>
							</div>

							<div class="row mt-3">
							    <div class="col-sm-12">
									<table id="tbl-add" class="table table-bordered table-striped table-sm">
										<thead>
											<tr>
												<th>Casilla</th>
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

			<div class="modal-footer">
				<button class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fas fa-times"></i> Cerrar
				</button>
				<button id="btn-grabar-casillas" class="btn btn-sm btn-primary">
					<i class="fas fa-save"></i> Grabar cambios
				</button>
			</div>
		</div>
	</div>
</div> 
