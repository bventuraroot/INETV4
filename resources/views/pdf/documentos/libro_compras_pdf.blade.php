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
			width: 100%;
			border-collapse: collapse;
			font-size: 75%;
		}
		table th{
			border: 1px solid;
		}
	</style>
</head>

<body>
	<header>
	<strong>{{$titulo}}</strong>
	<br>
	<strong>Nombre del Contribuyente:</strong>{{$nombre_empresa}}&nbsp;&nbsp;&nbsp;
			<strong>N.R.C.:</strong>{{$nrc}}&nbsp;&nbsp;&nbsp;<strong>MES:</strong>{{$mes}}&nbsp;&nbsp;&nbsp;<strong>AÑO:</strong>{{$ano_aplicacion}}
	
	</header>
<br>
<br>
<div class="row">
<table width="750">
<thead>
<tr style="border: 1px;">
<th width="10" align="center" rowspan="2">No.<br>CORR.</th>
<th width="40" align="center" rowspan="2">FECHA<br>EMISION</th>
<th width="40" align="center" rowspan="2">NUMERO DE<br>DOCUMENTO</th>
<th width="40" rowspan="2">NRC</th>
<th width="80" rowspan="2">NIT</th>
<th width="120" rowspan="2">NOMBRE DEL<br>PROVEEDOR</th>
<th colspan="2" align="center">EXENTAS</th>
<th colspan="3" align="center">GRAVADAS</th>
<th width="70" align="center" rowspan="2">TOTAL<br>COMPRAS</th>
<th width="40" align="center" rowspan="2">IMP.<br>RETENIDO</th>
</tr>
<tr>
<th width="30" align="center" >INTER.</th>
<th width="30" align="center">EXPORT.</th>
<th width="70" align="center">INTERNAS</th>
<th width="40" align="center">IMPORT.</th>
<th width="50" align="center">CR.FISCAL</th>
</tr>
</thead>
@php
$exenta = 0.00;
$gravada = 0.00;
$iva = 0.00;
$total = 0.00;
$iva_retenido = 0.00;

@endphp
<tbody>
@foreach ($datos as $compra) 


<tr>
<td>{{$compra->correlativo}}</td>
<td align="right">{{$compra->fecha}}</td>
<td align="right">{{$compra->numero}}</td>
<td align="right">{{$compra->nrc}}</td>
<td align="right">{{$compra->nit}}</td>
<td>{{$compra->razon_social}}</td>
<td align="right">{{$compra->exenta}}</td>
<td align="right">0.00</td>
<td align="right">{{$compra->gravada}}</td>
<td align="right">0.00</td>
<td align="right">{{$compra->iva}}</td>
<td align="right">{{number_format($compra->total,2)}}</td>
<td align="right">{{number_format($compra->retencion_iva,2)}}</td>
</tr>
@php
$exenta += $compra->exenta;
$gravada += $compra->gravada;
$iva += $compra->iva;
$total += $compra->total;
$iva_retenido += $compra->retencion_iva;
@endphp

@endforeach
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>TOTALES</td>
<td align="right"><?php echo number_format($exenta,2);?></td>
<td align="right">&nbsp;</td>
<td align="right"><?php echo number_format($gravada,2);?></td>
<td align="right">&nbsp;</td>
<td align="right"><?php echo number_format($iva,2);?></td>
<td align="right"><?php echo number_format($total,2);?></td>
<td align="right"><?php echo number_format($iva_retenido,2);?></td>
</tr>
</tbody>
</table>
</div>
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