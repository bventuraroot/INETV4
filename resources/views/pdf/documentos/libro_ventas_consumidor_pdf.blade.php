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
</style>
</head>
<body>
<header>
	{{$titulo}}

<br>

	<strong>Nombre del Contribuyente:</strong>{{$nombre_empresa}}&nbsp;&nbsp;&nbsp;
	<strong>N.R.C.:</strong>{{$nrc}}&nbsp;&nbsp;&nbsp;<strong>MES:</strong>{{$mes}}&nbsp;&nbsp;&nbsp;<strong>AÑO:</strong>{{$ano_aplicacion}}

</header>

<br>
<br>
<table width="750" style="margin-top: 5px;">
<tbody>
<tr>
<td width="80">DIA</td>
<td width="80">DEL No.</td>
<td width="80">AL No.</td>
<td width="80">N.CAJA<br />REGIST.</td>
<td width="80">EXENTAS</td>
<td width="80">VENTAS<br /> INTERNAS<br /> GRAVADAS</td>
<td width="80">EXPORT.</td>
<td width="80">TOTAL<br /> VENTAS</td>
<td width="75">TERCEROS</td>
</tr>
@php
$total_gravadas = 0;
@endphp
@foreach ($datos as $venta) {


<tr>
<td >{{$venta->dia}}</td>
<td >{{$venta->desde}}</td>
<td>{{$venta->hasta}}</td>
<td></td>
<td align="right">{{ number_format($venta->exentas,2)}}</td>
<td align="right">{{ number_format($venta->internas,2)}}</td>
<td align="right">{{ number_format($venta->exportaciones,2)}}</td>
<td align="right">{{ number_format($venta->total,2)}}</td>
<td align="right">{{ number_format($venta->terceros,2)}}</td>
</tr>
@php 
$total_gravadas += $venta->internas;
@endphp
@endforeach
</tbody>
</table>
<br>
<br>
<table width="600">
<tbody>
<tr>
<td colspan="4" width="462">LIQUIDACION DEL DEBITO FISCAL EN VENTAS DIRECTAS</td>
<td width="100"></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td>GRAVADAS SIN IVA</td>
<td align="right"><?php echo number_format($total_gravadas/1.13,2);?></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td>IVA13%</td>
<td align="right"><?php echo number_format(($total_gravadas/1.13)*0.13,2);?></td>
</tr>
<tr>
<td>VENTA LOCALES GRAVADAS</td>
<td align="right"><?php echo number_format($total_gravadas,2);?></td>
<td></td>
<td>TOTAL</td>
<td align="right"><?php echo number_format($total_gravadas,2);?></td>
</tr>
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