<?php
	session_start();
	include 'wb_funcao.php';
	
	$id     = $_GET['id'];
	$dados  = false;
	$cn = abrebanco();
	
	if ($id != '' and $id != '0' ){
		
		$rs = $cn->open("select * from tblmercadoria where id = $id");
		
		if ($rs->EOF==true){
			echo "Ext.MessageBox.alert('WebFinan', 'falha na tentativa de acessar o registro id $id!')";
			exit();
		}
		$dados = true;
	}
?>

salvadadostela<?php echo $namespace?> = function(){
	if (Ext.getCmp('<?php echo $namespace?>post').getForm().isValid()==true){
		salvardados('php/wb_salva_dados.php?tela=cadmercadoria_ux<?php echo $namespace?>&tabela=tblmercadoria&id=<?php echo $id?>',Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))
	}
}
cadmercadoriaUi = Ext.extend(Ext.Window, {
    title: 'Cadastro de mercadoria',
    width: 408,
    height: 295,
    iconCls: 'mercadoria16',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                width: 400,
                height: 270,
                layout: 'absolute',
                itemId: '<?php echo $namespace?>post',
                id: '<?php echo $namespace?>post',
                items: [
                    {
                        xtype: 'container',
                        x: -36,
                        y: 15,
                        width: 420,
                        height: 220,
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
                            },
                            {
                                xtype: 'masktextfield',
                                fieldLabel: 'Valor',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>valor',
                                name: '<?php echo $namespace?>valor',
                                allowBlank: false,
                                id: '<?php echo $namespace?>valor',
								decimalPrecision: 3,
								decimalSeparator: ',',
								ForceDecimalPrecision: false,
								style: 'text-align: right',
								money: true,
								mask: '#9.999.990,00'
							
                            },
                            {
                                xtype: 'numberfield',
                                fieldLabel: 'Qtd atual',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>qtd',
                                name: '<?php echo $namespace?>qtd',
                                allowBlank: false,
                                id: '<?php echo $namespace?>qtd',
								preventMark: true
                            },
                            {
                                xtype: 'numberfield',
                                fieldLabel: 'Qtd min.',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>qtdminimo',
                                name: '<?php echo $namespace?>qtdminimo',
                                allowBlank: false,
                                id: '<?php echo $namespace?>qtdminimo'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Unidade',
                                anchor: '100%',
                                itemId: '<?php echo $namespace?>unidade',
                                name: '<?php echo $namespace?>unidade',
                                allowBlank: false,
                                id: '<?php echo $namespace?>unidade'
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
                        x: 284,
                        y: 235,
                        width: 100,
						handler: salvadadostela<?php echo $namespace?>
                    },
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 69,
                        y: 235,
                        width: 100,
						handler: function(){
							Ext.getCmp('cadmercadoria_ux<?php echo $namespace?>').close();
						}
                    }
                ]
            }
        ];
        cadmercadoriaUi.superclass.initComponent.call(this);
    }
});



var cadmercadoria_ux<?php echo $namespace?> = new cadmercadoriaUi({
	id : 'cadmercadoria_ux<?php echo $namespace?>',
	name : 'cadmercadoria_ux<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
cadmercadoria_ux<?php echo $namespace?>.show();
addtask('cadmercadoria_ux<?php echo $namespace?>');

setTimeout("Ext.getCmp('<?php echo $namespace?>descricao').focus();",1000);


<?php
	if ($dados == true){
		exibidados($rs->fields, 'codigo, descricao, qtd, qtdminimo, valor, unidade, obs', $namespace);
	}
	if ($id == '' or $id == '0' ){
		echo "Ext.getCmp('".$namespace."codigo').setValue('".Ultimo('codigo', 'tblmercadoria', $cn)."');";
	}
?>	
