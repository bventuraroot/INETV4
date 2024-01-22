<table class="table">
<tr>
	<th class='text-center'>CODIGO</th>
	<th class='text-center'>CANT.</th>
	<th>DESCRIPCION</th>
	<th class='text-right'>PRECIO UNIT.</th>
	<th class='text-right'>NO SUJETAS</th>
	<th class='text-right'>EXENTAS</th>
	<th class='text-right'>GRAVADAS</th>
	<th class='text-right'>PRECIO TOTAL</th>
	<th></th>
</tr>
<!--<?php

	$sumador_total=0;
	$iva_retenido = 0;
	$sql= $object->readAll($session_id, $id_empresa);
	//mysqli_query($con, "select * from products, tmp where products.id_producto=tmp.id_producto and tmp.session_id='".$session_id."'");
	foreach ( $sql as $row)
	{
	$id_tmp = $row->id_tmp;
	$codigo_producto = $row->id_producto;
	$cantidad = $row->cantidad_tmp;
	$nombre_producto = $row->descripcion;
	$no_sujetas = $row->no_sujetas;
	$gravadas = $row->gravadas;
	$exentas = $row->exentas;
	
	
	$precio_venta=$row->precio_tmp;
	$precio_venta_f=number_format($precio_venta,2);//Formateo variables
	$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
	$precio_total=$precio_venta_r*$cantidad;
	$precio_total_f=number_format($precio_total,2);//Precio total formateado
	$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
	$precio_no_sujetas = number_format($no_sujetas,2);;
	$precio_gravadas = number_format($gravadas,2);;
	$precio_exentas = number_format($exentas,2);;
	$sumador_total+=$precio_total_r;//Sumador
	if ($clasificacion == 1){
		$iva_retenido += $gravadas*.01;
	}
	
		?>
		<tr>
			<td class='text-center'><?php echo $codigo_producto;?></td>
			<td class='text-center'><?php echo $cantidad;?></td>
			<td><?php echo $nombre_producto;?></td>
			<td class='text-right'><?php echo $precio_venta_f;?></td>
			<td class='text-right'><?php echo $precio_no_sujetas;?></td>
			<td class='text-right'><?php echo $precio_exentas;?></td>
			<td class='text-right'><?php echo $precio_gravadas;?></td>
			<td class='text-right'><?php echo $precio_total_f;?></td>
			<td class='text-center'><a href="#" onclick="eliminar('<?php echo $id_tmp ?>')"><i class="glyphicon glyphicon-trash"></i></a></td>
		</tr>		
		<?php
	}
	$subtotal=number_format($sumador_total,2,'.','');
	$total_iva=($subtotal * $iva )/100;
	$total_iva=number_format($total_iva,2,'.','');
	$total_factura=$subtotal+$total_iva;
	
?>
<tr>
	<td class='text-right' colspan=7>SUBTOTAL $</td>
	<td class='text-right'><?php echo number_format($subtotal,2);?></td>
	<td></td>
</tr>
<tr>
	<td class='text-right' colspan=7>IVA (<?php echo $iva?>)% $</td>
	<td class='text-right'><?php echo number_format($total_iva,2);?></td>
	<td></td>
</tr>
<tr>
	<td class='text-right' colspan=7>IVA RETENIDO</td>
	<td class='text-right'><?php echo number_format($iva_retenido,2);?></td>
	<td></td>
</tr>
<tr>
	<td class='text-right' colspan=7>TOTAL $</td>
	<td class='text-right'><?php echo number_format($total_factura-$iva_retenido,2);?></td>
	<td></td>
</tr>
-->
</table>
