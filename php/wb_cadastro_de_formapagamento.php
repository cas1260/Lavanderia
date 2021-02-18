<?php
	session_start();
	include 'wb_funcao.php';
	
	@$id = $_GET['id'];
	$dados = false;
	
	if ($id != '0'){
		$dados = true;
		$cn = abrebanco();
		$rs = $cn->open("select descricao, tipo, obs from tblforma where id = $id");
		
		if ($rs->EOF==true){
			echo "Ext.MessageBox.alert('WebFinan', 'falha na tentativa de acessar o registro!')";
			exit();
		}
		
	}
	
?>

salvadadostela<?php echo $namespace?> = function(){
	if (Ext.getCmp('<?php echo $namespace?>post').getForm().isValid()==true){
		salvardados('php/wb_salva_dados.php?tela=myWindowWclass<?php echo $namespace?>&tabela=tblforma&id=<?php echo $id?>',Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))
	}
}

stored_tipo<?php echo $namespace;?> = [
	[0, 'A Vista'],
	[1, '15 dias'],
	[2, '30 dias'],
	[3, '45 dias'],
	[4, '60 dias'], 
	[5, '75 dias'], 
	[6, '90 dias'] 
];

wbformapgUi = Ext.extend(Ext.Window, {
    title: 'Forma de pagamento',
    width: 500,
    height: 250,
    layout: 'anchor',
    iconCls: 'formapagamento16',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                anchor: '100 100',
                layout: 'absolute',
                id: '<?php echo $namespace?>post',
                items: [
                    {
                        xtype: 'container',
                        width: 460,
                        height: 50,
                        x: 10,
                        y: 10,
                        layout: 'form',
                        labelAlign: 'right',
                        labelWidth: 150,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Descrição',
                                anchor: '100%',
                                allowBlank: false,
                                name: '<?php echo $namespace?>descricao',
                                id: '<?php echo $namespace?>descricao'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Forma de pagamento',
                                anchor: '100%',
                                allowBlank: false,
                                triggerAction: 'all',
                                name: '<?php echo $namespace?>tipo',
                                id: '<?php echo $namespace?>tipo'
                            },
							{
                                xtype: 'textarea',
                                fieldLabel: 'Obs.:',
                                anchor: '100%',
                                height: 115,
                                itemId: '<?php echo $namespace?>obs',
                                name: '<?php echo $namespace?>obs',
                                id: '<?php echo $namespace?>obs'
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        text: 'Salvar',
                        x: 220,
                        y: 185,
                        width: 120,
                        height: 22,
						handler: salvadadostela<?php echo $namespace?>
                    },
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 350,
                        y: 185,
                        width: 120,
                        height: 22,
						handler: function(){Ext.getCmp('myWindowWclass<?php echo $namespace?>').close();}
                    }
                ]
            }
        ];
        wbformapgUi.superclass.initComponent.call(this);
    }
});


var myWindowWclass<?php echo $namespace?> = new wbformapgUi({
	id : 'myWindowWclass<?php echo $namespace?>',
	name : 'myWindowWclass<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
myWindowWclass<?php echo $namespace?>.show();

addtask('myWindowWclass<?php echo $namespace?>');

setTimeout("Ext.getCmp('<?php echo $namespace?>descricao').focus();",1000);



<?php
	echo "/* $dados */";
	if ($dados == true){
		exibidados($rs->fields, 'descricao, tipo, obs', $namespace);
	}
?>	
