<!-- Modal -->
<div class="modal fade" id="modal-registro" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Distrito</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<div class="row">

					<div class="col-12">
						<form id="form-registro" class="form-horizontal" action="" method="post" enctype="multipart/form-data">

							<input type="hidden" name="id" id="id">
							
							@csrf

							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
										Generales
									</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="accesos-tab" data-toggle="tab" href="#accesos" role="tab" aria-controls="accesos" aria-selected="false">
										Accesos a Distritos
									</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="accesos-modulos-tab" data-toggle="tab" href="#accesos-modulos" role="tab" aria-controls="accesos-modulos" aria-selected="false">
										Acceso a Módulos
									</a>
								</li>
							</ul>
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
								<div class="form-group row">
    
									<label for="name" class="col-sm-4 col-form-label">Nombre:</label>
									<div class="col-sm-8">
										<input type="text" name="name" id="name" class="form-control form-control-sm">
									</div>
								</div>

								<div class="form-group row">

									<label for="email" class="col-sm-4 col-form-label">Email:</label>
									<div class="col-sm-8">
										<input type="email" name="email" id="email" class="form-control form-control-sm">
									</div>
								</div>

								<div class="form-group row">
									<label for="password" class="col-sm-4 col-form-label">Contraseña:</label>
									<div class="col-sm-8">
										<input type="password" name="password" id="password" class="form-control form-control-sm">
									</div>
								</div>

								<div class="form-group row">
									<label for="password_" class="col-sm-4 col-form-label">Confirmar contraseña:</label>
									<div class="col-sm-8">
										<input type="password" id="password_" class="form-control form-control-sm">
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
								<div class="tab-pane fade" id="accesos" role="tabpanel" aria-labelledby="accesos-tab">
									<div class="row">
										<div class="col-6">
											<table id="tbl-distritos-federales" class="table table-bordered table-striped table-sm order-column" style="width:100%">
												<thead>
													<tr>
														<th>Distrito Federal</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
												@foreach($distritos_federales as $row)
													<tr>
														<td>{{ $row->descripcion }}</td>
														<td>
														<input type="checkbox" value="{{$row->id}}">
														</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										</div>
										<div class="col-6">
											<table id="tbl-distritos-locales" class="table table-bordered table-striped table-sm order-column" style="width:100%">
												<thead>
													<tr>
														<th>Distrito Local</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
												@foreach($distritos_locales as $row)
													<tr>
														<td>{{ $row->descripcion }}</td>
														<td>
															<input type="checkbox" value="{{$row->id}}">
														</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="accesos-modulos" role="tabpanel" aria-labelledby="accesos-modulos-tab">
									<div class="row">
										<div class="col-12">
											<table id="tbl-modulos" class="table table-bordered table-striped table-sm order-column" style="width:100%">
												<thead>
													<tr>
														<th>Módulos</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
												@foreach($modulos as $row)
													<tr>
														<td>{{ $row->descripcion }}</td>
														<td>
														<input type="checkbox" value="{{$row->id}}">
														</td>
													</tr>
												@endforeach
												</tbody>
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
