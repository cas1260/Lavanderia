Ext.ns('Ext.ux');

Ext.ux.LogoComponent = Ext.extend(Ext.BoxComponent, {
    onRender: function(ct, position) {
        this.el = ct.createChild({ tag: 'div', cls: "app-logo" });
    }
});

Ext.ux.login = Ext.extend(Ext.Window,{
		 iconCls	: 'ico-cadeado'
		,layout		: 'form'
		,title		: 'WebLuvas 1.0 - Autentica&#231;&#227;o'
		,closable	: false
		,constrain	: true
		,width      : 607
		,height     : 380
		,resizable  : false
		,border     : false
		,bodyStyle  : 'background-color:#FFF'
		,initComponent: function()
		{
			if(Ext.isString(this.loginField))
				this.loginField = {fieldLabel: this.loginField};
			
			if(Ext.isString(this.senhaField))
				this.senhaField = {fieldLabel: this.senhaField};
			
			Ext.apply(this,{
				items	: [
                    new Ext.ux.LogoComponent()
					,{
					    id      : 'FormColumn',
					    layout  : 'column',
					    border  : false,
					    defaults : {
					        border  : false
					    },
					    items   : [{
					        columnWidth : .62,
					        itemId      : 'column1',
					        html        : '<img class="imgLogo" src="images/novalogo.jpg" border="0" alt="" /><br><p class="texto"><br />Copyright 2010 - WebLuvas<br />Todos os Direitos Reservados</p>',
					        style       : 'padding-left: 0px'
					    },{
					        columnWidth : .38,
					        itemId      : 'column2',
					        layout      : 'form',
					        labelWidth  : 45,
						//title       : 'Acesso ao sistema',
						//border      : true,
					        bodyStyle   : 'padding: 15px',
					        items       : [
					            Ext.apply({
					                xtype: 'textfield'
							    , name:'login'
						            , fieldLabel: 'Usuário'
						            , emptyText: 'Informe seu Usuário'
						            , msgTarget: 'side'
						            , itemId: 'txtLogin'
						            , allowBlank: false
						            , selectOnFocus: true
						            , enableKeyEvents: true
						            , listeners: {
						                scope: this
							            , 'keyup': this._onTxtKeyUp
						            }
					            }, this.loginField)
					            ,
					            Ext.apply({
					                xtype: 'textfield'
							    , name: 'senha'
						            , inputType: 'password'
						            , fieldLabel: 'Senha'
						            , emptyText: '*fakepass*'
						            , msgTarget: 'side'
						            , itemId: 'txtSenha'
						            , allowBlank: false
						            , selectOnFocus: false
						            , enableKeyEvents: true
						            , listeners: {
						                scope: this
							            , 'keyup': this._onTxtKeyUp
						            }
					            }, this.senhaField)
                            ]					        
					    }]
                    }
				]
				,buttons: [{
					 xtype	: 'button'
					,text	: 'Entrar'
					,iconCls: 'ico-app-go'
					,scope	: this
					,handler: this._onBtnEntrarClick
				},{
					 xtype	: 'button'
					,text	: 'Lembrar Senha'
					,iconCls: 'ico-cadeado'
					,scope	: this
					,handler: this._onBtnLembrarClick				
				}]
			});
			
			Ext.ux.login.superclass.initComponent.call(this);
		}
		,_onTxtKeyUp: function(txt,e)
		{
			if(e.getKey() === e.ENTER)
			{
				e.stopEvent();
				this._onBtnEntrarClick();
			}
		}		
		,_onBtnEntrarClick: function()
		{
			var txtLogin = this.getComponent('FormColumn').getComponent('column2').getComponent('txtLogin');
			var txtSenha = this.getComponent('FormColumn').getComponent('column2').getComponent('txtSenha');
			
			if(!txtLogin.isValid() && !txtSenha.isValid())
				return false;
			
			this.buttons[0].disable();
			this.buttons[1].disable();
			Ext.MessageBox.wait('Aguarde validando informações de acesso.', 'WebLuvas 1.0');
			Ext.Ajax.request({
				 url	: this.url
				,method	: 'POST'
				,scope	: this
				,params	: Ext.applyIf({
					 login: txtLogin.getValue()
					,senha: txtSenha.getValue()
				},this.params)
				,success: function(response)
				{
					try {
						Ext.MessageBox.hide();
						response = Ext.decode(response.responseText);	
						if( response.success )
						{
							this.el.mask();
							window.location.href = response.redirect||response.message;
						}
						else
						{
							Ext.Msg.show({
								title: 'Ocorreu um Erro!',
								msg: response.message,
								buttons: Ext.Msg.OK,
								icon: Ext.MessageBox.WARNING
							});						
							
							txtLogin.focus();
						}
					}
					catch (e) {
						Ext.MessageBox.hide();
						wbErroUi = Ext.extend(Ext.Window, {
							title: 'Erro no sistema',
							width: 736,
							height: 372,
							modal: true,
							resizable: false,
							autoScroll: true,
							layout: 'absolute',
							initComponent: function() {
								this.items = [
									{
										xtype: 'container',
										width: 120,
										height: 90,
										x: 10,
										y: 30,
										html: '<Img src = \'images/process-stop.png\' border = 0>'
									},
									{
										xtype: 'container',
										width: 560,
										height: 270,
										x: 150,
										y: 20,
										autoScroll: true,
										html: response.responseText
									},
									{
										xtype: 'button',
										text: 'Fechar a janela',
										x: 600,
										y: 310,
										width: 110,
										handler: function(){Ext.getCmp('WinErro').close();}
									}
								];
								wbErroUi.superclass.initComponent.call(this);
							}
						});

						var JanelaErro = new wbErroUi({	id : 'WinErro', renderTo: Ext.getBody()});
						JanelaErro.show();

						//document.write(response.responseText);
						//document.close();
					}
					
				}
				,callback: function()
				{
					this.buttons[0].enable();
					this.buttons[1].enable();
				}
			});
		}
		,_onBtnLembrarClick: function()
		{
			Ext.Msg.prompt('Recuperar Senha', 'Qual seu e-mail:', function(btn, text){
				if (btn == 'ok'){
					Ext.Ajax.request({
						url		: this.urlLembrar
						,method : 'POST'
						,scope	: this
						,params	: Ext.applyIf({
							 email: text
						},this.params)
						,success: function(response)
						{
							response = Ext.decode(response.responseText);
							
							if( response.success )
							{
								Ext.Msg.show({
									title: 'Atenção!',
									msg: response.message,
									buttons: Ext.Msg.OK,
									icon: Ext.MessageBox.WARNING
								});
							}
							else
							{
								Ext.Msg.show({
									title: 'Ocorreu um Erro!',
									msg: response.message,
									buttons: Ext.Msg.OK,
									icon: Ext.MessageBox.WARNING
								});				
								
								txtLogin.focus();
							}					
						}
						,callback: function()
						{
							this.buttons[0].enable();
							this.buttons[1].enable();				
						}
					});
				}
			});
		}
});



