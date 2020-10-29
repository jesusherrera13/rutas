<!-- Modal -->

<style type="text/css">

</style>
<div class="modal fade" id="modal-registro" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="contacto-modal-label" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="contacto-modal-label">Distrito</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-12">

						<form id="form-registro" action="" method="post" enctype="multipart/form-data">

							@csrf

							<input type="hidden" name="id" id="id">
							<input type="hidden" name="id_seccion" id="id_seccion">
							<input type="hidden" name="id_pais" id="id_pais">
							<input type="hidden" name="id_estado" id="id_estado">
							<input type="hidden" name="id_municipio" id="id_municipio">
							<input type="hidden" name="id_asentamiento" id="id_asentamiento">
							<input type="hidden" name="id_referente" id="id_referente">
							
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" id="contacto-tab" data-toggle="tab" href="#contacto" role="tab" aria-controls="contacto" aria-selected="true">Contacto</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="generales-tab" data-toggle="tab" href="#generales" role="tab" aria-controls="generales" aria-selected="false">Generales</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
								</li>
							</ul>
							<div class="tab-content pt-1" id="myTabContent">
								<div class="tab-pane fade show active" id="contacto" role="tabpanel" aria-labelledby="contacto-tab">
									
									<div class="form-row">
										<div class="form-group col-md-4 m-0">
											<label for="nombre">Nombre</label>
									        <input type="text" name="nombre" id="nombre" class="form-control form-control-sm">
										</div>
										<div class="form-group col-md-4 m-0">
											<label for="apellido1">Apellido 1</label>
									        <input type="text" name="apellido1" id="apellido1" class="form-control form-control-sm">
										</div>
										<div class="form-group col-md-4 m-0">
											<label for="apellido2">Apellido 2</label>
									        <input type="text" name="apellido2" id="apellido2" class="form-control form-control-sm">
										</div>
									</div>

									<div class="form-row p-0">
										<div class="form-group col-md-6 m-0">
											<label for="nombre">Sección</label>
											<div id="d-no_seccion">
												<input type="text" id="no_seccion" class="typeahead form-control form-control-sm" placeholder="Sección">
											</div>
										</div>
										<div class="form-group col-md-6 m-0">
											<label for="apellido1">Casilla</label>
											<select name="id_casilla" id="id_casilla" class="custom-select custom-select-sm">

			        						</select>
										</div>
									</div>

									<div class="form-row">
										<div class="form-group col-md-6 m-0">
											<label for="apellido2">Asentamiento</label>
											<div id="d-asentamiento">
												<input type="text" id="asentamiento_" class="typeahead form-control form-control-sm" placeholder="Asentamientos de MEX">
											</div>
										</div>
										<div class="form-group col-md-6 m-0">
											<label for="direccion">Dirección</label>
									        <input type="text" name="direccion" id="direccion" class="form-control form-control-sm">
										</div>
									</div>

									<div class="form-row">
										<div class="form-group col-md-4 m-0">
											<label for="id_coordinador">Coordinador</label>
											<select name="id_coordinador" id="id_coordinador" class="custom-select custom-select-sm">

			        							<option value=""></option>
			        							@foreach($coordinadores as $row)

			        								@php

													$tmp = explode(' ', $row->contacto);

													$coordinador = $tmp[0]." ".$tmp[2];

													@endphp


			        								<option value="{{$row->id_contacto}}">{{$coordinador}}</option>
			        							@endforeach
			        						</select>
										</div>
										<div class="form-group col-md-4 m-0">
											<label for="referente">Referente</label>
											<div id="d-referente">
												<input type="text" id="referente" class="typeahead form-control form-control-sm" placeholder="Referente">
											</div>
										</div>
										<div class="form-group col-md-4 m-0">
											<label for="status">Status</label>
											<select name="status" id="status" class="custom-select custom-select-sm">

			        							<option value="1">Activo</option>
			        							<option value="0">Inactivo</option>
			        						</select>
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="generales" role="tabpanel" aria-labelledby="generales-tab">
									
									<!-- <div class="form-group row">
									    <label for="email" class="col-sm-4 col-form-label">Email:</label>
									    <div class="col-sm-8">
									        <input type="email" name="email" id="email" class="form-control form-control-sm">
									    </div>
									</div> -->

									<div class="row">
										<div class="col-sm-3">
											Teléfono:
										</div>
									    <div class="col-sm-6">
									        <input type="text" id="no_telefono" class="form-control form-control-sm">
									    </div>
									    <div class="col-sm-3">
									    	<div class="d-flex justify-content-end">
												<button class="btn btn-primary btn-sm btn-agregar" clase="telefonos">
													<i class="fas fa-plus-circle"></i>
												</button>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<table id="tbl-telefonos" class="table table-bordered table-striped table-sm">
											<thead>
												<tr>
													<th>Teléfono</th>
													<th></th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>

									<div class="row">
										<div class="col-sm-3">
											Email:
										</div>
									    <div class="col-sm-6">
									        <input type="text" id="email" class="form-control form-control-sm">
									    </div>
									    <div class="col-sm-3">
									    	<div class="d-flex justify-content-end">
												<button class="btn btn-primary btn-sm btn-agregar" clase="emails">
													<i class="fas fa-plus-circle"></i>
												</button>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<table id="tbl-emails" class="table table-bordered table-striped table-sm">
											<thead>
												<tr>
													<th>Email</th>
													<th></th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>

								</div>
								<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
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
