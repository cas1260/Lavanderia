<?php
	session_start();
	include 'wb_funcao.php';
	@$id    = $_GET['id'];
	$dados  = false;
	$cn = abrebanco();	
	
	if ($id != '' and $id != '0' ){
		
		$sql = "select  id, codigo, descricao, unidade, precounitario, desconto, qtd, imposto from tblservicos where id = $id";
		

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
		salvardados('php/wb_salva_dados.php?ref=<?php echo $namespace?>&tela=wb_cadastroservicos<?php echo $namespace?>&tabela=tblservicos&id=<?php echo $id?>',Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))
	}else{
		MSG('Favor preecher os campos em vermelho.');
	}
}

cadserverUi = Ext.extend(Ext.Window, {
    title: 'Cadastro de serviço',
    width: 525,
    height: 307,
    modal: true,
    iconCls: 'servicos16',
    layout: 'absolute',
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                title: '',
                x: -1,
                y: -2,
                width: 531,
                height: 281,
                layout: 'absolute',
                border: false,
				itemId: '<?php echo $namespace?>post',
				id: '<?php echo $namespace?>post',
                items: [
                    {
                        xtype: 'fieldset',
                        title: '',
                        x: 14,
                        y: 9,
                        width: 490,
                        height: 231,
                        labelAlign: 'right',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Codigo',
                                anchor: '100%',
                                boxMaxWidth: 70,
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
                                fieldLabel: 'Preço Unitario',
                                anchor: '100%',
                                boxMaxWidth: 70,
                                itemId: '<?php echo $namespace?>precounitario',
                                name: '<?php echo $namespace?>precounitario',
                                allowBlank: false,
                                allowNegative: false,
                                id: '<?php echo $namespace?>precounitario',
								money: true,
								mask: '#9.999.990,00'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Unidade',
                                anchor: '100%',
                                boxMaxWidth: 70,
                                itemId: '<?php echo $namespace?>unidade',
                                name: '<?php echo $namespace?>unidade',
                                id: '<?php echo $namespace?>unidade'
                            },
                            {
                                xtype: 'masktextfield',
                                fieldLabel: 'Desconto',
                                anchor: '100%',
                                boxMaxWidth: 70,
                                itemId: '<?php echo $namespace?>desconto',
                                name: '<?php echo $namespace?>desconto',
                                allowBlank: false,
                                allowNegative: false,
                                id: '<?php echo $namespace?>desconto',
								money: true,
								mask: '999.999,99'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'QTD',
                                anchor: '100%',
                                boxMaxWidth: 70,
                                itemId: '<?php echo $namespace?>qtd',
                                name: '<?php echo $namespace?>qtd',
                                id: '<?php echo $namespace?>qtd'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Imposto',
                                anchor: '100%',
                                boxMaxWidth: 70,
                                itemId: '<?php echo $namespace?>imposto',
                                name: '<?php echo $namespace?>imposto',
                                id: '<?php echo $namespace?>imposto'
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        text: 'Cancelar',
                        x: 14,
                        y: 244,
                        width: 98
                    },
                    {
                        xtype: 'button',
                        text: 'Salvar',
                        x: 407,
                        y: 244,
                        width: 98,
						handler: salvadados<?php echo $namespace?>
                    }
                ]
            }
        ];
        cadserverUi.superclass.initComponent.call(this);
    }
});

var wb_cadastroservicos<?php echo $namespace?> = new cadserverUi({
	id : 'wb_cadastroservicos<?php echo $namespace?>',
	name : 'wb_cadastroservicos<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
wb_cadastroservicos<?php echo $namespace?>.show();

setTimeout("Ext.getCmp('<?php echo $namespace?>descricao').focus();",1000);


<?php
	if ($dados == true){
		exibidados($rs->fields, 'codigo, descricao, unidade, precounitario, desconto, qtd, imposto', $namespace);
	}
	if ($id == '' or $id == '0' ){
		echo "Ext.getCmp('".$namespace."codigo').setValue('".Ultimo('codigo', 'tblservicos', $cn)."');";
	}
?>	
