<?php

	session_start();
	include 'wb_funcao.php';
	
	$cn = abrebanco();

	@$numero = $_GET['numero'];
	
	if ($numero == ''){
		echo "<script>alert('erro: Não foi possivel encontrar a nota fiscal');</script>";
		exit();
	}

	$SQL = "select b.nome, b.endereco1, b.bairro1, b.bairro1, b.cidade1, b.estado1, b.cep1, b.doc,
				b.estadual, b.municipal, c.descricao as descricaoforma, d.codigo as vendedor, a.obs, a.emissao, a.valortotal, a.numero
				from tblnf a 
				inner join tblcliente b on a.idcliente = b.id
				left  join tblforma c on c.id = b.idforma
				left  join tblvendedor d on d.id = a.idvendedor
				where a.numero = $numero";

//	echo $SQL;
				
	$rs = $cn->open($SQL);
	
	if ($rs->EOF == true){
		echo "<script>alert('erro: Não foi possivel encontrar a nota fiscal');</script>";
		exit();	
	}
	

	$SQLItem = "select sum(a.subtotal) as valor, sum(a.qtd) as qtd, (sum(a.subtotal) / sum(a.qtd)) as precounitario, b.descricao, b.unidade
						from tblitemromanei a 
						inner join tblservicos b on a.idservico = b.id
						inner join tblitennf   c on a.idromanei = c.idromanei
						inner join tblnf       d on c.idnf = d.id
						where d.numero = $numero
						group by b.descricao, b.unidade";
	
	$rsItem = $cn->open($SQLItem);
	
	
		
	$SQLRol = "select GROUP_CONCAT(concat(' ' , cast(a.os as char))) as romanei 
					  from tblromanei a inner join tblitennf b on a.id = b.idromanei
										inner join tblnf c on b.idnf = c.id
										where c.numero = $numero";
	$rsRol = $cn->open($SQLRol);
?>

<html>

<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Nota fiscal Lava Luvas</title>
</head>



