<?php
require_once("../config.php");

$db = $ngl("pdo.")->args(array(
	"host" => "localhost",
	"user" => "unilever_produ",
	"pass" => "Un1l3ver2019%",
	"base" => "unilever_dev00"
))->connect();

function makePDF($sNegocio) {
	global $ngl, $db;

	$aMonths							= array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$sNegocio 							= strtolower($sNegocio);
	$aConfig							= array();
	$aConfig["pc"]						= array("PERSONAL CARE", "#5E256B", "#C19BC8");
	$aConfig["hc"]						= array("HOME CARE", "#0B9BCE", "#70C4E7");
	$aConfig["foods"]					= array("FOODS", "#336C34", "#99CA89");
	$aConfig["hc profesional"]			= array("HC PROFESIONAL", "#0E6EB0", "#70C4E7");
	$aConfig["food solutions"]			 = array("FOOD SOLUTIONS", "#0E6EB0", "#70C4E7");
	$aConfig["pc profesional"]			= array("PC PROFESIONAL", "#0E6EB0", "#70C4E7");


	$aData = $db->query("
		SELECT 
			`sub_negocio`, 
			`categoria`, 
			`sub_categoria`, 
			`segmento`, 
			`marca`, 
			CONCAT(REPLACE(LCASE(`sub_negocio`), ' ', '_'),'.jpg') AS 'caratula', 
			`formato`, 
			`novedad`, 
			`descripcion`, 
			`ean13`, 
			`dun`,
			`cn`,
			`unid`,
			`sap`, 
			'".$aConfig[$sNegocio][0]."' AS 'title',
			'".$aConfig[$sNegocio][1]."' AS 'color1',
			'".$aConfig[$sNegocio][2]."' AS 'color2' 
		FROM `catalogo` 
		WHERE LCASE(`sub_negocio`) = '".$sNegocio."' 
		ORDER BY 1,2,3,4,5 
	")->getall();
	
	//print_r($aData);
	foreach($aData as $k => $aRow) {
		$aRow["logo"] = strtolower(preg_replace("/[^0-9a-z]/is", "", $aRow["marca"]).".png");
		$aData[$k] = $aRow;
	}

	$aLogos = array();
	$aGetLogos = $db->query("
		SELECT 
			`categoria`, 
			`marca` 
		FROM 
			`catalogo` 
		WHERE 1 
		GROUP BY `categoria`, `marca` 
		ORDER BY RAND() 
	")->getall();
	foreach($aGetLogos as $aRow) {
		if(!isset($aLogos[$aRow["categoria"]])) {
			$aLogos[$aRow["categoria"]] = array();
		}
		$aLogos[$aRow["categoria"]][] = strtolower(preg_replace("/[^0-9a-z]/is", "", $aRow["marca"]).".png");
	}

	$GLOBALS["aData"]	= $aData;
	$GLOBALS["aLogos"]	= $aLogos;
	$GLOBALS["sDate"]	= "Fecha última actualización ".date("d/m/Y");
	$template = $ngl("rind.")->args(array(
		"cache" => "cache"
	));
	$sContent = $template->stamp("test.html");
	$sContent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $sContent);
    //
    // modified by SAK
    echo $sContent;
    exit;

	// echo $sContent;
	$aFilesNames = array();
	$aFilesNames["hc"] 						= "home_care";
	$aFilesNames["pc"] 						= "personal_care";
	$aFilesNames["foods"] 					= "foods";
	$aFilesNames["hc profesional"] 			= "hc_profesional";
    $aFilesNames["food solutions"] 			= "food_solutions";
    $aFilesNames["pc profesional"] 			= "pc_profesional";

	$ngl("pdf.")
		->margins(array(0,0,0,0))
		->content($sContent)
		->write(NGL_DOCUMENT_ROOT."/pdf/cache/".$aFilesNames[$sNegocio].".pdf"); 
}


// -- makepdf_AR -- //
makePDF("hc profesional");
makePDF("hc"); 
makePDF("pc");
makePDF("pc profesional");
makePDF("food solutions");
makePDF("foods");  

$sZipFile = NGL_DOCUMENT_ROOT."/pdf/cache/catalogo.zip";
@unlink($sZipFile);

$zip = new ZipArchive();
if($zip->open($sZipFile, ZIPARCHIVE::CREATE) === true) {
	$zip->addFile(NGL_DOCUMENT_ROOT."/pdf/cache/home_care.pdf", "home_care.pdf");
	$zip->addFile(NGL_DOCUMENT_ROOT."/pdf/cache/personal_care.pdf", "personal_care.pdf");
	$zip->addFile(NGL_DOCUMENT_ROOT."/pdf/cache/foods.pdf", "foods.pdf");
	$zip->addFile(NGL_DOCUMENT_ROOT."/pdf/cache/hc_profesional.pdf", "hc_profesional.pdf");
	$zip->addFile(NGL_DOCUMENT_ROOT."/pdf/cache/food_solutions.pdf", "food_solutions.pdf");
	$zip->addFile(NGL_DOCUMENT_ROOT."/pdf/cache/pc_profesional.pdf", "pc_profesional.pdf");
	$zip->close();
 }

die("listo");
?>