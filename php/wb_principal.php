<?php
session_start();

if ($_SESSION['tipo'] == "aba"){
	$xret = 'true';
}else{
	$xret = 'false';
}
?>

Ext.onReady(function(){
	var uBarra;

	Ext.QuickTips.init();
	
	var modulotab = <?php echo $xret; ?>;	
	var janelaativa = 0;
	
	
	OBJ = function(objname){
		return Ext.getCmp(objname);
	}
	RunJavaScript = function(pComando){
        try {
			eval(pComando);
        } catch(e) {
			ExibiErro('Erro: ' + e.message + '<br>name : '+e.name+'<br>Line: '+e.lineNumber+'<br>Arquivo :'+e.fileName+'<br>comando<Hr>'+pComando+'<hr>');
        }

	}
	hObj = function(sFormName, acao, total){
		
		sform = OBJ(sFormName).getForm();
		//total = sform.getForm().items.length;
		var i=0;
		for (i=0;i<=total;i++) // >
		{
			try {
				obj = sform.items.item(i);
				obj.setDisabled(acao);
			}
			catch(e) {
										
			}
		}
	}
	retornaIdTree = function(objTree){
		var objTreeInterno;
		
		if (!objTree.id == true){
			objTreeInterno = OBJ(objTree);
		} else {
			objTreeInterno = objTree;
		}
		sId = '';
		objTreeInterno.root.cascade(function(a){
										try {
											if (a.getUI().checkbox.checked == true){
												sId = sId + a.getUI().checkbox.id
											}

										} catch(e) {
										
										}
									   });
		return sId;
	}
	
	pesquisaTreeNode =  function(objTree, texto){
		
		var objTreeInterno;
		var erro;

		if (!objTree.id == true){
			objTreeInterno = OBJ(objTree);
		} else {
			objTreeInterno = objTree;
		}
		
		var text = '';
		
		if (!texto.id == true){
			text = OBJ(texto).getValue();
		}else{
			text = texto;
		}
		
		objTreeInterno.root.cascade(function(a){
									try {
										a.getUI().show();
									} catch(e) {
									
									}
								   });

		if (text != ''){
			objTreeInterno.root.cascade(function(n){
				try {
					var result = n.getUI().node.text.toLowerCase().indexOf(text.toLowerCase());

					if (result == -1){
						n.ui.hide();
					}
				} catch(e) {
										
				}
			});
		}
	} 
	
	
	ExibiErro = function(pdescricao){
	
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
								html: pdescricao
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

	}
	
	addtask = function(objecto){
	
		win = Ext.getCmp(objecto);
	
		idobj = Ext.getCmp('tarefa').insert(janelaativa, 
			{	xtype: 'button',
				text: win.title,
				iconCls: win.iconCls,
				//width:100,
				//pressed: true, 
				handler: function(){
					Ext.getCmp(objecto).show();
				}
			});
		win.tag = idobj.id;
		Ext.getCmp('tarefa').doLayout();
		janelaativa = janelaativa +1;
		win.on('close', function(obj) {
			janelaativa = janelaativa -1;
			Ext.getCmp('tarefa').remove(Ext.getCmp(obj.tag));
		});
		win.on('minimize', function(obj) {
			obj.hide();
		});
		trocaTAB()
	}

	function trocaTAB(){
	   var nodes = document.getElementsByTagName('*');
	   //console.dir(nodes);
	   var elements = new Array();
	   for(var j=0;j<nodes.length;j++){ //>		
		   //if(nodes[j].tagName.toLowerCase()=="input" || nodes[j].tagName.toLowerCase()=="textarea" || nodes[j].tagName.toLowerCase()=="select" ){
		   //alert(nodes[j].tagName);
		   if(nodes[j].tagName.toLowerCase()=="input" || nodes[j].tagName.toLowerCase()=="select"){			
				elements.push(nodes[j]);
		   } 
	   }
	   for(var i=0;i<elements.length;i++){ //>
		   if(elements[i].type.toLowerCase()=="submit" || elements[i].type.toLowerCase()=="reset") continue;
		   elements[i].onkeypress=function(e){
					var k = document.all?event.keyCode:e.keyCode;					  																
		   			if(k==13){									   				
		   			   var nodes = document.getElementsByTagName('*');	   
		   			   var elements = new Array();
		   			   for(var j=0;j<nodes.length;j++){		//>
		   				   if( nodes[j].tagName.toLowerCase()=="input"  || nodes[j].tagName.toLowerCase()=="textarea" || nodes[j].tagName.toLowerCase()=="select" ){			
		   						elements.push(nodes[j]);
		   				   } 
		   			   }
		   				for(var i=0;i<elements.length;i++){		//>		   				
							if(this==elements[i] && i<elements.length-1){ //>
								elements[i+1].focus();
								return false;
							}
						}
					  	elements[0].focus();
					  	return false;
		   			}
		   			return true;
				};
	   }
    };

	function fn1(nStr)
	{
		nStr = nStr.toFixed(2) + '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
	
	function fn(valor){
		return valor;
		var nvalor = eval(valor);
		
		svalor = nvalor.toFixed(2) + '';
		svalor = svalor.replace(',', '');
		svalor = svalor.replace('.', ',');

		return svalor;
	
	}
	
	function montasqlURL(dados){
		//console.dir(dados)
		
		totalregistro = dados.data.items.length;
		totaldecampos = dados.fields.length;
		sql = '';
		
		for (i=0; i < totalregistro; i++) { // >
			for (x=0; x < totaldecampos; x++) { // >
				nomecampo = dados.fields.items[x].name;
				valor     = '';
				comando   = "valor = dados.data.items[i].data."+nomecampo; 
				eval(comando);
				sql = sql + nomecampo + '=' + valor + '$!';
			}
			sql = sql + '|';
		}
		return sql;	
	}
	
	WAIT = function(texto){
			Ext.MessageBox.wait(texto, 'WebFinan');
	}
	
	function SIMNAO(texto, funcao){
		Ext.Msg.show({
			title:'WebFinan',
			msg: texto,
			buttons: Ext.Msg.YESNO,
			fn: funcao,
			animEl: 'elId',
			icon: Ext.MessageBox.QUESTION
		});
	}
	
	MSG = function(texto, funcao){
				Ext.Msg.show({
					title:'WebFinan',
					msg: texto,
					buttons: Ext.Msg.OK,
					fn: funcao,
					animEl: 'elId',
					icon: Ext.MessageBox.INFO
				});
	}
	
	REDE = function(texto, funcao){
				Ext.Msg.show({
					title:'WebFinan',
					msg: texto,
					buttons: Ext.Msg.OK,
					fn: funcao,
					animEl: 'elId',
					icon: 'computador'
				});
	}

	pesquisabarra = function(txt,e){
		if(e.getKey() == e.ENTER){
			e.stopEvent();	
			valor = Ext.getCmp('txtpesqbarra').getValue();
			Ext.getCmp('txtpesqbarra').setValue('');
			if (valor != ''){
				run('php/wb_listaregistros.php', 'Cadastro de clientes&txtpesqbarra='+valor, 900, 387, 'clientes');				
			}
		}
	}
	
	salvardados = function(url, paramentros){
	
		Ext.MessageBox.wait('Aguarde, Salvando informações.', 'WebFinan');

		Ext.Ajax.request({
						url : url,
						method: 'POST',
						params :paramentros,
						success: function (result, request) {
							xcomando = result.responseText;
							Ext.MessageBox.hide();
							RunJavaScript(xcomando);
						},
						failure: function ( result, request ) {
									alert('Falha ');
						}
					    });
		
	};
	
	OPENURL = function(url){

		Ext.menu.MenuMgr.hideAll();
		Ext.MessageBox.wait('Aguarde, carregando as informações desejadas.', 'WebFinan');

		urlx = url
		Ext.Ajax.request({
						url : url, 
						method: 'POST',
						params :{},
						success: function ( result, request ) {
							xcomando = result.responseText;
							Ext.MessageBox.hide();
							RunJavaScript(xcomando);
							
						},
						failure: function ( result, request ) {
							Ext.MessageBox.hide();
							texto = '';
							texto = texto + 'Falha ao carregar o arquivo desejado.<br>';
							texto = texto + 'Possíveis motivos da falha.<br>';
							texto = texto + '&nbsp;&nbsp;&nbsp;1º Não foi possível estabelecer uma conexão com o servidor<br>';
							texto = texto + '&nbsp;&nbsp;&nbsp;2º Sua conexão esta muito lenta, que atingiu o limite Maximo de execunsão de um script em seu servidor<br> <br>';
							texto = texto + 'Caso o problema volte acontecer, entre em contato com o suporte. <br>';
							texto = texto + "<A Href = javascript:alert('"+result+"')>Click aqui para maiores informações do erro.</a>";
							REDE(texto);
						}
					    });
		
	};
	
	
	run = function(url, titulo, w, h, tipo, icon){

	/*		
		uBarra = Ext.get('botaobarra');
	
		var JSON = Ext.decode('{"xtype": "button","text": "'+titulo+'"}');
		var component = Ext.ComponentMgr.create(JSON, defaultType);
		uBarra.container.add(component);
		component.show();
		uBarra.container.doLayout();
		
		alert(botaobarra);
		
		uBarra.add({
					xtype: 'button',
					text: 'MyButton'
					});
		
	*/
	
		Ext.menu.MenuMgr.hideAll();
		Ext.MessageBox.wait('Aguarde, carregando as informações desejadas.', 'WebFinan');

		// url : url+'?titulo='+titulo+'&w='+w+'&h='+h+'&tipo='+tipo+'&icon='+icon
		urlx = url
		Ext.Ajax.request({
						url : url+'?titulo='+titulo+'&w='+w+'&h='+h+'&tipo='+tipo+'&icon='+icon,
						method: 'POST',
						params :{},
						success: function ( result, request ) {
							xcomando = result.responseText;
							Ext.MessageBox.hide();
							RunJavaScript(xcomando);
							
						},
						failure: function ( result, request ) {
							Ext.MessageBox.hide();
							texto = '';
							texto = texto + 'Falha ao carregar o arquivo desejado.<br>';
							texto = texto + 'Possíveis motivos da falha.<br>';
							texto = texto + '&nbsp;&nbsp;&nbsp;1º Não foi possível estabelecer uma conexão com o servidor<br>';
							texto = texto + '&nbsp;&nbsp;&nbsp;2º Sua conexão esta muito lenta, que atingiu o limite Maximo de execunsão de um script em seu servidor<br> <br>';
							texto = texto + 'Caso o problema volte acontecer, entre em contato com o suporte. <br>';
							texto = texto + "<A Href = javascript:alert('"+result+"')>Click aqui para maiores informações do erro.</a>";
							REDE(texto);
						}
					    });
		
	};
	
	OpenUrl = function(url){
		return run(url +'&', '', 0, 0 , '');
	};
	
	
	
	MyToolbarUi = Ext.extend(Ext.Toolbar, 
		{
			initComponent: function() 
			{
				var menusistema = new Ext.Panel(
					{tbar: [{
						xtype: 'buttongroup',
						title: 'Sistema',
						columns:6,
						defaults: {
								scale: 'large',
								iconAlign: 'top'
						},
						items: [
						{
							text: 'Backup',
							iconCls:'backup',
							handler: function(){
								run('php/wb_backup.php', 'Backup', 740, 387, 'Config', this.iconCls);
							}

						},{
							text: 'Alterar<br>Senha',
							iconCls:'alteraenha',
							scope:this,
							handler: function(){
								run('php/wb_altera_senha.php', 'Altera Senha', 740, 387, 'Config', this.iconCls);
							}
						},{
							text: 'HelpDesk',
							iconCls:'helpdesk',
							handler: function(){
								run('php/wb_central_helpdesk.php', 'helpdesk', 740, 387, 'Config', this.iconCls);
							}
						},{
							text: 'Mala direta<br>&nbsp;',
							iconCls:'mala',
							handler: function(){
								run('php/wb_mala.php', 'mala', 740, 387, 'Config', this.iconCls);
							}
						}]			
					}]			
					}
				);	
				
				var menuconfig = new Ext.Panel(
					{tbar: [{
						xtype: 'buttongroup',
						title: 'Configurações',
						columns: 5, 
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [
						{
							text: 'Sistema',
							iconCls:'sistema',
							handler: function(){
								run('php/wb_config.php', 'Config', 740, 387, 'Config', this.iconCls);
							}
						},
						{
							text: 'Campos extras<br>em cliente',
							iconCls:'camposextras',
							handler: function(){
								run('php/wb_config_campos_extras.php', 'Config', 740, 387, 'Config', this.iconCls);
							}
							
						},
						
						{
							text: 'Central do cliente',
							iconCls:'configcentral',
							handler: function(){
								run('php/wb_central_cliente.php', 'Config', 740, 387, 'Config', this.iconCls);
							}
						},						
						{
							text: 'Formulario de<br> assinatura',
							iconCls:'formulario',
							handler: function(){
								run('php/wb_config_assinar.php', 'thema', 740, 387, 'config', this.iconCls);
							}
						},{
							text: 'Thema',
							iconCls:'themas',
							handler: function(){
								run('php/wb_thema.php', 'thema', 740, 387, 'thema', this.iconCls);
							}
						}]			
					}]			
					}
				);	
		
				var menurelatorio = new Ext.Panel({
					tbar: [{
						xtype: 'buttongroup',
						title: 'Relatórios',
						columns:5, 
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [
								{
									text: 'Apropriações',
									iconCls:'impressaomultipla',
									handler: function(){
										run('php/wb_apropriacao.php', 'Relatorio de apropriação', 740, 387, 'Relatorio de apropriação', this.iconCls);
									}
								},
								{
									width:75, 
									height: 75,
									text: 'Serviços<br>Prestados',
									iconCls:'resumo',
									handler: function(){
										run('php/wb_report_cliente.php', 'Serviços Prestados', 740, 387, 'ServiçosPrestados', this.iconCls);
									}							
								},
								{
									width:75, 
									height: 75,
									text: 'Movimento de <br>clientes Completo',
									iconCls:'extrato',
									scope:this,
									handler: function(){
										run('php/wb_relatorio_mov.php', 'Movimento de clientes', 740, 387, 'RelMovCli', this.iconCls);
									}							
								},
								{
									width:75, 
									height: 75,
									text: 'Movimento de <br>clientes Simples',
									iconCls:'extrato',
									scope:this,
									handler: function(){
										run('php/wb_relatorio_mov_simples.php', 'Movimento de clientes', 740, 387, 'RelMovCli', this.iconCls);
									}							
								},
								{
									width: 75, 
									height: 75,
									text: 'Rota de <br>Cliente',
									iconCls:'camposextras',
									scope:this,
									handler: function(){
										run('php/wb_report_rotas.php', 'Relatorio de rotas', 740, 387, 'RelMovCli', this.iconCls);
									}							
								}
						]			
					}]			
				});	
		
				var menufinanceiro = new Ext.Panel({
					tbar: [{
						xtype: 'buttongroup',
						title: 'Lançamento',
						columns:4, 
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [
						{
							text: 'Romaneio',
							iconCls:'cobrancaunitaria', 
							width:75,
							height: 75,
							handler: function(){
								//run('php/wb_romanei.php', 'Gerenciador', 740, 387, 'Gerenciador', this.iconCls);
								run('./php/wb_listaregistros.php', 'Romaneio', 740, 387, 'romanei', this.iconCls);
							}
						},
						{
							text: 'Nota fiscal', 
							iconCls:'baixadetitulo',
							scope:this,
							width:75,
							height: 75,
							handler: function(){
								run('./php/wb_listaregistros.php', 'Nota fiscal', 740, 387, 'nf', this.iconCls);
							}
						},
						{
							text: 'Reajuste<br>de preço',
							iconCls:'contasapagar',
							width:75,
							height: 75,
							handler: function(){
								run('php/wb_reajuste.php', this.text, 740, 387, 'DownFatura', this.iconCls);
							}
						}/*
						,{
							text: 'Contas a<br>Pagar',
							iconCls:'contasapagar',
							scope:this,
							handler: function(){
								run('php/wb_contas_a_pagar.php', this.text, 740, 387, 'ContasPagar', this.iconCls);
							}
						},{
							text: 'Contas a<br>Receber',
							iconCls:'contasareceber',
							handler: function(){
								run('php/wb_contas_a_receber.php', this.text, 740, 387, 'ContasReceber', this.iconCls);
							}
						},{
							text: 'Lançamento<br>automáticos',
							iconCls:'lancamentoautomaticao',
							handler: function(){
								run('php/wb_lancamento_automatico.php', this.text, 740, 387, 'LancAuto', this.iconCls);
							}
							
						}
						,
						{
							text: 'Contas<br>parceladas',
							iconCls:'parcelamultiplas',
							handler: function(){
								run('php/wb_listagem_contas_parcelas.php', this.text, 740, 387, 'parceladas', this.iconCls);
							}
						}*/]			
					}]			
				});	
				
				var menucadastro = new Ext.Panel({
					tbar: [{
						columns:10, 
						xtype: 'buttongroup',
						title: 'Cadastro',
						defaults: {
							scale: 'large',
							iconAlign: 'top'
						},
						items: [
						{
							width:60, 
							height: 75,
							text: 'Centro<br>de Custo',
							iconCls:'centrocusto',
							width:75,
							height: 75,							
							//scope:this,
							handler: function(){
								run('php/wb_listaregistros.php', 'Cadastro de centro de custo', 740, 387, 'centrocusto', this.iconCls);
							}
						},
						{
							width:60, 
							height: 75,
							text: 'Clientes',
							width:75,
							height: 75,
							iconCls:'cliente', 
							handler: function(){
								run('php/wb_listaregistros.php', 'Cadastro de clientes', 900, 387, 'clientes', this.iconCls);
							}
						},
						{
							width:75, 
							height: 75,
							text: 'Forma de<br>Pagamento',
							iconCls:'forma', 
							handler: function(){
								run('php/wb_listaregistros.php', 'Cadastro de Forma de Pagamento', 740, 387, 'formapagamento', this.iconCls);
							}
						},
						
						{
							width:75,
							height: 75,
							text: 'Fornecedor',
							iconCls:'fornecedor', 
							handler: function(){
								run('php/wb_listaregistros.php', 'Cadastro de fornecedores', 740, 387, 'fornecedor', this.iconCls);
							}
						},
						{
							width:75,
							height: 75,
							text: 'Mercadoria',
							iconCls:'mercadoria', 
							handler: function(){
								run('php/wb_listaregistros.php', 'Cadastro de mercadoria', 900, 387, 'mercadoria', this.iconCls);
							}
						},	
						{
							width:75,
							height: 75,
							text: 'Motorista',
							iconCls:'engineering', 
							handler: function(){
								run('php/wb_listaregistros.php', 'Cadastro de motorista', 900, 387, 'motorista', this.iconCls);
							}
						},
						{
							width:75,
							height: 75,
							text: '&nbsp;&nbsp;&nbsp;&nbsp;Rota&nbsp;&nbsp;&nbsp;&nbsp;',
							iconCls:'camposextras',
							handler: function(){
									run('php/wb_listaregistros.php', 'Cadastros de Rota', 740, 387, 'rota', this.iconCls);
													
							}
						},
						{
							width:75,
							height: 75,
							text: 'Serviço',
							iconCls:'servico',
							handler: function(){
									run('php/wb_listaregistros.php', 'Cadastros de servicos', 740, 387, 'servicos', this.iconCls);
													
							}
						},

						{
							width:75,
							height: 75,
							text: 'Vendedor',
							iconCls:'vendedor', 
							handler: function(){
								run('php/wb_listaregistros.php', 'Cadastro de vendedores', 900, 387, 'vendedor', this.iconCls);
							}
						},
						{
							width:75,
							height: 75,
							text: 'Usuários',
							iconCls:'usuario', 
							handler: function(){
								run('php/wb_listaregistros.php', 'Cadastro de usuários', 900, 387, 'usuario', this.iconCls);
							}
						}
						]
					}]			
				});	
	
				this.items = [
						{text: 'Cadastro', 	menu: {xtype: 'menu', items: [menucadastro]}},
						{text: 'Movimentação',menu: {xtype: 'menu',	items: [menufinanceiro]}},
						{text: 'Relatório',menu: {xtype: 'menu',	items: [menurelatorio]}}
					];
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
	
	var barramenu = new MyToolbar({
        renderTo: 'ux-menu'
    });
    
	xBarra = Ext.extend(Ext.Toolbar, {
		enableOverflow: true,				
		autoDestroy: true,
		initComponent: function() {
			this.items = [
				{
					xtype: 'tbfill'
				},
				{
					xtype: 'tbseparator'
				},
				{
					xtype: 'tbtext',
					text: 'Powered by Neo Software - Todos direitos reservado'
				},
				{
					xtype: 'tbseparator'
				},
				{
					xtype: 'tbseparator'
				},
				{
					xtype: 'tbtext',
					text: 'Usuário: <?php echo $_SESSION['nome'];?>'
				},
				{
					xtype: 'tbseparator'
				},
				{
					xtype: 'tbtext',
					text: '00:00:00',
					id: 'lblhora'
				}
			];
			xBarra.superclass.initComponent.call(this);
		}
	});

	
	Mybarra = Ext.extend(xBarra, 
	{
		initComponent: function() {
			Mybarra.superclass.initComponent.call(this);
		}
	}
	);
	var xbar = new Mybarra ({
		id: 'tarefa', 
        renderTo: 'xbarradivX'
    });
	mostrahora = function(){
		thistime= new Date();
		hours=thistime.getHours();
		minutes=thistime.getMinutes();
		seconds=thistime.getSeconds();
		if (eval(hours) < 10) { //>
			hours="0"+hours;
		}
		if (eval(minutes) < 10) { //>
			minutes="0"+minutes;
		}
		if (seconds < 10) { //>
			seconds="0"+seconds;
		}
		thistime = hours+":"+minutes+":"+seconds;	
		Ext.getCmp('lblhora').setText(thistime);
		var timer=setTimeout("mostrahora()",1000);
	}
	var timer=setTimeout("mostrahora()",1000);
});

Ext.override(Ext.data.Store,{
	addField: function(field){
		field = new Ext.data.Field(field);
		this.recordType.prototype.fields.replace(field);
		if(typeof field.defaultValue != 'undefined'){
			this.each(function(r){
				if(typeof r.data[field.name] == 'undefined'){
					r.data[field.name] = field.defaultValue;
				}
			});
		}
	},
	removeField: function(name){
		this.recordType.prototype.fields.removeKey(name);
		this.each(function(r){
			delete r.data[name];
			if(r.modified){
				delete r.modified[name];
			}
		});
	}
});
Ext.override(Ext.grid.ColumnModel,{
	addColumn: function(column, colIndex){
		if(typeof column == 'string'){
			column = {header: column, dataIndex: column};
		}
		var config = this.config;
		this.config = [];
		if(typeof colIndex == 'number'){
			config.splice(colIndex, 0, column);
		}else{
			colIndex = config.push(column);
		}
		this.setConfig(config);
		return colIndex;
	},
	removeColumn: function(colIndex){
		var config = this.config;
		this.config = [config[colIndex]];
		config.splice(colIndex, 1);
		this.setConfig(config);
	}
});
Ext.override(Ext.grid.GridPanel,{
	addColumn: function(field, column, colIndex){
		if(!column){
			if(field.dataIndex){
				column = field;
				field = field.dataIndex;
			} else{
				column = field.name || field;
			}
		}
		this.store.addField(field);
		return this.colModel.addColumn(column, colIndex);
	},
	removeColumn: function(name, colIndex){
		this.store.removeField(name);
		if(typeof colIndex != 'number'){
			colIndex = this.colModel.findColumnIndex(name);
		}
		if(colIndex >= 0){
			this.colModel.removeColumn(colIndex);
		}
	}
});


Ext.ns('Ext.ux.form');
Ext.ux.form.XCheckbox = Ext.extend(Ext.form.Checkbox, {
     offCls:'xcheckbox-off'
    ,onCls:'xcheckbox-on'
    ,disabledClass:'xcheckbox-disabled'
    ,submitOffValue:'0'
    ,submitOnValue:'1'
    ,checked:false

    ,onRender:function(ct) {
        // call parent
        Ext.ux.form.XCheckbox.superclass.onRender.apply(this, arguments);

        // save tabIndex remove & re-create this.el
        var tabIndex = this.el.dom.tabIndex;
        var id = this.el.dom.id;
        this.el.remove();
        this.el = ct.createChild({tag:'input', type:'hidden', name:this.name, id:id});

        // update value of hidden field
        this.updateHidden();

        // adjust wrap class and create link with bg image to click on
        this.wrap.replaceClass('x-form-check-wrap', 'xcheckbox-wrap');
        this.cbEl = this.wrap.createChild({tag:'a', href:'#', cls:this.checked ? this.onCls : this.offCls});

        // reposition boxLabel if any
        var boxLabel = this.wrap.down('label');
        if(boxLabel) {
            this.wrap.appendChild(boxLabel);
        }

        // support tooltip
        if(this.tooltip) {
            this.cbEl.set({qtip:this.tooltip});
        }

        // install event handlers
        this.wrap.on({click:{scope:this, fn:this.onClick, delegate:'a'}});
        this.wrap.on({keyup:{scope:this, fn:this.onClick, delegate:'a'}});

        // restore tabIndex
        this.cbEl.dom.tabIndex = tabIndex;
    } // eo function onRender

    ,onClick:function(e) {
        if(this.disabled || this.readOnly) {
            return;
        }
        if(!e.isNavKeyPress()) {
            this.setValue(!this.checked);
        }
    } // eo function onClick

    ,onDisable:function() {
        this.cbEl.addClass(this.disabledClass);
        this.el.dom.disabled = true;
    } // eo function onDisable

    ,onEnable:function() {
        this.cbEl.removeClass(this.disabledClass);
        this.el.dom.disabled = false;
    } // eo function onEnable

    ,setValue:function(val) {
        if('string' == typeof val) {
            this.checked = val === this.submitOnValue;
        }
        else {
            this.checked = !(!val);
        }

        if(this.rendered && this.cbEl) {
            this.updateHidden();
            this.cbEl.removeClass([this.offCls, this.onCls]);
            this.cbEl.addClass(this.checked ? this.onCls : this.offCls);
        }
        this.fireEvent('check', this, this.checked);

    } // eo function setValue

    ,updateHidden:function() {
        this.el.dom.value = this.checked ? this.submitOnValue : this.submitOffValue;
    } // eo function updateHidden

    ,getValue:function() {
        return this.checked;
    } // eo function getValue

}); // eo extend

// register xtype
Ext.reg('xcheckbox', Ext.ux.form.XCheckbox);

// eo file  


Ext.ns('Ext.ux.form');
Ext.ux.form.RCheckbox = Ext.extend(Ext.form.Checkbox, {
	uncheckedValue:false,
	checkedValue  :true,

	onRender:function() {
		Ext.ux.form.RCheckbox.superclass.onRender.apply(this, arguments);

		//Cria um campo hidden para conter o valor a ser enviado
		this.hiddenField = this.wrap.insertFirst({tag:'input', type:'hidden'});
		//Atribui o nome do checkbox ao campo hidden
		this.hiddenField.dom.name = this.el.dom.name;
		//Retira o campo nome do checkbox para enviar apenas o campo hidden
		this.el.dom.name = '';

		this.updateHidden();
	},
	setValue:function(v) {
		Ext.ux.form.RCheckbox.superclass.setValue.apply(this, arguments);
		this.updateHidden();
	},
	updateHidden:function(v) {
		if(this.hiddenField) {
			this.hiddenField.dom.value = this.checked ? this.checkedValue : this.uncheckedValue;
		}
	}
});
Ext.reg('rcheckbox', Ext.ux.form.RCheckbox);

Ext.ux.GridPrinter = {
  print: function(grid, pTitulo) {
    //We generate an XTemplate here by using 2 intermediary XTemplates - one to create the header,
    //the other to create the body (see the escaped {} below)
    var columns = grid.getColumnModel().config;
    
    //build a useable array of store data for the XTemplate
    var data = [];
    grid.store.data.each(function(item) {
      var convertedData = [];

      //apply renderers from column model
      for (var key in item.data) {
        var value = item.data[key];
        
        Ext.each(columns, function(column) {
          if (column.dataIndex == key) {
            convertedData[key] = column.renderer ? column.renderer(value) : value;
          }
        }, this);
      }
      
      data.push(convertedData);
    });
    
    //use the headerTpl and bodyTpl XTemplates to create the main XTemplate below
    var headings = Ext.ux.GridPrinter.headerTpl.apply(columns);
    var body = Ext.ux.GridPrinter.bodyTpl.apply(columns);
    
    var html = new Ext.XTemplate(
      '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
      '<html>',
        '<head>',
          '<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />',
          '<link href="' + Ext.ux.GridPrinter.stylesheetPath + '" rel="stylesheet" type="text/css" media="screen,print" />',
          '<title>' + grid.title + '</title>',
        '</head>',
        '<body><center><font size = 14>'+pTitulo+'</font></center>',
          '<table>',
            headings,
            '<tpl for=".">',
              body,
            '</tpl>',
          '</table>',
        '</body>',
      '</html>'
    ).apply(data);
    
    //open up a new printing window, write to it, print it and close
    var win = window.open('', 'printgrid');
    
    win.document.write(html);
    win.document.close();
    
    win.print();
    win.close();
  },
  
  /**
* @property stylesheetPath
* @type String
* The path at which the print stylesheet can be found (defaults to '/stylesheets/print.css')
*/
  stylesheetPath: '/stylesheets/print.css',
  
  /**
* @property headerTpl
* @type Ext.XTemplate
* The XTemplate used to create the headings row. By default this just uses <th> elements, override to provide your own
*/
  headerTpl: new Ext.XTemplate(
    '<tr>',
      '<tpl for=".">',
        '<th>{header}</th>',
      '</tpl>',
    '</tr>'
  ),
   
   /**
* @property bodyTpl
* @type Ext.XTemplate
* The XTemplate used to create each row. This is used inside the 'print' function to build another XTemplate, to which the data
* are then applied (see the escaped dataIndex attribute here - this ends up as "{dataIndex}")
*/
  bodyTpl: new Ext.XTemplate(
    '<tr>',
      '<tpl for=".">',
        '<td>\{{dataIndex}\}</td>',
      '</tpl>',
    '</tr>'
  )
};

Ext.ux.GridPrinter.stylesheetPath = '/css/gridPrint.css';

// $Id: InputTextMask.js 293638 2008-02-04 14:33:36Z UE014015 $

Ext.namespace('Ext.ux.netbox');

/**
 * InputTextMask script used for mask/regexp operations.
 * Mask Individual Character Usage:
 * 9 - designates only numeric values
 * L - designates only uppercase letter values
 * l - designates only lowercase letter values
 * A - designates only alphanumeric values
 * X - denotes that a custom client script regular expression is specified</li>
 * All other characters are assumed to be "special" characters used to mask the input component.
 * Example 1:
 * (999)999-9999 only numeric values can be entered where the the character
 * position value is 9. Parenthesis and dash are non-editable/mask characters.
 * Example 2:
 * 99L-ll-X[^A-C]X only numeric values for the first two characters,
 * uppercase values for the third character, lowercase letters for the
 * fifth/sixth characters, and the last character X[^A-C]X together counts
 * as the eighth character regular expression that would allow all characters
 * but "A", "B", and "C". Dashes outside the regular expression are non-editable/mask characters.
 * @constructor
 * @param (String) mask The InputTextMask
 * @param (boolean) clearWhenInvalid True to clear the mask when the field blurs and the text is invalid. Optional, default is true.
 */
Ext.ux.netbox.InputTextMask = function(mask,clearWhenInvalid) {

    if(clearWhenInvalid === undefined)
		this.clearWhenInvalid = true;
	else
		this.clearWhenInvalid = clearWhenInvalid;
    this.rawMask = mask;
    this.viewMask = '';
    this.maskArray = new Array();
    var mai = 0;
    var regexp = '';
    for(var i=0; i<mask.length; i++){
        if(regexp){
            if(regexp == 'X'){
                regexp = '';
            }
            if(mask.charAt(i) == 'X'){
                this.maskArray[mai] = regexp;
                mai++;
                regexp = '';
            } else {
                regexp += mask.charAt(i);
            }
        } else if(mask.charAt(i) == 'X'){
            regexp += 'X';
            this.viewMask += '_';
        } else if(mask.charAt(i) == '9' || mask.charAt(i) == 'L' || mask.charAt(i) == 'l' || mask.charAt(i) == 'A') {
            this.viewMask += '_';
            this.maskArray[mai] = mask.charAt(i);
            mai++;
        } else {
            this.viewMask += mask.charAt(i);
            this.maskArray[mai] = RegExp.escape(mask.charAt(i));
            mai++;
        }
    }

    this.specialChars = this.viewMask.replace(/(L|l|9|A|_|X)/g,'');
};

Ext.ux.netbox.InputTextMask.prototype = {

    init : function(field) {
        this.field = field;

        if (field.rendered){
            this.assignEl();
        } else {
            field.on('render', this.assignEl, this);
        }

        field.on('blur',this.removeValueWhenInvalid, this);
        field.on('focus',this.processMaskFocus, this);
    },

    assignEl : function() {
        this.inputTextElement = this.field.getEl().dom;
        this.field.getEl().on('keypress', this.processKeyPress, this);
        this.field.getEl().on('keydown', this.processKeyDown, this);
        if(Ext.isSafari || Ext.isIE){
            this.field.getEl().on('paste',this.startTask,this);
            this.field.getEl().on('cut',this.startTask,this);
        }
        if(Ext.isGecko || Ext.isOpera){
            this.field.getEl().on('mousedown',this.setPreviousValue,this);
        }
        if(Ext.isGecko){
          this.field.getEl().on('input',this.onInput,this);
        }
        if(Ext.isOpera){
          this.field.getEl().on('input',this.onInputOpera,this);
        }
    },
    onInput : function(){
        this.startTask(false);
    },
    onInputOpera : function(){
      if(!this.prevValueOpera){
        this.startTask(false);
      }else{
        this.manageBackspaceAndDeleteOpera();
      }
    },
    
    manageBackspaceAndDeleteOpera: function(){
      this.inputTextElement.value=this.prevValueOpera.cursorPos.previousValue;
      this.manageTheText(this.prevValueOpera.keycode,this.prevValueOpera.cursorPos);
      this.prevValueOpera=null;
    },

    setPreviousValue : function(event){
        this.oldCursorPos=this.getCursorPosition();
    },

    getValidatedKey : function(keycode, cursorPosition) {
        var maskKey = this.maskArray[cursorPosition.start];
        if(maskKey == '9'){
            return keycode.pressedKey.match(/[0-9]/);
        } else if(maskKey == 'L'){
            return (keycode.pressedKey.match(/[A-Za-z]/))? keycode.pressedKey.toUpperCase(): null;
        } else if(maskKey == 'l'){
            return (keycode.pressedKey.match(/[A-Za-z]/))? keycode.pressedKey.toLowerCase(): null;
        } else if(maskKey == 'A'){
            return keycode.pressedKey.match(/[A-Za-z0-9]/);
        } else if(maskKey){
            return (keycode.pressedKey.match(new RegExp(maskKey)));
        }
        return(null);
    },

    removeValueWhenInvalid : function() {
        if(this.clearWhenInvalid && this.inputTextElement.value.indexOf('_') > -1){
            this.inputTextElement.value = '';
        }
    },

    managePaste : function() {
        if(this.oldCursorPos==null){
          return;
        }
        var valuePasted=this.inputTextElement.value.substring(this.oldCursorPos.start,this.inputTextElement.value.length-(this.oldCursorPos.previousValue.length-this.oldCursorPos.end));
        if(this.oldCursorPos.start<this.oldCursorPos.end){//there is selection...
          this.oldCursorPos.previousValue=
            this.oldCursorPos.previousValue.substring(0,this.oldCursorPos.start)+
            this.viewMask.substring(this.oldCursorPos.start,this.oldCursorPos.end)+
            this.oldCursorPos.previousValue.substring(this.oldCursorPos.end,this.oldCursorPos.previousValue.length);
          valuePasted=valuePasted.substr(0,this.oldCursorPos.end-this.oldCursorPos.start);
        }
        this.inputTextElement.value=this.oldCursorPos.previousValue;
        keycode={unicode :'',
        isShiftPressed: false,
        isTab: false,
        isBackspace: false,
        isLeftOrRightArrow: false,
        isDelete: false,
        pressedKey : ''
        }
        var charOk=false;
        for(var i=0;i<valuePasted.length;i++){
            keycode.pressedKey=valuePasted.substr(i,1);
            keycode.unicode=valuePasted.charCodeAt(i);
            this.oldCursorPos=this.skipMaskCharacters(keycode,this.oldCursorPos);
            if(this.oldCursorPos===false){
                break;
            }
            if(this.injectValue(keycode,this.oldCursorPos)){
                charOk=true;
                this.moveCursorToPosition(keycode, this.oldCursorPos);
                this.oldCursorPos.previousValue=this.inputTextElement.value;
                this.oldCursorPos.start=this.oldCursorPos.start+1;
            }
        }
        if(!charOk && this.oldCursorPos!==false){
            this.moveCursorToPosition(null, this.oldCursorPos);
        }
        this.oldCursorPos=null;
    },

    processKeyDown : function(e){
        this.processMaskFormatting(e,'keydown');
    },

    processKeyPress : function(e){
        this.processMaskFormatting(e,'keypress');
    },

    startTask : function(setOldCursor){
        if(this.task==undefined){
            this.task=new Ext.util.DelayedTask(this.managePaste,this);
      }
        if(setOldCursor!== false){
            this.oldCursorPos=this.getCursorPosition();
      }
      this.task.delay(0);
    },

    skipMaskCharacters : function(keycode, cursorPos){
        if(cursorPos.start!=cursorPos.end && (keycode.isDelete || keycode.isBackspace))
            return(cursorPos);
        while(this.specialChars.match(RegExp.escape(this.viewMask.charAt(((keycode.isBackspace)? cursorPos.start-1: cursorPos.start))))){
            if(keycode.isBackspace) {
                cursorPos.dec();
            } else {
                cursorPos.inc();
            }
            if(cursorPos.start >= cursorPos.previousValue.length || cursorPos.start < 0){
                return false;
            }
        }
        return(cursorPos);
    },

    isManagedByKeyDown : function(keycode){
        if(keycode.isDelete || keycode.isBackspace){
            return(true);
        }
        return(false);
    },

    processMaskFormatting : function(e, type) {
        this.oldCursorPos=null;
        var cursorPos = this.getCursorPosition();
        var keycode = this.getKeyCode(e, type);
        if(keycode.unicode==0){//?? sometimes on Safari
            return;
        }
        if((keycode.unicode==67 || keycode.unicode==99) && e.ctrlKey){//Ctrl+c, let's the browser manage it!
            return;
        }
        if((keycode.unicode==88 || keycode.unicode==120) && e.ctrlKey){//Ctrl+x, manage paste
            this.startTask();
            return;
        }
        if((keycode.unicode==86 || keycode.unicode==118) && e.ctrlKey){//Ctrl+v, manage paste....
            this.startTask();
            return;
        }
        if((keycode.isBackspace || keycode.isDelete) && Ext.isOpera){
          this.prevValueOpera={cursorPos: cursorPos, keycode: keycode};
          return;
        }
        if(type=='keydown' && !this.isManagedByKeyDown(keycode)){
            return true;
        }
        if(type=='keypress' && this.isManagedByKeyDown(keycode)){
            return true;
        }
        if(this.handleEventBubble(e, keycode, type)){
            return true;
        }
        return(this.manageTheText(keycode, cursorPos));
    },
    
    manageTheText: function(keycode, cursorPos){
      if(this.inputTextElement.value.length === 0){
          this.inputTextElement.value = this.viewMask;
      }
      cursorPos=this.skipMaskCharacters(keycode, cursorPos);
      if(cursorPos===false){
          return false;
      }
      if(this.injectValue(keycode, cursorPos)){
          this.moveCursorToPosition(keycode, cursorPos);
      }
      return(false);
    },

    processMaskFocus : function(){
        if(this.inputTextElement.value.length == 0){
            var cursorPos = this.getCursorPosition();
            this.inputTextElement.value = this.viewMask;
            this.moveCursorToPosition(null, cursorPos);
        }
    },

    isManagedByBrowser : function(keyEvent, keycode, type){
        if(((type=='keypress' && keyEvent.charCode===0) ||
            type=='keydown') && (keycode.unicode==Ext.EventObject.TAB ||
            keycode.unicode==Ext.EventObject.RETURN ||
            keycode.unicode==Ext.EventObject.ENTER ||
            keycode.unicode==Ext.EventObject.SHIFT ||
            keycode.unicode==Ext.EventObject.CONTROL ||
            keycode.unicode==Ext.EventObject.ESC ||
            keycode.unicode==Ext.EventObject.PAGEUP ||
            keycode.unicode==Ext.EventObject.PAGEDOWN ||
            keycode.unicode==Ext.EventObject.END ||
            keycode.unicode==Ext.EventObject.HOME ||
            keycode.unicode==Ext.EventObject.LEFT ||
            keycode.unicode==Ext.EventObject.UP ||
            keycode.unicode==Ext.EventObject.RIGHT ||
            keycode.unicode==Ext.EventObject.DOWN)){
                return(true);
        }
        return(false);
    },

    handleEventBubble : function(keyEvent, keycode, type) {
        try {
            if(keycode && this.isManagedByBrowser(keyEvent, keycode, type)){
                return true;
            }
            keyEvent.stopEvent();
            return false;
        } catch(e) {
            alert(e.message);
        }
    },

    getCursorPosition : function() {
        var s, e, r;
        if(this.inputTextElement.createTextRange){
            r = document.selection.createRange().duplicate();
            r.moveEnd('character', this.inputTextElement.value.length);
            if(r.text === ''){
                s = this.inputTextElement.value.length;
            } else {
                s = this.inputTextElement.value.lastIndexOf(r.text);
            }
            r = document.selection.createRange().duplicate();
            r.moveStart('character', -this.inputTextElement.value.length);
            e = r.text.length;
        } else {
            s = this.inputTextElement.selectionStart;
            e = this.inputTextElement.selectionEnd;
        }
        return this.CursorPosition(s, e, r, this.inputTextElement.value);
    },

    moveCursorToPosition : function(keycode, cursorPosition) {
        var p = (!keycode || (keycode && keycode.isBackspace ))? cursorPosition.start: cursorPosition.start + 1;
        if(this.inputTextElement.createTextRange){
            cursorPosition.range.move('character', p);
            cursorPosition.range.select();
        } else {
            this.inputTextElement.selectionStart = p;
            this.inputTextElement.selectionEnd = p;
        }
    },

    injectValue : function(keycode, cursorPosition) {
        if (!keycode.isDelete && keycode.unicode == cursorPosition.previousValue.charCodeAt(cursorPosition.start))
            return true;
        var key;
        if(!keycode.isDelete && !keycode.isBackspace){
            key=this.getValidatedKey(keycode, cursorPosition);
        } else {
            if(cursorPosition.start == cursorPosition.end){
                key='_';
                if(keycode.isBackspace){
                    cursorPosition.dec();
                }
            } else {
                key=this.viewMask.substring(cursorPosition.start,cursorPosition.end);
            }
        }
        if(key){
            this.inputTextElement.value = cursorPosition.previousValue.substring(0,cursorPosition.start)
                + key +
                cursorPosition.previousValue.substring(cursorPosition.start + key.length,cursorPosition.previousValue.length);
            return true;
        }
        return false;
    },

    getKeyCode : function(onKeyDownEvent, type) {
        var keycode = {};
        keycode.unicode = onKeyDownEvent.getKey();
        keycode.isShiftPressed = onKeyDownEvent.shiftKey;
        
        keycode.isDelete = ((onKeyDownEvent.getKey() == Ext.EventObject.DELETE && type=='keydown') || ( type=='keypress' && onKeyDownEvent.charCode===0 && onKeyDownEvent.keyCode == Ext.EventObject.DELETE))? true: false;
        keycode.isTab = (onKeyDownEvent.getKey() == Ext.EventObject.TAB)? true: false;
        keycode.isBackspace = (onKeyDownEvent.getKey() == Ext.EventObject.BACKSPACE)? true: false;
        keycode.isLeftOrRightArrow = (onKeyDownEvent.getKey() == Ext.EventObject.LEFT || onKeyDownEvent.getKey() == Ext.EventObject.RIGHT)? true: false;
        keycode.pressedKey = String.fromCharCode(keycode.unicode);
        return(keycode);
    },

    CursorPosition : function(start, end, range, previousValue) {
        var cursorPosition = {};
        cursorPosition.start = isNaN(start)? 0: start;
        cursorPosition.end = isNaN(end)? 0: end;
        cursorPosition.range = range;
        cursorPosition.previousValue = previousValue;
        cursorPosition.inc = function(){cursorPosition.start++;cursorPosition.end++;};
        cursorPosition.dec = function(){cursorPosition.start--;cursorPosition.end--;};
        return(cursorPosition);
    }
};

Ext.applyIf(RegExp, {
  escape : function(str) {
    return new String(str).replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
  }
});

Ext.ux.InputTextMask=Ext.ux.netbox.InputTextMask;