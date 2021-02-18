<?php
	session_start();
	include 'wb_funcao.php';
	$cn = abrebanco();

	function buscaOS($txtdatainicial, $txtdatafinal, $idcliente, $cn){
		
		$sqltmp = "SELECT GROUP_CONCAT(a.os) as OS from
					   tblromanei a  
					   where a.data >= '". formatadata($txtdatainicial). "' and a.data <= '".formatadata($txtdatafinal). "' and a.idcliente = $idcliente ";
		//echo "$sqltmp";
		return rsRetornacampo($sqltmp, "OS", $cn);
	}

	
	@$txtdatainicial = $_GET['txtdatainicial'];
	@$txtdatafinal   = $_GET['txtdatafinal'];
	@$idservico      = $_GET['idservico'];
	@$idcliente      = $_GET['idcliente'];
	
	
	
	$sql = "SELECT c.id as idcliente, c.nome, d.codigo, d.descricao, SUM(b.qtd) as qtd, SUM(b.subtotal) as valor FROM 
				   tblromanei a inner join tblitemromanei b on a.id = b.idromanei                   
				   inner join tblcliente     c on a.idcliente = c.id
				   inner join tblservicos    d on b.idservico = d.id
				   where a.data >= '". formatadata($txtdatainicial). "' and a.data <= '".formatadata($txtdatafinal). "' ";
	if (trim($idservico) != ''){
		$sql = $sql . " and d.id in ($idservico) ";
	}
	
	if (trim($idcliente) != ''){
		$sql = $sql . " and c.id in ($idcliente) ";
	}
	$sql = $sql . "	group by c.nome, d.codigo, d.descricao order by c.nome, d.descricao, c.id ";
	
	//echo "/* $sql */";
	
	$rs = $cn->open($sql);
	
	if ($rs->EOF == true){
		echo "MSG('Não há dados para ser exibidos!');";
		exit();
	}
		
	$html = "<html>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=windows-1252'>
			<title>Relatório de serviços prestados</title>
			</head>

			<body>
			<center>
			<table border='0' width='800'>
				<tr>
					<td width='250'><font size='1' face='Verdana'>".date('d/m/Y')." ".date("H:i:s")."</font></td>
					<td >
					<p align='center'><font size='4' face='Verdana'>Relatório de serviços 
					prestados</font></td>
					<td width='250'>
					<p align='right'><font size='1' face='Verdana'>Período: $txtdatainicial - $txtdatafinal</font></td>
				</tr>";
				
	$cliente = '';
	$total   = 0;
	$roda    = false;
	
	$qtd = 0;
	$valoracumulado = 0;
	while ($rs->EOF == false){
		
		if (trim($cliente) != trim($rs->fields['nome'])){
			$total = 0;
			$html = $html . "<tr>
								<td width='788' colspan='3'><font size='2' face='Verdana'>Cliente:".$rs->fields['nome']."</font></td>
							</tr>			
							<tr>
							<td width='794' colspan='3'>
							<table border = '1' width='100%' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='67' bgcolor='#C0C0C0'><font face='Tahoma' size='2' color='#008000'>Código</font></td>
									<td width='536' bgcolor='#C0C0C0'><font face='Tahoma' size='2' color='#008000'>Descrição</font></td>
									<td width='60' align='center' bgcolor='#C0C0C0'><font face='Tahoma' size='2' color='#008000'>Qtd.</font></td>
									<td align='right' bgcolor='#C0C0C0'><font face='Tahoma' size='2' color='#008000'>Valor</font></td>
								</tr>";
		}
		$html = $html . "<tr>
							<td width='67'><font face='Tahoma' size='2'>".$rs->fields['codigo']."</font></td>
							<td width='536'><font face='Tahoma' size='2'>".$rs->fields['descricao']."</font></td>
							<td width='60' align='center'><font face='Tahoma' size='2'>".$rs->fields['qtd']."</font></td>
							<td align='right'><font face='Tahoma' size='2'>".$rs->fields['valor']."</font></td>
						</tr>";

		$total = $total + $rs->fields['valor'];
		$cliente = $rs->fields['nome'];
		$idcliente = $rs->fields['idcliente'];
		$qtd = $qtd+$rs->fields['qtd'];
		$valoracumulado = $valoracumulado + $rs->fields['valor'];
		$rs->MoveNext();
		
		if ($rs->EOF == false){
			if (trim($cliente) != trim($rs->fields['nome'])){
				$html = $html . "<tr>
									<td colspan=2>OS.: ".buscaOS($txtdatainicial, $txtdatafinal, $rs->fields['idcliente'], $cn )."</td>
									<td >
										<p align='right'><font size='2' face='Tahoma'>Total =&gt;</font></td>
										<td align='right'><font size='2' face='Tahoma'>".fn($total)."</font></td>
									</tr>
								</table>
								
								</td>
							</tr>";
			}
		}
	}
	$html = $html . "<tr>
						<td colspan=2>OS.: ".buscaOS($txtdatainicial, $txtdatafinal, $idcliente, $cn )."</td>
						<td>
							<p align='right'><font size='2' face='Tahoma'>Total =&gt;</font>
						</td>
						<td align='right'><font size='2' face='Tahoma'>".fn($total)."</font></td>
						</tr>
					</table>
					
					</td>
				</tr>";
	
	$html = $html. "</table></body>
		Qtd. Total => $qtd  Valor Total ".fn($valoracumulado)."
	</html>";
	
	$html = str_replace(chr(13), '', $html);
	$html = str_replace(chr(10), '', $html);
	$html = str_replace('"', '', $html);
?>

wb_reportUi = Ext.extend(Ext.Window, {
    title: 'Relatório de serviços prestados',
    width: (screen.width - 80),
    height: (screen.height - 300),
    layout: 'fit',
    iconCls: 'print16',
	maximizable: true,
    minimizable: true,
    initComponent: function() {
        this.items = [
            {
                xtype: 'panel',
                autoWidth: true,
                headerAsText: false,
                html: '<iframe style="overflow:auto;width:100%;height:100%;" frameborder="0"  src="" name = "wb_relatorio_contas<?php echo $namespace?>" id =name = "wb_relatorio_contas<?php echo $namespace?>"></iframe>'
            }
        ];
        this.tbar = {
            xtype: 'toolbar',
            items: [
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Imprimir relatorio',
                    iconCls: 'print16',
					handler: printrel<?php echo $namespace?>
                }
            ]
        };
        wb_reportUi.superclass.initComponent.call(this);
    }
});



var wb_report<?php echo $namespace?> = new wb_reportUi({
	id : 'wb_report<?php echo $namespace?>',
	name : 'wb_report<?php echo $namespace?>',
	renderTo: Ext.getBody()
});

   
var win<?php echo $namespace?> = window.open('', 'wb_relatorio_contas<?php echo $namespace?>');

html = "<?php echo $html;?>";

win<?php echo $namespace?>.document.write(html);
win<?php echo $namespace?>.document.close();

wb_report<?php echo $namespace?>.show();

addtask('wb_report<?php echo $namespace?>');

function printrel<?php echo $namespace?>(){
	win<?php echo $namespace?>.print();
}
