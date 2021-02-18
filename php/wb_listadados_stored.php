<?php
	session_start();
	include 'wb_funcao.php';

	$tipo     = $_GET['tipo'];
	$cn       = abrebanco();
	$ncampos  = explode(',', $_SESSION[$tipo.'_campos']);
	$limit    = $_POST['limit'];
	$start    = $_POST['start'];
	@$filtro  = $_POST['filtro'];
	@$valor   = $_POST['valor'];
	@$desktop = $_POST['desktop'];
	@$ativo   = $_POST['ativo'];
	
	
	$sql = '';
	
	if ($filtro != ''){
		if(inStr("where", $_SESSION[$tipo . '_table']) == 0){
			$sql = $sql . "where $filtro like '%$valor%' ";
		}else{
			$sql = $sql . " and $filtro like '%$valor%' ";
		}
		if ($ativo != ""){
			if ($ativo == '0'){
				$sql = $sql . " and (ativo != 1 and ativo != -1) ";
			}else{
				$sql = $sql . " and (ativo = $ativo) ";
			}
		}
	}else{
		if ($ativo != ""){
			if ($ativo == '0'){
				$sql = $sql . " where (ativo != 1 and ativo != -1) ";
			}else{
				$sql = $sql . " where (ativo = $ativo) ";
			}
		}
	}
	
	if ($desktop != ''){
		//Nome, Codigo, Dominio, Email, Telefone1, Empresa, Documento, Endereco, Bairro, Cidade, Cep
		if ($sql != ''){
			$sql = $sql . " and ";
		}else{
			
			if(inStr("where", $_SESSION[$tipo . '_table']) == 0){
				$sql = $sql . " where ";
			}else{
				$sql = $sql . " and ";
			}
		}
		$sql = $sql . " Nome like '%$desktop%' or Email like '%$desktop%' or Telefone1 like '%$desktop%' or Empresa like '%$desktop%' or Documento like '%$desktop%' or Endereco like '%$desktop%' or Bairro like '%$desktop%' or Cidade like '%$desktop%' or Cep like '%$desktop%' ";
	}
	
	$sqlcount = "select count(*) as total from ".$_SESSION[$tipo . '_table']. " " . $sql;
	//echo "/*$sqlcount*/";
	if ($_SESSION[$tipo.'_order'] != ''){
		$sql = $sql . " order by " . $_SESSION[$tipo.'_order'];
	}
	$sql = $sql . " limit $start, $limit";
	$sql      = "select ".$_SESSION[$tipo.'_campos']." from ".$_SESSION[$tipo . '_table']. " " . $sql;
	echo "/* $sql */";
	$rs  = $cn->open($sql);

	echo "{";
		$totalregistro = $rs->RecordCount();
		echo "results: " . rsRetornacampo($sqlcount, "total", $cn) . ", \n";
		$i = 0;
		echo "rows: [", "\n";
		$linha = '';
		for ($y = 1; $y <= $totalregistro; $y++) {		
			$linha = $linha . '{';
			for($i = 0; $i < count($ncampos); ++$i){
				if (trim($ncampos[$i]) == 'data' || trim($ncampos[$i]) == 'emissao' || trim($ncampos[$i]) == 'vencimento'){
					$linha = $linha . removeAS($ncampos[$i]) . ": '" . formatadataBR($rs->fields[removeAS(trim($ncampos[$i]))]). "', " ;
				}else{
					$linha = $linha . removeAS($ncampos[$i]) . ": '" . $rs->fields[removeAS(trim($ncampos[$i]))] . "', " ;
				}
			}
			$linha = left($linha, strlen($linha)-2);
			$linha = $linha . "}, ";
			$rs->MoveNext();
		};
		$linha = left($linha, strlen($linha)-2);
		echo $linha, "\n";
		echo "]",	 "\n";

	echo "}";
	//exit();
?>