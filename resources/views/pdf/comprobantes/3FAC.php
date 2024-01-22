<?php
?>
<table width="609">
<tbody>
<tr>
<td width="80">Cliente:</td>
<td colspan="2" width="246"><?php echo $factura->anombrede;?></td>
<td width="96">Fecha:</td>
<td width="86"><?php echo $factura->fecha_factura;?></td>
<td width="101"></td>
</tr>
<tr>
<td>Direcci&oacute;n:</td>
<td colspan="2"><?php echo $factura->direccion_cliente;?></td>
<td>Venta a cuenta de:</td>
<td></td>
<td></td>
</tr>
<tr>
<td>Departamento:</td>
<td colspan="2"><?php echo $factura->departamento;?></td>
<td colspan="2">Condiciones de la operaci&oacute;n:</td>
<td></td>
</tr>

<tr>
<td>NIT:</td>
<td colspan="2"><?php echo $factura->nit;?></td>
<td colspan="2">No de Nota de Remisi&oacute;n Anterior:</td>
<td></td>
</tr>
<tr>
<td></td>
<td colspan="2"></td>
<td colspan="2">Fecha de Nota de Remisi&oacute;n Anterior:</td>
<td></td>
</tr>
<tr>
<td></td>
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
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td>CANTIDAD</td>
<td>DESCRIPCION</td>
<td>P.UNITARIO</td>
<td>V.NOSUJETAS</td>
<td>VTAS EXENTAS</td>
<td>VTAS AFECTAS</td>
</tr>

<?php 
foreach ($factura_dets as $factura_det) {
?>
<tr>
<td ><?php echo $factura_det->cantidad;?></td>
<td align="right"><?php echo $factura_det->descripcion;?></td>
<td align="right"><?php echo $factura_det->precio_unitario;?></td>
<td align="right"><?php echo $factura_det->no_sujetas;?></td>
<td align="right"><?php echo $factura_det->exentas;?></td>
<td align="right"><?php echo $factura_det->gravadas+$factura_det->iva;?></td>
</tr>
<?php } ?>
<tr>
<td></td>
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
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
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
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
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
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
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
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
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
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td>SON:</td>
<td colspan="3" rowspan="2" ><?php echo numtoletras($factura->total_venta);?></td>
<td>SUMAS</td>
<td align="right"><?php echo $factura->total_gravadas+$factura->total_iva;?></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>(-) IVA RETENIDO</td>
<td align="right"><?php echo $factura->iva_retenido;?></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>VENTA NO SUJETA</td>
<td align="right"></td>
</tr>
<tr>
<td align="right"><?php echo $factura->total_nosujetas;?></td>
<td></td>
<td></td>
<td></td>
<td>VENTA EXENTA</td>
<td align="right"><?php echo $factura->total_exentas;?></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>OTROS</td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>TOTAL</td>
<td align="right"><?php echo $factura->total_venta;?></td>
</tr>
</tbody>
</table>


