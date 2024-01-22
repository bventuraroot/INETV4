<?php
session_start();
if(empty($_SESSION['user_id']))
{
    header("Location: index.php");
}
	set_include_path(dirname(dirname(__FILE__)).'/');
	$usuario = $_SESSION['user_id'];
	$id_empresa = $_SESSION["id_empresa"];
	$tipo = $_GET["tipo"];
	$id_factura = $_GET["id_factura"];
	if (file_exists("pdf/comprobantes/".$id_empresa.$tipo."php"))
	{
    	$filename = "pdf/comprobantes/".$id_empresa.$tipo.".php";     
	}
	else
	{
    	$filename = "pdf/comprobantes/CCF.php";
	}
	
	$iva = 13.00;
	require_once 'lib/FacturasController.php';
	$object = new FacturasController();
	$factura = $object->readOne($id_factura);
//Archivo verifica que el usario que intenta acceder a la URL esta logueado
/*$session_id= $_SESSION['user_id'];
if (isset($_POST['id'])){$id_producto=$_POST['id'];}
if (isset($_POST['cantidad'])){$cantidad_tmp=$_POST['cantidad'];}
if (isset($_POST['precio_venta'])){$precio_tmp = $_POST['precio_venta'];}
if (isset($_POST['no_sujetas'])){$no_sujetas = $_POST['no_sujetas'];}
if (isset($_POST['exentas'])){$exentas = $_POST['exentas'];}
if (isset($_POST['gravadas'])){$gravadas = $_POST['gravadas'];}
if (isset($_POST['descripcion'])){$descripcion = $_POST['descripcion'];}
if (isset($_POST['tipo_producto'])){$tipo_producto = $_POST['tipo_producto'];}
if (isset($_POST['clasificacion'])){$clasificacion = $_POST['clasificacion'];}

/*$id_producto = 6; //
$cantidad_tmp = 1; //
$precio_tmp = 10; //
$no_sujetas = 0;
$exentas = 0; 
$gravadas= 10; 
$descripcion= 'prueba';

if (!empty($id_producto) and !empty($cantidad_tmp) and !empty($precio_tmp))
{

$insert_tmp= $object->Create($id_producto, $cantidad_tmp, $precio_tmp, $session_id, $no_sujetas, $exentas, $gravadas, $descripcion, $id_empresa,$tipo_producto);
//mysqli_query($con, "INSERT INTO tmp (id_producto,cantidad_tmp,precio_tmp,session_id) VALUES ('$id','$cantidad','$precio_venta','$session_id')");
}
if (isset($_GET['id']))//codigo elimina un elemento del array
{
$id_tmp=intval($_GET['id']);	
$delete= $object->Delete($id_tmp);
//mysqli_query($con, "DELETE FROM tmp WHERE id_tmp='".$id_tmp."'");
}
*/
?>
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