<body topmargin="70" leftmargin="0" rightmargin="2" bottommargin="2,5" marginwidth="1,3" marginheight="0">
<table border="0" cellpadding="0" style="border-collapse: collapse; width: 726px">
	<colgroup>
		<col width="58" style="width: 44pt">
		<col width="64" span="3" style="width: 48pt">
		<col width="69" style="width: 52pt">
		<col width="100" style="width: 75pt">
		<col width="50" style="width: 38pt"><col width="84" style="width: 63pt">
		<col width="88" style="width: 66pt">
		<col width="88" style="width: 66pt">
	</colgroup>
	<tr height="16" style="height: 12.0pt">
		<td height="16" width="58" style="">
		<a name="RANGE!A1:I34"><font size="2" face="Arial">&nbsp;</font></a></td>
		<td width="64" style="">&nbsp;</td>
		<td width="64" style="">&nbsp;</td>
		<td width="64" style="">&nbsp;</td>
		<td width="69" style="width: 52pt; ">&nbsp;</td>
		<td width="100" style="width: 75pt; ">&nbsp;</td>
		<td width="50" style="width: 38pt; ">&nbsp;</td>
		<td style="width: 96px; ">&nbsp;</td>
		<td style="width: 75px; ">
		&nbsp;</td>
		<td style="width: 84px; ">
		&nbsp;</td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="16" style="">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" width="96">&nbsp;</td>
		<td style="width: 75px; ">
		<font size="2" face="Arial">Prest.Serv.</font></td>
		<td style="width: 84px; ">
		&nbsp;</td>
	</tr>
	<tr height="17" style="">
		<td height="17" style="">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" width="96">&nbsp;</td>
		<td style="" width="75">
		<font size="2" face="Arial">Rodov.</font></td>
		<td style="" width="84">
		&nbsp;</td>
	</tr>
	<tr height="18" style="height: 13.5pt">
		<td height="18" style="">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" width="96">&nbsp;</td>
		<td style="" width="75">
		<font size="2" face="Arial"><?php echo date('d/m/y');?></font></td>
		<td style="" width="84">
		&nbsp;</td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="16" style="">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" width="96">&nbsp;</td>
		<td style="" width="75">&nbsp;</td>
		<td style="" width="84">&nbsp;</td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="21" style="">
		</td>
		<td style="height: 21px" colspan="6" width="412">
		<font size="2" face="Arial"><?php echo $rs->fields['nome'];?></font></td>
		<td style="height: 21px" width="96" valign="middle">
		&nbsp;</td>
		<td width="75" style="height: 21px">
		&nbsp;</td>
		<td style="height: 21px" width="84">
		&nbsp;</td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="21" style="">
		</td>
		<td style="height: 21px" colspan="6">
		<font size="2" face="Arial"><?php echo $rs->fields['endereco1'];?></font></td>
		<td style="height: 21px" width="96" valign="middle">
		<font size="2" face="Arial">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1289</font></td>
		<td width="75" valign="middle" height="21">
		&nbsp;</td>
		<td style="height: 21px" width="84">
		&nbsp;</td>
	</tr>
	<tr height="16" style="height: 12.6pt">
		<td height="21" style="">
		</td>
		<td colspan="3" style="height: 21px">
		<font size="2" face="Arial"><?php echo $rs->fields['bairro1'];?></font></td>
		<td style="height: 21px" colspan="2">
		<font size="2" face="Arial"><?php echo $rs->fields['cep1'];?></font></td>
		<td style="height: 21px">&nbsp;</td>
		<td style="height: 21px" width="96">&nbsp;</td>
		<td width="75" valign="middle" height="21">
		<font size="2" face="Arial"><?php echo $rs->fields['estado1'];?></font></td>
		<td style="height: 21px" width="84">
		<font size="2" face="Arial">&nbsp;</font></td>
	</tr>
	<tr height="16" style="height: 12.6pt">
		<td height="21" style="">
		</td>
		<td style="height: 21px" colspan="3">
		<font size="2" face="Arial"><?php echo formatarCPF_CNPJ($rs->fields['doc']);?></font></td>
		<td style="height: 21px">&nbsp;</td>
		<td style="height: 21px" colspan="2">
		<font size="2" face="Arial">&nbsp;&nbsp;&nbsp; <?php echo $rs->fields['estadual'];?></font></td>
		<td style="height: 21px" align="right" width="96">&nbsp;</td>
		<td style="height: 21px" width="159" colspan="2">
		<p align="right"><font face="Arial" size="2"><?php echo $rs->fields['municipal'];?></font></td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="21" style="">
		</td>
		<td style="height: 21px" colspan="2">
		<font size="2" face="Arial"><?php echo $rs->fields['descricaoforma'];?></font></td>
		<td style="height: 21px">&nbsp;</td>
		<td style="height: 21px">&nbsp;</td>
		<td style="height: 21px">&nbsp;</td>
		<td style="height: 21px" colspan="2"><font size="2"><?php echo $rs->fields['numero'];?></font></td>
		<td style="height: 21px" width="75">&nbsp;</td>
		<td style="height: 21px" width="84" align="right">
		<p align="center"><font size="2" face="Arial"><?php echo $rs->fields['vendedor'];?></font></td>
	</tr>
	<tr height="8" style="height: 6.0pt">
		<td height="8" style="">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" align="right" width="96">&nbsp;</td>
		<td style="" width="75">&nbsp;</td>
		<td style="" width="84" align="right">&nbsp;</td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="16" style="">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" align="right" width="96">&nbsp;</td>
		<td style="" width="75">&nbsp;</td>
		<td style="" width="84" align="right">&nbsp;</td>
	</tr>
	
	<?php
		$totallinha = 0;
		while ($rsItem->EOF == false){
		?>
			<tr height="17" style="">
				<td height="17"><font face="Arial" size="2"><?php echo $rsItem->fields['qtd'];?></font></td>
				<td><font size="2" face="Arial"><?php echo $rsItem->fields['unidade'];?></font></td>
				<td colspan="5"><font size="2" face="Arial"><?php echo $rsItem->fields['descricao'];?></font></td>
				<td align="right" width="96"><font size="2" face="Arial">R$ <?php echo fn($rsItem->fields['precounitario']);?></font></td>
				<td width="75">&nbsp;</td>
				<td width="84" align="right"><font size="2" face="Arial">R$ <?php echo $rsItem->fields['valor'];?></font></td>
			</tr>
		<?php
			$totallinha = $totallinha +1;
			$rsItem->MoveNext();
		}
		$totallinha = $totallinha +1;
	?>
		<tr height="17" style="">
			<td colspan="7"><font size="2" face="Arial">OS(s):<?php echo $rsRol->fields['romanei'];?></font></td>
			<td align="right" width="96"><font size="2" face="Arial">&nbsp;</font></td>
			<td width="75">&nbsp;</td>
			<td width="84" align="right"><font size="2" face="Arial">&nbsp;</font></td>
		</tr>
	
	
	<?php
		if ($totallinha < 17){
			for ($y = $totallinha; $y <= 16; $y++) {
	?>
				<tr height="17" style="">
					<tr height="17" style="">
					<td height="17"><font face="Arial" size="2">&nbsp;</font></td>
					<td><font size="2" face="Arial">&nbsp;</font></td>
					<td colspan="5"><font size="2" face="Arial">&nbsp;</font></td>
					<td align="right" width="96"><font size="2" face="Arial">&nbsp;</font></td>
					<td width="75">&nbsp;</td>
					<td width="84" align="right"><font size="2" face="Arial">&nbsp;</font></td>
				</tr>

	
	<?php 
			}
		}
	
		$data =  explode('-', $rs->fields['emissao']);
	
		$dia = $data[2];
		$mes = $data[1];
		$ano = $data[0];
	
	// fim de registro
	?>
	
	
	
	<tr height="9" style="height: 6.75pt">
		<td height="5" style="" colspan="10">
		</td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="16" colspan="2" style="">
		<font size="2" face="Arial"><?php echo $rs->fields['obs'];?></font></td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" colspan="2">
		<font face="Arial" size="2">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; <?php echo $dia;;?>&nbsp;&nbsp;&nbsp;&nbsp; 
		<?php echo $mes;?>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $ano;?></font></td>
		<td style="">&nbsp;</td>
		<td style="" align="right" width="96">&nbsp;</td>
		<td style="" width="75">
		&nbsp;</td>
		<td style="" width="84" align="right">
		<font size="2" face="Arial">R$ <?php echo $rs->fields['valortotal'];?> </font></td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="16" style="">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" width="96">&nbsp;</td>
		<td style="" width="75">&nbsp;</td>
		<td style="" width="84">&nbsp;</td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="16" style="">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" width="96">&nbsp;</td>
		<td style="" width="75">&nbsp;</td>
		<td style="" width="84">&nbsp;</td>
	</tr>
	<tr height="16" style="height: 12.0pt">
		<td height="16" style="height: 12.0pt; ">
		</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="">&nbsp;</td>
		<td style="" width="96">&nbsp;</td>
		<td style="" width="75">
		&nbsp;</td>
		<td style="" width="84">
		<p align="right"><font size="2" face="Arial">R$&nbsp;<?php echo $rs->fields['valortotal'];?> </font></td>
	</tr>
</table>

</body>
<script>
	window.print();
</script>

</html>