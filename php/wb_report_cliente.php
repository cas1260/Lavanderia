<?php
	// ALTER TABLE `tblcliente` ADD `obs1` TEXT NOT NULL ;
	session_start();
	include 'wb_funcao.php';

	$cn = abrebanco();	

?>

selcliall<?php echo $namespace?> = function(){

	
}

processa<?php echo $namespace?> = function(){
	
	txtdatainicial = OBJ('<?php echo $namespace?>txtdatainicial');
	txtdatafinal   = OBJ('<?php echo $namespace?>txtdatafinal');
	
	if (txtdatainicial.isValid() == false){
		txtdatainicial.focus();
		return false;
	}
	
	if (txtdatafinal.isValid() == false){
		txtdatafinal.focus();
		return false;
	}
	
	
	var idservico = '';
	var selNodes = Ext.getCmp('<?php echo $namespace?>servico').getChecked();
	Ext.each(selNodes, function(node){
		if(idservico.length > 0){
			idservico += ', ';
		}
		idservico += node.id;
	});

	var idcliente = '';
	var selNodes = Ext.getCmp('<?php echo $namespace?>cliente').getChecked();
	Ext.each(selNodes, function(node){
		if(idcliente.length > 0){
			idcliente += ', ';
		}
		idcliente += node.id;
	});

	url = './php/wb_report.php?txtdatainicial='+ txtdatainicial.getRawValue()+'&txtdatafinal='+txtdatafinal.getRawValue()+'&idservico='+idservico+'&idcliente='+idcliente;
	
	OPENURL(url);
	
}


wb_relatorioUi = Ext.extend(Ext.Window, {
   title: 'Relatório de serviços prestados',
    width: 618,
    height: 399,
    iconCls: 'contas16',
    layout: 'absolute',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                x: 0,
                y: 0,
                width: 600,
                height: 260,
                itemId: '<?php echo $namespace?>post',
                layout: 'absolute',
                anchor: '100% 100%',
                id: '<?php echo $namespace?>post',
                items: [
                    {
                        xtype: 'container',
                        x: 190,
                        y: 10,
                        width: 235,
                        height: 25,
                        layout: 'form',
                        labelWidth: 120,
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Data Inicial',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>txtdatainicial',
                                name: '<?php echo $namespace?>txtdatainicial',
                                boxMaxWidth: 100,
                                tabIndex: 1,
                                format: 'd/m/Y',
                                id: '<?php echo $namespace?>txtdatainicial',
								selectOnFocus:true,
								allowBlank: false
                            }
                        ]
                    },
                    {
                        xtype: 'container',
                        x: 385,
                        y: 10,
                        width: 215,
                        height: 35,
                        layout: 'form',
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Data Final',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>txtdatafinal',
                                name: '<?php echo $namespace?>txtdatafinal',
                                boxMaxWidth: 100,
                                tabIndex: 1,
                                format: 'd/m/Y',
								selectOnFocus:true,
                                id: '<?php echo $namespace?>txtdatafinal',
								allowBlank: false
                            }
                        ]
                    },
					{
                        xtype: 'container',
                        x: 10,
                        y: 10,
                        width: 130,
                        height: 110,
                        html: "<img src = 'images/frm/contas.png'>"
                    },
                    {
						xtype: 'fieldset',
                        title: 'Clientes:',
                        x: 142,
                        y: 38,
                        width: 445,
                        height: 145,
                        layout: 'absolute',
                        items: [
                            {
                                xtype: 'treepanel',
                                title: '',
                                x: 0,
                                y: 0,
                                anchor: '100% 100%',
                                lines: false,
                                rootVisible: false,
                                autoScroll: true,
                                id: '<?php echo $namespace?>cliente',
                                root: new Ext.tree.AsyncTreeNode({
										expanded: true,
										children: [<?php echo montaTreeCliente(0, $cn, "");?>]
										}),
								loader: new Ext.tree.TreeLoader()
                            }
                        ]
                    },
                    {
                        xtype: 'fieldset',
                        title: 'Serviços:',
                        x: 141,
                        y: 189,
                        width: 445,
                        height: 145,
                        layout: 'absolute',
                        items: [
                            {
                                xtype: 'treepanel',
                                title: '',
                                x: 0,
                                y: 0,
                                anchor: '100% 100%',
                                lines: false,
                                rootVisible: false,
                                autoScroll: true,
                                id: '<?php echo $namespace?>servico',
                                root: new Ext.tree.AsyncTreeNode({
										expanded: true,
										children: [<?php echo montaTreeServico(0, $cn, "");?>]
								}),
								loader: new Ext.tree.TreeLoader()
                            }
                        ]
                    },					
					{
						xtype: 'button',
                        text: 'Pesquisar',
                        x: 139,
                        y: 339,
                        width: 90,
                        tabIndex: 11,
                        itemId: '<?php echo $namespace?>pesq',
                        id: '<?php echo $namespace?>pesq'
                    },
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 496,
                        y: 339,
                        width: 90,
                        tabIndex: 12,
                        itemId: '<?php echo $namespace?>cancel',
                        id: '<?php echo $namespace?>cancel'
                    },
					{
                        xtype: 'checkbox',
                        boxLabel: 'Selecionar todos',
                        x: 480,
                        y: 43,
                        id: '<?php echo $namespace?>chkSelTodosCliente'
                    },
                    {
                        xtype: 'checkbox',
                        x: 479,
                        y: 194,
                        boxLabel: 'Selecionar todos',
                        id: '<?php echo $namespace?>chkSelTodosServico'
                    },
                    {
                        xtype: 'textfield',
                        x: 207,
                        y: 36,
                        width: 263,
                        id: '<?php echo $namespace?>pesqcliente'
                    },
                    {
                        xtype: 'textfield',
                        x: 208,
                        y: 186,
                        width: 263,
                        id: '<?php echo $namespace?>pesqservico'
                    }
                ]
            }
        ];
        wb_relatorioUi.superclass.initComponent.call(this);
    }
});


