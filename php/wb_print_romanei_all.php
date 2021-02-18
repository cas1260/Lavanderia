<?php
	// 4´por pagina se tiver observao e desconto
	// 5 por pagina se tive apenas obser ou desconto
	// 6 por por pagina se não nei dos 2.

	session_start();
	include 'wb_funcao.php';
	$cn = abrebanco();

	@$idcliente = $_GET['id'];

	$txtdatainicial = $_GET['data1'];
	$txtdatafinal   = $_GET['data2'];

	$sql = "SELECT b.nome as nomecliente, b.doc as documento, 
					concat(b.endereco1, ' Nº ',  b.numero1, ' ', b.bairro1, ' ', b.cidade1,' ', b.estado1) as endereco, b.contato as contato,
					a.pedido, a.os,
					f.nome as vendedor,
					a.solicitante,
					a.volume, 
					b.telefone,
					d.codigo, d.descricao as servico, c.qtd as qtd, c.valor, c.subtotal, e.descricao as formapag, a.desconto, a.valortotal ,
					(select GROUP_CONCAT(zz.descricao) from tblrota zz inner join tblrotacliente xx on zz.id = xx.idrota where xx.idcliente = b.id) as rota,
					a.obs,
					d.unidade
				FROM tblromanei a 
				INNER JOIN tblcliente b ON a.idcliente = b.id
				INNER JOIN tblitemromanei c ON a.id = c.idromanei
				INNER JOIN tblservicos d ON d.id = c.idservico 
				left JOIN tblforma e on e.id = a.idforma
				left JOIN tblvendedor f on a.idvendedor = f.id
				where a.data >= '".formatadata($txtdatainicial)."' and a.data <= '".formatadata($txtdatafinal)."'";
				if ($idcliente != '0'){
					$sql = $sql . " and a.idcliente = $idcliente ";
				}
				$sql = $sql . " order by a.os ";
	//echo $sql;
	$rs = $cn->open($sql);
	
?>
<html>

<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Romanei</title>
</head>
<body topmargin="10" leftmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<center>
<?php

$plinha = 0;

$reg = 0;

