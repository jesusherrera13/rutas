<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style type="text/css">
    	
	.table {
		border-collapse: collapse;

		margin-bottom: 10px;
	}

	.table th {

		background-color: #A5A5A5A5;
	}

	.table th, .table td {

		border: 1px solid;
		font-size: 12px;
		padding: 2px;
	}

	#tbl-info > thead > tr > :nth-child(1) {

		width: 100px;
	}

	#tbl-info > thead > tr > :nth-child(2) {

		width: 45px;
	}

	#tbl-info > thead > tr > :nth-child(3) {

		width: 45px;
	}

	#tbl-info > thead > tr > :nth-child(4) {

		width: 200px;
	}

	#tbl-info > thead > tr > :nth-child(5) {

		width: 200px;
	}

	#tbl-data > thead > tr > :nth-child(1) {

		width: 200px;
	}

	#tbl-data > thead > tr > :nth-child(2) {

		width: 300px;
	}

	#tbl-data > thead > tr > :nth-child(3) {

		width: 100px;
	}

	.fecha {

		font-size: 10px;
		text-align: right;
	}

	.casilla {

		font-size: 18px;
		font-weight: bold;
	}
    </style>
</head>
<body>
    <div class="fecha">{{$fecha}} {{$hora}}</div>
    <h3>Información de RC'S</h3>

    <table id="tbl-info" class="table">
    	<thead>
			<tr>
				<th>Ruta</th>
				<th>DF</th>
				<th>DL</th>
				<th>CRG</th>
				<th>RG</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{ $data[0]->descripcion }}</td>
				<td>{{ $data[0]->no_distrito_federal }}</td>
				<td>{{ $data[0]->no_distrito_local }}</td>
				<td>CRG</td>
				<td>{{ $data[0]->representante_general }}</td>
			</tr>
		</tbody>
    </table>

	@foreach($data[0]->ruta_casillas as $row)

	<div class="casilla">
		{{ $row->casilla }}, {{ $row->asentamiento_corto }}
	</div>
    
    <table id="tbl-data" class="table table-bordered table-striped">
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