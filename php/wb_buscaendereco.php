<?php
	session_start();
	include 'wb_funcao.php';

	$cn = abrebanco();
	@$id        = $_GET['id'];
	@$n         = $_GET['n'];
	
	$sql = "select concat(endereco1, ' , ', numero1 , ' , ', bairro1, ' , ', cidade1, ' , ', estado1) as  endereco from tblcliente where id = $id";
	$endereco = rsRetornacampo($sql, 'endereco', $cn);
	
?>