while ($rs->EOF == false){
	$resto= '';
	if ($plinha >= 7){
		echo "<div ID='quebra' STYLE='page-break-before:always'>";
		$resto = '</div>';
	}

	
?>
	<table border="0" width="605" id="table1" cellspacing="0" cellpadding="0">
		<tr>
			<td width="304" colspan="2"><font face="Verdana" size="2">LAVA LUVAS INDUSTRIA E COMERCIO LTDA</font></td>
			<td width="296" colspan="2"><font size="2" face="Verdana"><?php echo $rs->fields['vendedor'];?></font></td>
		</tr>
		<tr>
			<td width="133"><font face="Verdana" size="2"><?php echo date("d/m/Y");?></font></td>
			<td width="373"  align="CENTER" colspan="2">
			<table border="0" width="408" id="table6" cellspacing="0" cellpadding="0">
				<tr>
					<td width="71">
					<p align="right"><font face="Verdana" size="2"><?php echo date("H:i:s");?></font></td>
					<td width="124">
					<p align="right"><font size="2" face="Verdana">Pedido:</font></td>
					<td width="114"><font size="2" face="Verdana"><?php echo $rs->fields['pedido'];?></font></td>
					<td width="99" align="right"><font size="2" face="Verdana">OS:</font></td>
				</tr>
			</table>
			</td>
			<td width="99" align="right"><font size="2" face="Verdana"><?php echo $rs->fields['os'];?></font></td>
		</tr>
		<tr>
			<td width="133"><font face="Verdana" size="2">Solicitante:</font></td>
			<td width="226"><font face="Verdana" size="2"><?php echo $rs->fields['solicitante'];?></font></td>
			<td width="156"  align="CENTER" align="right"><font size="2" face="Verdana">Volume:</font></td>
			<td width="99" align="right"><font size="2" face="Verdana"><?php echo $rs->fields['volume'];?></font></td>
		</tr>
		<tr>
			<td width="590" colspan="4"><hr size="1" width="102%"></td>
		</tr>
		<tr>
			<td width="600" height="16" align="left" colspan="4">
			<font size="2" face="Verdana">Cliente: <?php echo $rs->fields['nomecliente'];?></font></td>
		</tr>
		
		<tr>
			<td width="703" height="22" align="left" colspan="4">
			<table border="0" width="100%" id="table7" cellspacing="0" cellpadding="0">
				<tr>
					<td width="180" height="22" align="left">
					<p><font size="2" face="Verdana">CNPJ:<?php echo $rs->fields['documento'];?></font></td>
					<td width="275" height="22 align="right">
					<font size="2" face="Verdana">&nbsp;Contato:<?php echo $rs->fields['contato'];?></font></td>
					<td width="160" height="22" align="right">
					<font size="2" face="Verdana"><?php echo $rs->fields['telefone'];?></font></td>
				</tr>
		
			</table>
			</td>
		</tr>
	<tr>
			<td width="600" height="11" align="left" colspan="4">
			<font size="2" face="Verdana"><?php echo $rs->fields['endereco'];?></font></td>
		</tr>
		<tr>
			<td width="600" height="21" align="left" colspan="4">
			<font size="2" face="Verdana">Rota: &nbsp;<?php echo $rs->fields['rota'];?></font></td>
		</tr>
		<tr>
			<td width="590" colspan="4"><hr size="1" width="102%"></td>
		</tr>
		<tr>
			<td width="590" colspan="4" height="21"><cemter>
			<table border="0" width="605" id="table4" cellspacing="0" cellpadding="0" style="border-style:solid; border-color:#FFFFFF; ">
				<tr>
					<td width="52"><font size="2" face="Verdana">Código</font></td>
					<td width="249"><font face="Verdana" size="2">Descrição do serviço</font></td>
					<td width="59" align="center"><font size="2" face="Verdana">Qtd</font></td>
					<td width="35" align="center">UN.</td>
					<td width="102" colspan="2" align="right"><font size="2" face="Verdana">Valor unit.</font></td>
					<td width="108" colspan="2" align="right"><font size="2" face="Verdana">Subtotal</font></td>
				</tr>
				<tr>
					<td width="605" colspan="8"><hr size="1" width="102%"></td>
				</tr>
				<?php
				$plinha = 0;
				$old_os = $rs->fields['os'];
				while ($rs->EOF == false){
					$plinha = $plinha + 1;
				?>
					<tr>
						<td width="52"><font size="2" face="Verdana"><?php echo $rs->fields['codigo'];?></font></td>
						<td width="249"><font size="2" face="Verdana"><?php echo $rs->fields['servico'] . ' - '. $rs->fields['os'];?></font></td>
						<td width="59" align="right"><font size="2" face="Verdana"><?php echo strzero($rs->fields['qtd'],2);?></font></td>
						<td width="35" align="center"><font size="2" face="Verdana"><?php echo $rs->fields['unidade'];?></td>
						<td width="16" align="right"><font size="2" face="Verdana">R$ </font></td>
						<td width="86" align="right"><font size="2" face="Verdana"><?php echo fn($rs->fields['valor']);?></font></td>
						<td width="20" align="right"><font size="2" face="Verdana">R$ </font></td>
						<td width="88" align="right"><font size="2" face="Verdana"><?php echo fn($rs->fields['subtotal']);?></font></td>
					</tr>
				<?php
						$rs->MoveNext();
						$reg = $reg +1;
						if ($rs->EOF == false){
							if ($rs->fields['os'] != $old_os){
								$reg = $reg - 1;
								$rs->Move($reg);
								break;
							}
						}
					}
					if ($rs->EOF == true){
						$reg = $reg -1;
						$rs->Move($reg);					
					}
				?>
				</table></center>
			</td>
		</tr>
		<tr>
			<td width="590" colspan="4" height="21">&nbsp;</td>
		</tr>
		<tr>
			<td width="590" colspan="4" height="21">
			<table border="0" width="606" id="table5" cellspacing="0" cellpadding="0">
				<tr>
					<td width="344">
					<p align="right"><font face="Verdana" size="2">Forma de pagamento:</font></td>
					<td width="262"><font face="Verdana" size="2"><?php echo $rs->fields['formapag'];?></font></td>
				</tr>
				<?php 
				if ($rs->fields['desconto'] != '' && $rs->fields['desconto'] != '0'){ 
					$plinha = $plinha +1;
				?>
					<tr>
						<td width="344">
						<p align="right"><font size="2" face="Verdana">Desconto:</font></td>
						<td width="262" align="right"><font size="2" face="Verdana"><?php if ($rs->fields['desconto'] == ''){echo "0,00";}else{echo fn($rs->fields['desconto']);};?></font></td>
					</tr>
				<?php
				}?>
				<tr>
					<td width="344" align="right"><font size="2" face="Verdana">Valor total:</font></td>
					<td width="262" align="right"><font size="2" face="Verdana">R$ <?php echo fn($rs->fields['valortotal']);?></font></td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	<br>
<?php
		echo $resto;
		$rs->MoveNext();
		$reg = $reg +1;
	}
?>

</center>
</body>

<script>
	<?php 
		@$print = $_GET['print'];
//		echo "alert('$print');";
		if ($print == '') {
			echo "window.print();";
		}
		?>

</script>


</html>


<?php
	exit();
?>
