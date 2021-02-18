<?php
header("pragma".": "."no-cache");
header("cache-control".": "."private");
header("Content-Type".": "."text/html; charset=iso-8859-1");
header("Cache-Control: "."no-cache");

include 'php/wb_funcao.php';
@session_start();
@$id = $_SESSION['id'];

if ($id==""){
	//header("Location: ./wb_login.php");
	echo "<script>window.open('./wb_login.php', '_self');</script>";
	exit();
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>


.xcheckbox-wrap {
   line-height: 18px;
   padding-top:2px;
}
.xcheckbox-wrap a {
   display:block;
   width:16px;
   height:16px;
   float:left;
}
.x-toolbar .xcheckbox-wrap {
   padding: 0 0 2px 0;
}
.xcheckbox-on {
   background:transparent url(./framework/resources/images/default/menu/checked.gif) no-repeat 0 0;
}
.xcheckbox-off {
   background:transparent url(./framework/resources/images/default/menu/unchecked.gif) no-repeat 0 0;
}
.xcheckbox-disabled {
   opacity: 0.5;
   -moz-opacity: 0.5;
   filter: alpha(opacity=50);
   cursor:default;
}

@class Ext.ux.XCheckbox
@extends Ext.form.Checkbox

   
#fundo {
	width: 100%;
	/*background:#3d71b8 url('images/logofundo.jpg'); */

	/*	min-width: 0;
	max-width: 100%;
	width: 100%;
    font: normal 12px tahoma, arial, verdana, sans-serif;
	margin: 0;
	padding: 0;
	border: 0 none;
	overflow: hidden;
	color: #003399;
	background-color: #000000;
	background-repeat:repeat-x
	background-size: auto;
	--fixed top center
	
	background-attachment: fixed;
	background-repeat: no-repeat;
	background-position: top left;
	background-width: 100%;	
	background-height: 100%;
*/
}



</style>

<title>Webluvas 1.0 - Controle Gerencial</title>

    <link rel="stylesheet" type="text/css" href="framework/resources/css/ext-all.css" />
	<!--link rel="stylesheet" type="text/css" href="framework/resources/css/xtheme-gray.css" /-->
	<!--link rel="stylesheet" type="text/css" href="framework/resources/theme/xtheme-vistaglass/css/xtheme-vistaglass.css" /-->
	<!--link rel="stylesheet" type="text/css" href="framework/resources/theme/PurpleTheme/css/xtheme-purple.css" /-->

	<link rel="stylesheet" type="text/css" href="temas/<?php echo $_SESSION['tema'];?>/css/thema.css" />

    <link rel="stylesheet" type="text/css" href="css/data-view.css" />
	
    <link rel="stylesheet" type="text/css" href="css/xmenu.css" />
    <link rel="stylesheet" type="text/css" href="css/cadastro.css"/>

	<script>
		function trocaTAB(){
		   var nodes = document.getElementsByTagName('*');
		   //console.dir(nodes);
		   var elements = new Array();
		   for(var j=0;j<nodes.length;j++){ //>		
			   //if(nodes[j].tagName.toLowerCase()=="input" || nodes[j].tagName.toLowerCase()=="textarea" || nodes[j].tagName.toLowerCase()=="select" ){
			   //alert(nodes[j].tagName);
			   if(nodes[j].tagName.toLowerCase()=="input" || nodes[j].tagName.toLowerCase()=="select"){			
					elements.push(nodes[j]);
			   } 
		   }
		   for(var i=0;i<elements.length;i++){ //>
			   if(elements[i].type.toLowerCase()=="submit" || elements[i].type.toLowerCase()=="reset") continue;
			   elements[i].onkeypress=function(e){
						var k = document.all?event.keyCode:e.keyCode;					  																
						if(k==13){									   				
						   var nodes = document.getElementsByTagName('*');	   
						   var elements = new Array();
						   for(var j=0;j<nodes.length;j++){		//>
							   if( nodes[j].tagName.toLowerCase()=="input"  || nodes[j].tagName.toLowerCase()=="textarea" || nodes[j].tagName.toLowerCase()=="select" ){			
									elements.push(nodes[j]);
							   } 
						   }
							for(var i=0;i<elements.length;i++){		//>		   				
								if(this==elements[i] && i<elements.length-1){ //>
									elements[i+1].focus();
									return false;
								}
							}
							elements[0].focus();
							return false;
						}
						return true;
					};
		   }
		};
	</script>
	
 	<script type="text/javascript" src="framework/scripts/ext-base.js"></script>
    <script type="text/javascript" src="framework/scripts/ext-all.js"></script>
	<script type="text/javascript" src="framework/scripts/ext-lang-pt_BR.js"></script>
	<script type="text/javascript" src="framework/scripts/Ext.ux.TextMask.js?x=a"></script>
	
	
	<script type="text/javascript" src="php/wb_principal.php?canche=no"></script>
	

</head>
<body oncontextmenu = 'return false;' scroll="no">
<style>
	.holder{
		position: absolute; 
		height: 50;
		width:100%;
		}
	.bottom{
		position: absolute; 
		bottom: 0;
		width:100%
	}
	.new2{
		background: url(./images/xmenu/contact-new.png) no-repeat 0 0;
	}

</style>
<!--[if IE 6]>
	<style>
		.bottom{
			position: relative; 
			bottom: 5;
			width:100%
		}
	</style>
<![endif]-->
<!--[if IE 7]>
	<style>
		.bottom{
			position: relative; 
			bottom: 5;
			width:100%
		}
	</style>
<![endif]-->
<!--[if IE 8]>
	<style>
		.bottom{
			position: relative; 
			bottom: 5;
			width:100%
		}
	</style>
<![endif]-->

<div id="ux-menu" class ='holder'></div>
<div id="x-desktop">
</div>
<div>
	<center><br><br><img  src = 'images/logofundo.jpg'>
<div  id = 'xbarradivX' class = 'bottom' width = '100%' height = '30px'></div>
<iframe name = 'printgrid' style="overflow:auto;width:0%;height:0%;" frameborder="0"  src=""></iframe>
</body>
</html>

