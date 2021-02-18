<?php
	session_start();
	include 'wb_funcao.php';	
	include 'wb_session_campos.php';
	
    $senha   = str_replace("'","",@$_POST['senha']);
    $usuario = str_replace("'","",@$_POST['login']);
	$_SESSION['tipo'] = 'janela';
	$_SESSION['empresa'] = buscaempresa($_SERVER["HTTP_HOST"], mappath(".\config\empresa.ini"));

	$cn = abrebanco();
	
	$_SESSION['nome']="";
	$_SESSION['login']="";
	$_SESSION['id']="";
	$_SESSION['senha']="";
	$_SESSION['tema']="";
	$_SESSION['wallpapers']="";
	$_SESSION['serial']="";
	$_SESSION['ip']         = $_SERVER["REMOTE_ADDR"];
	$_SESSION['registro'] = 10;

	$Sql=strtolower("Select * From tblusuario WHERE login = '".$usuario."' ");
	$rs = $cn->open($Sql);

	if ($rs->EOF == true)
	{
		$Sql=strtolower("Select nome, id,senha, ativo  From tblcliente WHERE Login = '".$usuario."' And Acesso = 1");
		$rscliente = $cn->open($Sql);
		echo "{success:false, message:'Usuario ou Senha invalido'}";	
		$rscliente->Close();
		$cn->Close();
		exit();	
	}
    else
    {	
		if ($rs->fields['senha'] != $senha)
		{
			echo "{success:false, message:'Usuario ou Senha invalido'}";	
			$cn->Close();
			exit();
		}
		
		
		$_SESSION['senha']      = $rs->fields['senha'];
		$_SESSION['nome']       = $rs->fields['Nome'];
		$_SESSION['login']      = $usuario;
		$_SESSION['id']         = $rs->fields['Id'];
		$_SESSION['ip']         = $_SERVER["REMOTE_ADDR"];
		$_SESSION['tema']       = $rs->fields['tema'];
		$_SESSION['wallpapers'] = $rs->fields['wallpapers'];
		
	
		if ($_SESSION['tema'] ==''){
			$_SESSION['tema'] = 'blue';
		}
		
		if ($_SESSION['wallpapers'] ==''){
			$_SESSION['wallpapers'] = 'wallpapers/grande/000009.jpg';
		}
		
		echo "{success:true, message:'wb_desktop.php'}";
		//echo "{success:false, message:'sx = ".$rs->fields['Id']."'}";
		$cn->Close();
	}
	
?>
