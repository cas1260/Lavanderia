<?php
	session_start();
	include 'wb_funcao.php';
	
	$tabela = $_GET['tabela'];
	$id = $_GET['id'];
	
	$cn = abrebanco();
	
	@$ref = $_GET['ref'];
	
	if ($ref != ''){
		@$codigo = $_POST[$ref . 'codigo'];
		if ($codigo != ''){
			if (pesquisacodigo($codigo, $cn, $tabela, $id) == false){
				exit();
			}
		}
	}
	
	
	$sql = montasql($tabela, $id);
	

	$cn->Execute($sql);
	
//	echo "/* $sql */";
	
	@$tela = $_GET['tela'];
	if ($tela != ''){
		echo "MSG('Dados salvo com sucesso!', function(){Ext.getCmp('$tela').close();});";
	}
?>