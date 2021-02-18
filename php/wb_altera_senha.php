<?php
	session_start();
	include 'wb_funcao.php';

	$cn = abrebanco();

	@$pwd = $_GET['pwd'];
	if ($_SESSION['id'] == ''){
		exit();
	}
	if ($pwd != ''){
		$_SESSION['senha'] = $pwd;
		$cn->execute("Update tblusuario Set senha ='" . $pwd . "' where id = " . $_SESSION['id']);
		echo "MSG('Senha alterada com sucesso!', function(){Ext.getCmp('wb_alterarsenha').close();})";
		exit();
	}else{
		verAcesso($cn);
	}
?>

function alterasenha(){
	senhaantiga   = Ext.getCmp('<?php echo $namespace?>antiga').getValue();
	senhanova     = Ext.getCmp('<?php echo $namespace?>novo').getValue();
	senhaconfirma = Ext.getCmp('<?php echo $namespace?>confirma').getValue();
	
	if (senhaantiga == ''){
		MSG('A senha antiga não pode ser em branco.', function(){Ext.getCmp('<?php echo $namespace?>antiga').focus();});
		return true;
	}
	if (senhanova == ''){
		MSG('A nova senha não pode ser em branco.', function(){Ext.getCmp('<?php echo $namespace?>novo').focus();});
		return true;
	}
	if (senhaconfirma == ''){
		MSG('A confirmação da senha não pode ser em branco.', function(){Ext.getCmp('<?php echo $namespace?>confirma').focus();});
		return true;
	}
	
	if (senhaantiga != '<?php echo $_SESSION['senha']?>'){
		MSG('Senha antiga não confere!', function(){
			Ext.getCmp('<?php echo $namespace?>antiga').focus();
		});
		return true;
	}

	if (senhanova != senhaconfirma){
		MSG('Senha nao conferi com a sua confirmação.', function(){
			Ext.getCmp('<?php echo $namespace?>novo').focus();
		});
		return true;
	}
	OpenUrl('php/wb_altera_senha.php?pwd='+senhanova);
	
}

wb_alterarsenhaUi = Ext.extend(Ext.Window, {
    title: 'Alterar senha',
    width: 340,
    height: 155,
    layout: 'absolute',
    modal: true,
    buttonAlign: 'center',
    titleCollapse: true,
    resizable: false,
    initComponent: function() {
        this.items = [
            {
                xtype: 'container',
                width: 310,
                height: 85,
                x: 10,
                y: 10,
                layout: 'form',
                labelAlign: 'right',
                labelWidth: 110,
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Senha antiga',
                        anchor: '100%',
                        itemId: '<?php echo $namespace?>antiga',
                        name: '<?php echo $namespace?>antiga',
                        inputType: 'password',
                        id: '<?php echo $namespace?>antiga'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Nova senha',
                        anchor: '100%',
                        itemId: '<?php echo $namespace?>novo',
                        name: '<?php echo $namespace?>novo',
                        inputType: 'password',
                        id: '<?php echo $namespace?>novo'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Confirme a senha',
                        anchor: '100%',
                        itemId: '<?php echo $namespace?>confirma',
                        name: '<?php echo $namespace?>confirma',
                        inputType: 'password',
                        id: '<?php echo $namespace?>confirma'
                    }
                ]
            },
            {
                xtype: 'button',
                text: 'Cancelar',
                x: 225,
                y: 90,
                width: 95,
				handler: function(){
					Ext.getCmp('wb_alterarsenha').close();
				}
            },
            {
                xtype: 'button',
                text: 'Salvar',
                x: 125,
                y: 90,
                width: 95,
				handler: alterasenha
            }
        ];
        wb_alterarsenhaUi.superclass.initComponent.call(this);
    }
});


var wb_alterarsenha = new wb_alterarsenhaUi({
	id : 'wb_alterarsenha',
	name : 'wb_alterarsenha',
	renderTo: Ext.getBody()
});

wb_alterarsenha.show();
addtask('wb_alterarsenha');
