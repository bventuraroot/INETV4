<html>

<head>
	<style>
		/** Define the margins of your page **/
		@page {
			margin: 100px 25px;
		}

		header {
			position: fixed;
			top: -60px;
			left: 0px;
			right: 0px;
			height: 70px;
			/** Extra personal styles **/

			color: black;
			text-align: center;
			line-height: 35px;
			border: 1pt;

		}

		footer {
			position: fixed;
			bottom: -60px;
			left: 0px;
			right: 0px;
			height: 50px;

			/** Extra personal styles **/

			color: black;
			text-align: center;
			line-height: 35px;
		}
		table {
			font-size: 80%;
		}
		
	</style>
</head>

<body>
<header>
	{{$titulo}}
	<br>
	<strong>Nombre del Contribuyente:</strong>{{$nombre_empresa}}&nbsp;&nbsp;&nbsp;
	<strong>N.R.C.:</strong>{{$nrc}}&nbsp;&nbsp;&nbsp;<strong>MES:</strong>{{nombre_mes($mes)}}&nbsp;&nbsp;&nbsp;<strong>AÑO:</strong>{{$ano_aplicacion}}
</header>

<br>
<br>
<table width="750">
<thead>
<tr>
<th width="20" align="center">No.</th>
<th width="30"></th>
<th width="30" align="center">No. De</th>
<th width="130"></th>
<th width="60" align="center">No. De</th>
<th colspan="3"  align="center">Ventas Propias</th>
<th width="50" align="center">Retencion</th>
<th colspan="3" align="center">Vta Cta. Terceros</th>
<th width="80" align="center"></th>
</tr>
<tr>
<th align="center">Corr.</th>
<th align="center">Dia</th>
<th align="center">Compro.</th>
<th align="center">Nombre del Cliente</th>
<th align="center">Registro</th>
<th align="center" width="50">Exentas</th>
<th width="70" align="center">Gravadas</th>
<th align="center">IVA</th>
<th  align="center">IVA</th>
<th align="center" width="50">Exentas</th>
<th width="70" align="center">Gravadas</th>
<th align="center" width="50">IVA</th>
<th width="80" align="center">TOTAL</th>
</tr>
<thead>
<tbody>

@foreach ($datos as $venta ) {


<tr>
<td align="center">{{$venta->corr}}</td>
<td align="center">{{$venta->dia}}</td>
<td>{{$venta->numero}}</td>
<td>{{$venta->nombre}}</td>
<td>{{$venta->nrc}}</td>
<td align="right">{{$venta->exentas}}</td>
<td align="right">{{$venta->gravadas}}</td>
<td align="right">{{$venta->iva}}</td>
<td align="right">{{$venta->iva_retenido}}</td>
<td align="right">{{$venta->exentas_terceros}}</td>
<td align="right">{{$venta->gravadas_terceros}}</td>
<td align="right">{{$venta->iva_terceros}}</td>
<td align="right">{{$venta->total}}</td>
</tr>
@endforeach                                                                                                                      
</tbody>
</table>
<footer>


</footer>
<script type="text/php">
	if (isset($pdf))
    {
      $font = null;
      $pdf->page_text(720, 550, "Pagina {PAGE_NUM} de {PAGE_COUNT}", $font, 9, array(0, 0, 0));
    }
</script>
</body>

</html>

