<?php
	session_start();
	include 'wb_funcao.php';

	$cn = abrebanco();

	@$id        = $_GET['id'];
	@$codigo    = $_GET['codigo'];
	@$n         = $_GET['n'];
	@$idcliente = $_GET['idcliente'];
	@$acao      = $_GET['acao'];
	
	if (is_numeric($id) == false){
		$id = '0';
	}
	if ($id == ''){
		$id = '0';
	}
	
	
	if ($idcliente == '0'){
		$sql = "select a.codigo, a.descricao, a.precounitario, a.precounitario as valor, 0 as idservico, a.unidade from tblservicos a 
					where (a.id = $id or codigo = '$codigo') ";
	}else{
			$sql = "select a.codigo, a.descricao, a.precounitario, b.valor, b.idservico, a.unidade from tblservicos a 
					left join tblclienteservico b on a.id = b.idservico  and b.idcliente = $idcliente 
					where (a.id = $id or codigo = '$codigo') ";
	}
	$rs = $cn->open($sql);
	echo "/* $sql */";
	if ($rs->EOF == true){
		$com = '';
		if ($acao == 'cliente'){
			 $com = "Ext.getCmp('".$n."0unidade').setValue('');";
		}	
		echo "MSG('Serviço não encontrado.', function(){
			Ext.getCmp('".$n."0codigo').setValue('');
			//Ext.getCmp('".$n."0servico').setValue('');
			Ext.getCmp('".$n."0qtd').setValue('');
			Ext.getCmp('".$n."0valor').setValue('');
			Ext.getCmp('".$n."0codigo').focus();
			".$com."
			});";
		exit();
	}
	echo "Ext.getCmp('".$n."0codigo').setValue('".$rs->fields['codigo']."');";
	echo "Ext.getCmp('".$n."0servico').setValue('".$rs->fields['descricao']."');";
	echo "Ext.getCmp('".$n."0qtd').setValue('1');";
	if ($rs->fields['valor'] != ''){
		echo "Ext.getCmp('".$n."0valor').setValue('".$rs->fields['valor']."');";
	}else{
		echo "Ext.getCmp('".$n."0valor').setValue('".$rs->fields['precounitario']."');";
	}
	echo "Ext.getCmp('".$n."0qtd').focus();";
	if ($acao == 'cliente'){
		 echo "Ext.getCmp('".$n."0unidade').setValue('".$rs->fields['unidade']."');";
	}	
?>