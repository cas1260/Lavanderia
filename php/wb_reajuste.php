<?php
	session_start();
	include 'wb_funcao.php';

	$cn = abrebanco();	
	
	@$acao = $_GET['acao'];
	
	if ($acao == 'processo'){
		//acao=processo
		$ajuste    = $_GET['ajuste'];
		$idcliente = $_GET['idcliente'];
		$idservico = $_GET['idservico'];
		$cadastro  = $_GET['cadastro'];
		
		$ajuste = str_replace(',', '.', $ajuste);
		
		$sql = "update tblclienteservico set valor = valor + ((valor /100) * $ajuste) where idcliente in ($idcliente) and idservico in ($idservico)";
		$cn->open($sql);
		
		$sql = "update tblcliente set reajuste = '20" . date("y-m-d")."' where id in (
			select idcliente from tblclienteservico where idcliente in ($idcliente) and idservico in ($idservico))
		";
		$cn->open($sql);
		
		echo "/* $sql */";
		
		if ($cadastro == 'true'){
			$sql = "update tblservicos set precounitario = precounitario + ((precounitario / 100) * $ajuste) where id in ($idservico)";
			$cn->open($sql);
		}
		echo "MSG('Tabela de preço reajustada com sucesso!');";
		exit();
	}
	

?>

myReajustWinUi = Ext.extend(Ext.Window, {
    height: 443,
    width: 930,
    layout: 'absolute',
    iconCls: 'contasapagar',
    title: 'Reajuste de preço',
    modal: true,

    initComponent: function() {
        Ext.applyIf(this, {
            items: [
                {
                    xtype: 'treepanel',
                    id: 'clientes',
                    height: 360,
                    width: 450,
                    title: 'Clientes',
                    x: 5,
                    y: 5,
					lines: false,
					rootVisible: false,
					autoScroll: true,
					root: new Ext.tree.AsyncTreeNode({
							expanded: true,
							children: [<?php echo montaTreeCliente(0, $cn, "", "true");?>]
							}),
					loader: new Ext.tree.TreeLoader()
                },
                {
                    xtype: 'treepanel',
                    id: 'servicos',
                    height: 360,
                    width: 450,
                    title: 'Serviços',
                    x: 460,
                    y: 5,
					lines: false,
					rootVisible: false,
					autoScroll: true,
					root: new Ext.tree.AsyncTreeNode({
							expanded: true,
							children: [<?php echo montaTreeServico(0, $cn, "", "true");?>]
					}),
					loader: new Ext.tree.TreeLoader()
                },
               {
                    xtype: 'checkbox',
                    id: 'chkcadastro',
                    boxLabel: 'Ajudar o preço no cadastro de serviço também',
                    x: 5,
                    y: 365
                },
                {
                    xtype: 'container',
                    height: 55,
                    width: 260,
                    layout: 'form',
                    x: 555,
                    y: 370,
                    labelWidth: 150,
					labelAlign: 'right',
					items: [
                        {
                            xtype: 'masktextfield',
                            id: 'ajuste',
                            width: 163,
                            anchor: '100%',
                            fieldLabel: 'Aplicar reajuste em %',
							style: 'text-align: right',
							selectOnFocus: true,
							money: true,
							mask: '#0,00'
                        }
                    ]
                },
                {
                    xtype: 'button',
                    id: 'cmdinicio',
                    text: 'Iniciar o processo',
                    x: 820,
                    y: 370
                },
                {
                    xtype: 'checkbox',
                    id: 'chkTodosCliente',
                    boxLabel: 'Selecionar todos',
                    x: 350,
                    y: 10,
					checked : true
                },
                {
                    xtype: 'checkbox',
                    id: 'chkTodosServicos',
                    boxLabel: 'Selecionar todos',
                    x: 805,
                    y: 10,
					checked : true
                }
            ]
        });

        myReajustWinUi.superclass.initComponent.call(this);
    }
});

var myReajustWinUi<?php echo $namespace?> = new myReajustWinUi({
	id : 'myReajustWinUi<?php echo $namespace?>',
	name : 'myReajustWinUi<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
myReajustWinUi<?php echo $namespace?>.show();
setTimeout("OBJ('ajuste').focus();",1000);

addtask('myReajustWinUi<?php echo $namespace?>');

processa<?php echo $namespace?> = function(){
	
	var idservico = '';
	var selNodes = OBJ('servicos').getChecked();
	Ext.each(selNodes, function(node){
		if(idservico.length > 0){
			idservico += ', ';
		}
		idservico += node.id;
	});

	var idcliente = '';
	var selNodes = OBJ('clientes').getChecked();
	Ext.each(selNodes, function(node){
		if(idcliente.length > 0){
			idcliente += ', ';
		}
		idcliente += node.id;
	});
	
	url = './php/wb_reajuste.php?acao=processo&idservico='+idservico+'&idcliente='+idcliente+'&ajuste='+OBJ('ajuste').getValue()+'&cadastro='+OBJ('chkcadastro').getValue();
	
	OPENURL(url);
}

OBJ('cmdinicio').on('click', processa<?php echo $namespace?>);

OBJ('chkTodosCliente').on('check', function(o){
	//alert('click');
	OBJ('clientes').getRootNode().cascade(
		function(oo){
			oo.ui.toggleCheck(o.checked)
		;});
});

OBJ('chkTodosServicos').on('check', function(o){
	//alert('click');
	OBJ('servicos').getRootNode().cascade(
		function(oo){
			oo.ui.toggleCheck(o.checked)
		;});
});