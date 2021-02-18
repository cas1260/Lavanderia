<?php
	session_start();
	include 'wb_funcao.php';
	@$id    = $_GET['id'];
	$dados  = false;
	$cn = abrebanco();	
	
	if ($id != '' and $id != '0' ){
		
		$sql = "select id, codigo, nome, obs from tblmotorista where id = $id";
		

		$rs = $cn->open($sql);
		if ($rs->EOF==true){
			echo "Ext.MessageBox.alert('WebFinan', 'falha na tentativa de acessar o registro id $id!')";
			exit();
		}
		$dados = true;
	}

?>	
function salvadados<?php echo $namespace?>(){
	if (Ext.getCmp('<?php echo $namespace?>post').getForm().isValid()==true){
		salvardados('php/wb_salva_dados.php?tela=wb_cadmotoristaUi<?php echo $namespace?>&tabela=tblmotorista&id=<?php echo $id?>',Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))
	}else{
		MSG('Favor preecher os campos em vermelho.');
	}
}


cadmotoristaUi = Ext.extend(Ext.Window, {
    title: 'Cadastro de motorista',
    width: 407,
    height: 212,
	iconCls:'engineering16', 
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                width: 393,
                height: 180,
                layout: 'absolute',
                itemId: '<?php echo $namespace?>post',
                id: '<?php echo $namespace?>post',
                items: [
                    {
                        xtype: 'container',
                        width: 420,
                        height: 135,
                        x: -36,
                        y: 15,
                        layout: 'form',
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Código',
                                anchor: '100%',
                                boxMaxWidth: 100,
                                itemId: '<?php echo $namespace?>codigo',
                                name: '<?php echo $namespace?>codigo',
                                allowBlank: false,
                                id: '<?php echo $namespace?>codigo'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nome',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>nome',
                                name: '<?php echo $namespace?>nome',
                                allowBlank: false,
                                id: '<?php echo $namespace?>nome'
                            },
                            {
                                xtype: 'textarea',
                                anchor: '100%',
                                fieldLabel: 'Obs.',
                                itemId: '<?php echo $namespace?>obs',
                                name: '<?php echo $namespace?>obs',
                                id: '<?php echo $namespace?>obs'
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        text: 'Salvar',
                        x: 310,
                        y: 145,
                        width: 75,
						handler: salvadados<?php echo $namespace?>
					},
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 65,
                        y: 145,
                        width: 75,
						handler: function(){
							Ext.getCmp('wb_cadmotoristaUi<?php echo $namespace?>').close();
						}
                    }
                ]
            }
        ];
        cadmotoristaUi.superclass.initComponent.call(this);
    }
});

var wb_cadmotoristaUi<?php echo $namespace?> = new cadmotoristaUi({
	id : 'wb_cadmotoristaUi<?php echo $namespace?>',
	name : 'wb_cadmotoristaUi<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
wb_cadmotoristaUi<?php echo $namespace?>.show();
addtask('wb_cadmotoristaUi<?php echo $namespace?>');
setTimeout("Ext.getCmp('<?php echo $namespace?>nome').focus();",1000);

<?php
	if ($dados == true){
		exibidados($rs->fields, 'codigo, nome, obs', $namespace);
	}
	if ($id == '' or $id == '0' ){
		echo "Ext.getCmp('".$namespace."codigo').setValue('".Ultimo('codigo', 'tblmotorista', $cn)."');";
	}
?>	
