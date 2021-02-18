<?php
	session_start();
	include 'wb_funcao.php';
	include 'wb_zip.php';
	
	@$acao = $_GET['acao'];
	
	$cn = abrebanco();
	
	if ($acao == 'backup'){
		
		backup();
	
		exit();
	}
	verAcesso($cn);

	?>	

function backup(){
	
	chkmail      = Ext.getCmp('chkmail').getValue();
	chkdownload  = Ext.getCmp('chkdownload').getValue();
	txtemail     = Ext.getCmp('txtemail').getValue();

	Ext.MessageBox.wait('Aguarde, Fazendo backup de sua base de dados<br>Este procedimento pode demorar um pouco.', 'WebFinan');
	
	Ext.Ajax.request({
					url : 'php/wb_backup.php?acao=backup&chkmail='+chkmail+'&chkdownload='+chkdownload+'&txtemail='+txtemail,
					method: 'POST',
					params : {},
					success: function (result, request) {
						xcomando = result.responseText;
						Ext.MessageBox.hide();
						RunJavaScript(xcomando);
					},
					failure: function ( result, request ) {
								alert('Falha ');
					}
					});
}
	

wb_backupUi = Ext.extend(Ext.Window, {
    title: 'Backup',
    width: 403,
    height: 180,
    layout: 'absolute',
    hideBorders: true,
    border: false,
    modal: true,
    initComponent: function() {
        this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                x: 0,
                y: -1,
                height: 155,
                width: 400,
                items: [
                    {
                        xtype: 'panel',
                        title: 'Backup',
                        width: 404,
                        height: 143,
                        layout: 'absolute',
                        iconCls: 'backup16',
                        labelWidth: 40,
                        border: false,
                        items: [
                            {
                                xtype: 'container',
                                width: 429,
                                height: 117,
                                layout: 'form',
                                x: -42,
                                y: 14,
                                labelAlign: 'right',
                                items: [
                                    {
                                        xtype: 'checkbox',
                                        boxLabel: 'Enviar por e-mail',
                                        itemId: 'chkmail',
                                        name: 'chkmail',
                                        id: 'chkmail'
                                    },
                                    {
                                        xtype: 'checkbox',
                                        boxLabel: 'Download do Arquivo',
                                        checked: true,
                                        itemId: 'chkdownload',
                                        name: 'chkdownload',
                                        id: 'chkdownload'
                                    },
                                    {
                                        xtype: 'textfield',
                                        width: 323,
                                        fieldLabel: 'E-mail',
                                        itemId: 'txtemail',
                                        name: 'txtemail',
                                        id: 'txtemail'
                                    }
                                ]
                            },
                            {
                                xtype: 'button',
                                text: 'Fazer backup',
                                x: 63,
                                y: 85,
                                width: 130,
                                height: 22,
								handler: backup
								
                            },
                            {
                                xtype: 'button',
                                text: 'Cancelar',
                                x: 255,
                                y: 86,
                                width: 130,
                                height: 22
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        title: 'Restaurar backup',
                        width: 397,
                        height: 135,
                        layout: 'absolute',
                        border: false,
                        iconCls: 'restore16',
                        items: [
                            {
                                xtype: 'container',
                                width: 405,
                                height: 25,
                                layout: 'form',
                                x: -42,
                                y: 14,
                                labelAlign: 'right',
                                labelWidth: 150,
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Local do arquivo',
                                        anchor: '100%',
                                        itemId: 'txtrestaura',
                                        name: 'txtrestaura',
                                        id: 'txtrestaura'
                                    }
                                ]
                            },
                            {
                                xtype: 'button',
                                text: 'Restaurar backup',
                                x: 63,
                                y: 85,
                                width: 130,
                                height: 22
                            },
                            {
                                xtype: 'button',
                                text: 'Cancelar',
                                x: 255,
                                y: 86,
                                width: 130,
                                height: 22
                            },
                            {
                                xtype: 'button',
                                text: '...',
                                x: 370,
                                y: 15
                            }
                        ]
                    }
                ]
            }
        ];
        wb_backupUi.superclass.initComponent.call(this);
    }
});

var wb_backup<?php echo $namespace?> = new wb_backupUi({
	id : 'wb_backup<?php echo $namespace?>',
	name : 'wb_backup<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
wb_backup<?php echo $namespace?>.show();
addtask('wb_backup<?php echo $namespace?>');
