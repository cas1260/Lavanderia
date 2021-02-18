Ext.onReady(function(){
	
	Ext.QuickTips.init();

	MyToolbarUi = Ext.extend(Ext.Toolbar, 
		{
			width: screen.width,
			initComponent: function() 
			{
				var menusistema = new Ext.Panel(
					{tbar: [{
						xtype: 'buttongroup',
						title: 'Configurações',
						columns: 4, 
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [
						{
							text: 'Sistema',
							iconCls:'sistema',
						},{
							text: 'Central <br>do Assinante',
							iconCls:'configcentral',
							scope:this,
						},{
							text: 'Dados da central<br>	do assinante',
							iconCls:'dadoscentral',
							scope:this,
						},{
							text: 'Campos extras<br>em cliente',
							iconCls:'camposextras'
						},{
							text: 'Cobrança<br>automatica',
							iconCls:'cobrancaautomatica'
						},{
							text: 'Formulario de<br> assinatura',
							iconCls:'formulario'
						},{
							text: 'Thema',
							iconCls:'themas'
						}]			
					},
					{
						xtype: 'buttongroup',
						title: 'Geral',
						columns:3,
						defaults: {
								scale: 'large',
								iconAlign: 'top'
						},
						items: [
						{
							text: 'Backup',
							iconCls:'backup',
						},{
							text: 'Restaurar<br>Backup',
							iconCls:'restore',
							scope:this,
						},{
							text: 'Alterar<br>Senha',
							iconCls:'alteraenha',
							scope:this,
						},{
							text: 'HelpDesk',
							iconCls:'helpdesk'
						},{
							text: 'Mala direta<br>&nbsp;',
							iconCls:'mala'
						}]			
					}]			
					}
				);	
		
				var menurelatorio = new Ext.Panel({
					tbar: [{
						xtype: 'buttongroup',
						title: 'Relatórios',
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [
						{
							text: 'Contas a Pagar',
							iconCls:'',
						},{
							text: 'Contas a Receber',
							iconCls:'',
							scope:this,
						},{
							text: 'Extrato de contas',
							iconCls:'',
							scope:this,
						},{
							text: 'Calculos de<br>contas a pagar',
							iconCls:''
						},{
							text: 'Contas a <br>Receber Vencidas',
							iconCls:''
						},{
							text: 'Imprimir <br>multiplos Titulos',
							iconCls:'impressaomultipla'
						},{
							text: 'Segurança',
							iconCls:''
						},{
							text: 'Personalizado',
							iconCls:''
						}]			
					}]			
				});	
		
				var menufinanceiro = new Ext.Panel({
					tbar: [{
						xtype: 'buttongroup',
						title: 'Financeiro',
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [
						{
							text: 'Baixa de titulo',
							iconCls:'baixadetitulo',
						},{
							text: 'Baixa de <br>titulo via CNAB',
							iconCls:'baixadetitulocnab',
							scope:this,
						},{
							text: 'Contas a<br>Pagar',
							iconCls:'contasapagar',
							scope:this,
						},{
							text: ' Contas a<br>Receber',
							iconCls:'contasareceber'
						},{
							text: 'Lançamento<br>automáticos',
							iconCls:'lancamentoautomaticao'
						},{
							text: 'Lançamento de<br> titulos Avuso',
							iconCls:'boletoavulso'
						},{
							text: 'Lançamento de <br>Parcelas de contas',
							iconCls:'parcelamultiplas'
						},{
							text: 'Pesquisa &<br> Baixas de Contas',
							iconCls:'pesquisa'
						},{
							text: 'Resumo<br>Financeiro',
							iconCls:'resumo'
						}]			
					}]			
				});	
				
				var menucobranca = new Ext.Panel({
					tbar: [{
						xtype: 'buttongroup',
						title: 'Cobran;a',
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [{
							text: 'Enviar cobrança<br>Unitario',
							iconCls:'cobrancaunitaria',
						},{
							text: 'Enviar cobrança<br>Geral', 
							iconCls:'cobrancageral',
							scope:this,
						}]			
					}]			
				});		

				
				var menucadastro = new Ext.Panel({
					tbar: [{
						columns:6, 
						xtype: 'buttongroup',
						title: 'Cadastro',
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [{
							text: 'Bancos'
							, iconCls:'banco'
							, handler: function(){
								chamatela('clientes.json');
							
							}
							
						},{
							text: 'Serviço',
							iconCls:'servico',
						},{
							text: 'Centro<br>de Custo',
							iconCls:'centrocusto',
							scope:this,
							//handler: chamamenu
						},{
							text: 'Classificação',
							iconCls:'classificacao'
						},{
							text: 'Clientes',
							iconCls:'cliente'
						},{
							text: 'Fornecedor',
							iconCls:'fornecedor'
						},{
							text: 'Texto<br>Pre-definidos',
							iconCls:'textos	'
						},{
							text: 'Departamento<br>de Helpdesk',
							iconCls:'dep_helpdesk'
						},{
							text: 'Forma de<br>Pagamento',
							iconCls:'forma'
						},{
							text: 'Servidores',
							iconCls:'servidores'
						},{
							text: 'Usuário',
							iconCls:'usuario'
						}]			
					}]			
				});	
	
				
				this.items = [
					{
						text: 'Cadastro',
						menu: {
							xtype: 'menu',
							items: [menucadastro]
						}
					},
					{
						text: 'Cobrança',
						menu: {
							xtype: 'menu',
							items: [menucobranca]
						}
					},
					{
						text: 'Financeiro',
						menu: {
							xtype: 'menu',
							items: [menufinanceiro]
						}
					},
					{
						text: 'Relatório',
						menu: {
							xtype: 'menu',
							items: [menurelatorio]
						}
					},
					{
						text: 'Sistema',
						menu: {
							xtype: 'menu',
							items: [menusistema]
						}
					}];
					MyToolbarUi.superclass.initComponent.call(this);
			}
		}
	);

	MyToolbar = Ext.extend(MyToolbarUi, 
	{
		initComponent: function() {
			MyToolbar.superclass.initComponent.call(this);
		}
	}
	);
	
   var myToolbar = new MyToolbar({
        renderTo: 'ux-menu'
    });
    myToolbar.show();	

	var remoteUrl = 'js/cadastrobancos.js';
	var tabId = 'tabpanel';
	var tabIndex = 0;
	var liteTab = false;
	var deferedTab = false;
	var mainPanel = false;
	
	var getRemoteComponentPlugin = function(){
		return new Ext.ux.Plugin.RemoteComponent({
			url : remoteUrl
		}); 
	};

	var getLiteRemoteComponentPlugin = function(){
		return new Ext.ux.Plugin.LiteRemoteComponent({
			url : remoteUrl
		}); 
	};

	var getDeferedRemoteComponentPlugin = function(){
		return new Ext.ux.Plugin.RemoteComponent({
			url : remoteUrl,
			loadOn: 'show'
		}); 
	};

	var getStoppedRemoteComponentPlugin = function(){
		return new Ext.ux.Plugin.RemoteComponent({
			url : remoteUrl,
			breakOn: 'show'
		}); 
	};

	var getMainPanel = function(){
		if (!mainPanel){
			mainPanel = new Ext.TabPanel({
			    activeTab: 0,
				resizeTabs:true, // turn on tab resizing
		        minTabWidth: 160,
		        tabWidth:160,
		        enableTabScroll:true,
		        width:screen.width,
		        height:screen.height-190,
				title: 'WebFinan 5.0 - Controle Gerencial',
				autoShow: true,
		        defaults: {autoScroll:true},
				items: [ 
				{
					title:'Principal',
					plugins: [getRemoteComponentPlugin()],
					closable: false,
				}
				]
			});		
		}		
		return mainPanel;
	};
	
	var addRemoteTab = function(){
		tabIndex += 1;
		return getMainPanel().add({
	        title: 'RemoteComponent ' + tabIndex,
			id: 'tab' + tabIndex,
			closable: true,
			plugins: new Ext.ux.Plugin.RemoteComponent({
				url : remoteUrl
			}),
			autoShow: true
			}
		).show();
	};

	var addLiteTab = function(){
		if(!liteTab){			
			liteTab =  getMainPanel().add({
		        title: 'LiteRemoteComponent',
				closable: true,
				plugins: [getLiteRemoteComponentPlugin()],
				autoShow: true
				}
			).show();
			liteTab.on('destroy', function(){
				liteTab = false;
			})						
		}		
		return liteTab;
	};

	var addDeferedTab = function(){
		if(!deferedTab)
		{
			deferedTab =  getMainPanel().add({
		        title: 'defered RemoteComponent',
				closable: true,
				plugins: [getDeferedRemoteComponentPlugin()],
				autoShow: true
				}
			);
			deferedTab.on('destroy', function(){
				deferedTab = false;
			});						
		}		
		return deferedTab;
	};

    var p = new Ext.Panel({
        collapsible:false,
        renderTo: 'aba',
        width:screen.width,
		height: screen.height-190,
        items: getMainPanel()
    });
	
	p.getBottomToolbar().add([
	]);
	
});