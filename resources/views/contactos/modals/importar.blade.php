<!-- Modal -->
<div class="modal fade" id="modal-importar" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Importar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<div class="row mb-1">

					<div class="col-8">

						<form id="form-importar" class="form-horizontal" action="" method="post" enctype="multipart/form-data">

							@csrf

				        	<input name="archivo" id="archivo" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
							
						</form>
					</div>

					<div class="col-4">
						<button id="btn-verificar" type="button" class="btn btn-secondary btn-sm">
							<i class="fas fa-check-double"></i> Verificar
						</button>
						<button id="btn-grabar-importar" type="button" class="btn btn-sm btn-verde">
							<i class="fas fa-cloud-upload-alt"></i> Importar
						</button>
					</div>
				</div>

				<div class="row">

					<div class="col-12">
						<table id="tbl-importar" class="table table-responsive-sm table-bordered table-sm display responsive nowrap data-table verde" style="width:100%">
							<thead>
								<tr>
									<th></th>
									<th>Seccion</th>
									<th>Nombre</th>
									<th>Apellido 1</th>
									<th>Apellido 2</th>
									<th>Teléfono</th>
									<th>Referente</th>
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
