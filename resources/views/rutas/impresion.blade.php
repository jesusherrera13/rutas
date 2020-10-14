<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style type="text/css">
    	
	.table {
		border-collapse: collapse;

		margin-bottom: 10px;
	}

	.table th, .table td {

		font-size: 12px;
		padding: 2px;
	}

	.table > thead > tr > :nth-child(1) {

		width: 200px;
	}

	.table > thead > tr > :nth-child(2) {

		width: 300px;
	}

	.table > thead > tr > :nth-child(3) {

		width: 100px;
	}

	.fecha {

		font-size: 10px;
		text-align: right;
	}

    </style>
</head>
<body>
    <div class="fecha">{{$fecha}} {{$hora}}</div>

    <h3>INFORMACIÓN DE RC'S</h3>
    <div>{{ $data[0]->descripcion }}</div>
    <h3>Distrito Federal: {{ $data[0]->no_distrito_federal }}</h3>
    <h3>Distrito Local: {{ $data[0]->no_distrito_local }}</h3>
    <h3>CRG: </h3>
    <h3>RG: {{ $data[0]->representante_general }}</h3>

	@foreach($data[0]->ruta_casillas as $row)

	<div>
		{{ $row->casilla }}, {{ $row->asentamiento_corto }}
	</div>
    
    <table id="tbl-data" class="table table-bordered table-striped" border="1">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Domicilio</th>
				<th>Teléfono</th>
			</tr>
		</thead>
		<tbody>
		@foreach($row->rcs as $row_)
			<tr>
				<td>{{ $row_->contacto }}</td>
				<td>{{ $row_->direccion }}</td>
				<td>{{ $row_->no_telefono }}</td>
			</tr>
		@endforeach

		</tbody>
	</table>
	@endforeach
</body>
</html>