<?php
	session_start();
	include 'wb_funcao.php';
	
	@$id = $_GET['id'];
	$descricao = '';

	if ($id != ''  && $id != '0'){
		
		$cn = abrebanco();
		$rs = $cn->open("select descricao from tblcentro where id = $id");
		
		if ($rs->EOF==true){
			echo "Ext.MessageBox.alert('WebFinan', 'falha na tentativa de acessar o registro!')";
			exit();
		}
		
		$descricao = $rs->fields['descricao'];
	
	}
	
?>

salvadadostela<?php echo $namespace?> = function(){
	if (Ext.getCmp('<?php echo $namespace?>post').getForm().isValid()==true){
		salvardados('php/wb_salva_dados.php?tela=myWindowWclass<?php echo $namespace?>&tabela=tblcentro&id=<?php echo $id?>',Ext.getCmp('<?php echo $namespace?>post').getForm().getFieldValues(true))
	}
}

Wclass<?php echo $namespace?> = Ext.extend(Ext.Window, {
    title: 'Cadastro de centro de custo',
    width: 331,
    height: 142,
    layout: 'fit',
	modal:true,
    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                labelWidth: 100,
                labelAlign: 'left',
                layout: 'absolute',
                width: 390,
                height: 153,
				name: '<?php echo $namespace?>post',
				itemId: '<?php echo $namespace?>post',
				id: '<?php echo $namespace?>post',
                items: [
                    {
                        xtype: 'button',
                        text: 'Salvar',
                        x: 180,
                        y: 75,
                        iconCls: 'salvar',
						handler: salvadadostela<?php echo $namespace?>
                    },
                    {
                        xtype: 'button',
                        text: 'Fechar',
                        x: 250,
                        y: 75,
                        iconCls: 'excluir',
						handler:function(){
							myWindowWclass<?php echo $namespace?>.close();
						}
                    },
                    {
                        xtype: 'label',
                        text: 'Descrição',
                        x: 20,
                        y: 25
                    },
					{
						xtype: 'textfield',
						x: 20,
						y: 40,
						width: 285,
						value: '<?php echo $descricao?>',
						name: '<?php echo $namespace?>descricao',
						itemId: '<?php echo $namespace?>descricao',
						id: '<?php echo $namespace?>descricao'
					}

                ]
            }
        ];
        Wclass<?php echo $namespace?>.superclass.initComponent.call(this);
    }
});

var myWindowWclass<?php echo $namespace?> = new Wclass<?php echo $namespace?>({
	id : 'myWindowWclass<?php echo $namespace?>',
	name : 'myWindowWclass<?php echo $namespace?>',
	renderTo: Ext.getBody()
});
myWindowWclass<?php echo $namespace?>.show();
addtask('myWindowWclass<?php echo $namespace?>');
