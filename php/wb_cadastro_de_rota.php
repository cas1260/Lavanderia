<?php
	session_start();
	include 'wb_funcao.php';
	@$id    = $_GET['id'];
	$dados  = false;
	$cn = abrebanco();	
	
	if ($id != '' and $id != '0' ){
		
		$sql = "select id, codigo, descricao, obs, grupo from tblrota where id = $id";
		

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
		salvardados('php/wb_salva_dados.php?tela=wb_cadrotaUi<?php echo $namespace?>&tabela=tblrota&id=<?php echo $id?>',Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))
	}else{
		MSG('Favor preecher os campos em vermelho.');
	}
}


cadrotaUi = Ext.extend(Ext.Window, {
    title: 'Cadastro de rota',
    width: 407,
    height: 220,
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                width: 393,
                height: 195,
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
                                fieldLabel: 'Descrição',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>descricao',
                                name: '<?php echo $namespace?>descricao',
                                allowBlank: false,
                                id: '<?php echo $namespace?>descricao'
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Grupo',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>grupo',
                                name: '<?php echo $namespace?>grupo',
                                allowBlank: true,
                                id: '<?php echo $namespace?>grupo'
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
                        x: 307,
                        y: 157,
                        width: 75,
						handler: salvadados<?php echo $namespace?>
					},
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 67,
                        y: 157,
                        width: 75, 
						handler: function(){Ext.getCmp('wb_cadrotaUi<?php echo $namespace?>').close()}
						
                    }
                ]
            }
        ];
        cadrotaUi.superclass.initComponent.call(this);
    }
});

var wb_cadrotaUi<?php echo $namespace?> = new cadrotaUi({
	id : 'wb_cadrotaUi<?php echo $namespace?>',
	name : 'wb_cadrotaUi<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
wb_cadrotaUi<?php echo $namespace?>.show();

setTimeout("Ext.getCmp('<?php echo $namespace?>descricao').focus();",1000);

<?php
	if ($dados == true){
		exibidados($rs->fields, 'codigo, descricao, obs, grupo', $namespace);
	}
	if ($id == '' or $id == '0' ){
		echo "Ext.getCmp('".$namespace."codigo').setValue('".Ultimo('codigo', 'tblrota', $cn)."');";
	}
?>	
