<?php
include 'php/wb_funcao.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    	<title>WebFinan 5.0</title>

		<!--css-->
		<link rel="stylesheet" type="text/css" href="framework/resources/css/ext-all.css" />		
		<link rel="stylesheet" type="text/css" href="framework/scripts/Ext.ux.login/Ext.ux.login.css"/>
		<link rel="stylesheet" type="text/css" href="framework/resources/css/xtheme-gray.css" />
		<!link rel="stylesheet" type="text/css" href="framework/resources/theme/xtheme-vistaglass/css/xtheme-vistaglass.css" />

		<style type="text/css">
            .app-logo
            {
	            background: url('images/login.jpg');
	            width:150%;
	            height:180px;
	            background-repeat:no-repeat;
	            margin-bottom: 0px;
            }
            .imgLogo
            {
                float:left;
                padding-right:10px;
            }
		    .texto{ line-height: 18px; }
		</style>
		
		<!--js-->
		<script type="text/javascript" src="framework/scripts/ext-base.js"></script>
		<script type="text/javascript" src="framework/scripts/ext-all.js"></script>
		
		<script type="text/javascript" src="framework/scripts/ext-lang-pt_BR.js"></script>
		<script type="text/javascript" src="framework/scripts/Ext.ux.login/Ext.ux.login.js"></script>

		
		<script type="text/javascript">
			
			Ext.onReady(function()
			{
				//Inicializar quicktips
				Ext.QuickTips.init();

				new Ext.ux.login({
					url: 'php/wb_logar.php'
					,urlLembrar: '/php/wb_lembrarsenha.php'
					//,redirectUrl: '/desktop.php'						
				}).show();
			});
		</script>
	</head>
	<body></body>
</html>