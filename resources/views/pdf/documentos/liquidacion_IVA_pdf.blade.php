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

		table td {
			margin-left: 10px;
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
<?php
if(! empty($datos)) {
?>

<div class="row">

<table style="width: 650px;">
<tbody>
<tr>
<td style="width: 300px;">
<h3><strong>VENTAS</strong></h3>
</td>
<td style="width: 145px;">&nbsp;</td>
<td style="width: 50px;">
<h3><strong>COMPRAS</strong></h3>
</td>
<td style="width: 69px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 300px;">&nbsp;</td>
<td style="width: 145px;">&nbsp;</td>
<td style="width: 150px;">&nbsp;</td>
<td style="width: 69px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 300px;"><strong>COMPROBANTE DE CREDITO FISCAL</strong></td>
<td style="width: 145px;" align="right">{{number_format($datos->CreditoFiscal,2)}}</td>
<td style="width: 150px;"><strong>GRAVADAS</strong></td>
<td style="width: 69px;" align="right">{{number_format($datos->Compras,2)}}</td>
</tr>
<tr>
<td style="width: 300px;"><strong>FACTURA DE CONSUMIDOR FINAL</strong></td>
<td style="width: 145px;" align="right">{{number_format($datos->FacturaConsumidor,2)}}</td>
<td style="width: 150px;"><strong>RETENCION IVA</strong></td>
<td style="width: 69px;" align="right">{{number_format($datos->RetencionIVA,2)}}</td>
</tr>
<tr>
<td style="width: 300px;"><strong>FACTURA DE EXPORTACION</strong></td>
<td style="width: 145px;" align="right">{{number_format($datos->Exportacion,2)}}</td>
<td style="width: 150px;">&nbsp;</td>
<td style="width: 69px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 300px;"><strong>DEBITO FISCAL</strong></td>
<td style="width: 145px;" align="right">{{number_format($datos->IVA,2)}}</td>
<td style="width: 150px;"><strong>CREDITO FISCAL</strong></td>
<td style="width: 69px;" align="right">{{number_format($datos->CrFiscal,2)}}</td>
</tr>
<tr>
	<td style="width: 300px;"><strong>VENTA NO SUJETAS A RENTA</strong></td>
	<td style="width: 145px;" align="right">{{number_format($datos->VentasNoSujetasRenta,2)}}</td>
	<td style="width: 150px;"><strong></strong></td>
	<td style="width: 69px;" align="right"></td>
	</tr>
<tr>
	<tr>
		<td style="width: 300px;"><strong>VENTA SUJETAS A RENTA</strong></td>
		<td style="width: 145px;" align="right">{{number_format($datos->VentasSujetasRenta,2)}}</td>
		<td style="width: 150px;"><strong></strong></td>
		<td style="width: 69px;" align="right"></td>
		</tr>
	<tr>
<tr>
	<td style="width: 300px;"><strong>RETENCION RENTA </strong></td>
	<td style="width: 145px;" align="right">{{number_format($datos->RetencionRenta,2)}}</td>
	<td style="width: 150px;"><strong></strong></td>
	<td style="width: 69px;" align="right"></td>
</tr>
<tr>
<td style="width: 300px;">&nbsp;</td>
<td style="width: 145px;">&nbsp;</td>
<td style="width: 150px;">&nbsp;</td>
<td style="width: 69px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 300px;">
<h3><strong>LIQUIDACION DEL MES</strong></h3>
</td>
<td style="width: 145px;" align="right">{{number_format($datos->Liquidacion,2)}}</td>
<td style="width: 150px;">&nbsp;</td>
<td style="width: 69px;">&nbsp;</td>
</tr>
</tbody>
</table>
<?php
}else {
?>
<h2>No hay Datos para Mostrar en esta Liquidacion</h2>
<?php
}
?>
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