var wb_relatorioUi<?php echo $namespace?> = new wb_relatorioUi({
	id : 'wb_relatorioUi<?php echo $namespace?>',
	name : 'wb_relatorioUi<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
wb_relatorioUi<?php echo $namespace?>.show();
setTimeout("Ext.getCmp('<?php echo $namespace?>txtdatainicial').focus();",1000);



addtask('wb_relatorioUi<?php echo $namespace?>');
OBJ('<?php echo $namespace?>pesq').on('click', processa<?php echo $namespace?>);
OBJ('<?php echo $namespace?>cancel').on('click', function(){OBJ('wb_relatorioUi<?php echo $namespace?>').close();});


OBJ('<?php echo $namespace?>pesqcliente').on('change', function(obj){pesquisaTreeNode('<?php echo $namespace?>cliente', '<?php echo $namespace?>pesqcliente')});
OBJ('<?php echo $namespace?>pesqcliente').on('blur', function(obj){pesquisaTreeNode('<?php echo $namespace?>cliente', '<?php echo $namespace?>pesqcliente')});
OBJ('<?php echo $namespace?>pesqcliente').on('keydown', function(obj){pesquisaTreeNode('<?php echo $namespace?>cliente', '<?php echo $namespace?>pesqcliente')});
OBJ('<?php echo $namespace?>pesqservico').on('keydown', function(obj){pesquisaTreeNode('<?php echo $namespace?>servico', '<?php echo $namespace?>pesqservico')});
OBJ('<?php echo $namespace?>pesqservico').on('blur', function(obj){pesquisaTreeNode('<?php echo $namespace?>servico', '<?php echo $namespace?>pesqservico')});
OBJ('<?php echo $namespace?>pesqservico').on('change', function(obj){pesquisaTreeNode('<?php echo $namespace?>servico', '<?php echo $namespace?>pesqservico')});

OBJ('<?php echo $namespace?>pesqservico').on('keypress', function(obj, e){
	pesquisaTreeNode('<?php echo $namespace?>servico', '<?php echo $namespace?>pesqservico')
	});



OBJ('<?php echo $namespace?>chkSelTodosCliente').on('check', fMascarTodosCliente<?php echo $namespace?>);
OBJ('<?php echo $namespace?>chkSelTodosServico').on('check', fMascarTodosServico<?php echo $namespace?>);

function fMascarTodosCliente<?php echo $namespace?>(obj){
	o = OBJ("<?php echo $namespace?>cliente")
	o.root.cascade(function(a){
								try {
									a.getUI().checkbox.checked=obj.checked;
								} catch(e) {
								
								}
							   });
}

function fMascarTodosServico<?php echo $namespace?>(obj){
	o = OBJ("<?php echo $namespace?>servico")
	o.root.cascade(function(a){
								try {
									a.getUI().checkbox.checked=obj.checked;
								} catch(e) {
								
								}
							   });
}
