<?php
	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
session_start();
if(empty($_SESSION['user_id']))
{
    header("Location: index.php");
}
	
	/* Connect To Database*/
	set_include_path(dirname(dirname(__FILE__)).'/');
	$hechopor = $_SESSION['user_id'];
	$id_empresa = $_SESSION["id_empresa"];
	require_once 'lib/TmpController.php';
	$object = new TmpController();
	$sql_query = $object->readAll($hechopor, $id_empresa);
	$count=$object->rowcount;
	if ($count==0)
	{
	echo "<script>swal('No hay productos agregados a la factura')</script>";
	exit;
	}

	require_once('pdf/html2pdf.class.php');
		
	//Variables por GET
	$id_cliente=intval($_GET['id_cliente']);
	$id_vendedor=intval($_GET['id_vendedor']);
	$condiciones=$_REQUEST['condiciones'];

	//Fin de variables por GET
	$sql=mysqli_query($con, "select LAST_INSERT_ID(id_factura) as last from facturas order by id_factura desc limit 0,1 ");
	$rw=mysqli_fetch_array($sql);
	$numero_factura=$rw['last']+1;	
    // get the HTML
    ob_start();
    include(dirname('__FILE__').'/res/factura_html.php');
    $content = ob_get_clean();

    try
    {
        // init HTML2PDF
        $html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        // display the full page
        $html2pdf->pdf->SetDisplayMode('fullpage');
        // convert
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        // send the PDF
        $html2pdf->Output('Factura.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
