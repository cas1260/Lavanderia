<?php
	session_start();
	include 'wb_funcao.php';

	$cn = abrebanco();

	@$varspace       = $_GET['varspace'];
	@$txtdatainicial = $_POST['txtdatainicial'];
	@$txtdatafinal   = $_POST['txtdatafinal'];
	@$idcliente      = $_POST['idcliente'];
	
	if ($txtdatainicial != '') {
		
		$sql = "select distinct b.id, b.codigo, b.nome as cliente, b.fantasia, b.doc, SUM(a.valortotal) as valor, count(b.id) as totalQtd from tblromanei a 
					inner join tblcliente b on a.idcliente = b.id
					left join tblitennf c on a.id = c.idromanei 
					where a.data >= '".formatadata($txtdatainicial)."' and a.data <= '".formatadata($txtdatafinal)."'";
		if ($idcliente != '0'){
			$sql = $sql . " and a.idcliente = $idcliente ";
		}
		$sql = $sql . " group by b.codigo, b.nome, b.fantasia, b.doc order by b.nome ";
		//echo "/* $sql  */ ";
		montastored($sql, $cn);
		exit();
	}
	
	
?>	
storecliente<?php echo $namespace?>   = <?php rsdados("SELECT id, nome FROM tblcliente order by nome", $cn, "['0', 'Todos'], ")?>




mydados<?php echo $namespace?> = Ext.extend(Ext.data.JsonStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        mydados<?php echo $namespace?>.superclass.constructor.call(this, Ext.apply({
            storeId: 'dados<?php echo $namespace?>',
            url: 'php/wb_relatorio_mov_simples.php',
            root: 'rows',
            totalProperty: 'results',
            autoDestroy: true,
            fields: [
				{
                    name: 'id'
                },				{
                    name: 'codigo'
                },
                {
                    name: 'cliente'
                },
                {
                    name: 'fantasia'
                },
                {
                    name: 'doc'
                },
                {
                    name: 'valor'
                },
                {
                    name: 'totalQtd'
                }
            ]
        }, cfg));
    }
});


MYDADOS<?php echo $namespace?> = new mydados<?php echo $namespace?>();

MYDADOS<?php echo $namespace?>.on('load', function(){

	Ext.MessageBox.hide()

});

pesquisaRomanei<?php echo $namespace?> = function(){

	Ext.MessageBox.wait('Aguarde, Pesquisando informações no banco de dados.', 'webLuvas');	

	if (Ext.getCmp('<?php echo $namespace?>txtdatainicial').isValid() == false){
		MSG('Data inicial invalida!', function(){Ext.getCmp('<?php echo $namespace?>txtdatainicial').focus();});
		return false;
	}
	

	if (Ext.getCmp('<?php echo $namespace?>txtdatafinal').isValid() == false){
		MSG('Data final invalida!', function(){		Ext.getCmp('<?php echo $namespace?>txtdatafinal').focus();});
		return false;
	}

	
	Dados = OBJ('<?php echo $namespace?>gridsimples').store;
	Dados.setBaseParam("txtdatainicial",  Ext.getCmp('<?php echo $namespace?>txtdatainicial').getRawValue());
	Dados.setBaseParam("txtdatafinal",  Ext.getCmp('<?php echo $namespace?>txtdatafinal').getRawValue());
	Dados.setBaseParam("idcliente",  OBJ('<?php echo $namespace?>idcliente').getValue());
	Dados.reload();

}

printReport<?php echo $namespace?> = function(){

	GRID = Ext.getCmp('<?php echo $namespace?>gridsimples');
	
	Data = Ext.getCmp('<?php echo $namespace?>txtdatainicial').getRawValue() + " A ";
	Data = Data + Ext.getCmp('<?php echo $namespace?>txtdatafinal').getRawValue();
	
	Ext.ux.GridPrinter.print(GRID, "Relátorio de clientes simplificado</font><br>Periodo: "+Data);


}


RelMovCliUi = Ext.extend(Ext.Window, {
    title: 'Relátorio de Movimento de cliente Completo',
    width: 806,
    height: 438,
    layout: 'absolute',
    resizable: false,
    initComponent: function() {
        this.items = [
            {
                xtype: 'container',
                width: 180,
                height: 30,
                x: -7,
                y: 10,
                layout: 'form',
                labelAlign: 'right',
                labelWidth: 70,
                items: [
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Periodo',
                        anchor: '100%',
                        selectOnFocus: true,
                        id: '<?php echo $namespace?>txtdatainicial'
                    }
                ]
            },
            {
                xtype: 'container',
                width: 180,
                height: 40,
                x: 135,
                y: 10,
                layout: 'form',
                labelAlign: 'right',
                labelWidth: 70,
                items: [
                    {
                        xtype: 'datefield',
                        fieldLabel: 'até',
                        anchor: '100%',
                        selectOnFocus: true,
                        id: '<?php echo $namespace?>txtdatafinal'
                    }
                ]
            },
            {
                xtype: 'container',
                width: 400,
                height: 35,
                x: 295,
                y: 10,
                layout: 'form',
                labelAlign: 'right',
                labelWidth: 70,
                items: [
                    {
                        xtype: 'combo',
                        fieldLabel: 'Cliente',
                        anchor: '100%',
                        selectOnFocus: true,
                        forceSelection: true,
						store: storecliente<?php echo $namespace?>,
						triggerAction: 'all',
                        id: '<?php echo $namespace?>idcliente'
                    }
                ]
            },
            {
                xtype: 'button',
                text: 'Pesquisa',
                x: 700,
                y: 10,
                width: 80,
                id: '<?php echo $namespace?>cmdpesq'
            },
            {
                xtype: 'button',
                text: 'Cancelar',
                x: 700,
                y: 380,
                width: 80,
                id: '<?php echo $namespace?>cmdcancelar'
            },
            {
                xtype: 'button',
                text: 'Imprimir',
                x: 5,
                y: 380,
                width: 80,
                id: '<?php echo $namespace?>cmdimprimir'
            },
            {
                xtype: 'grid',
                title: 'Simples',
                store: 'dados<?php echo $namespace?>',
                x: 5,
                y: 40,
                width: 775,
                height: 335,
                id: '<?php echo $namespace?>gridsimples',
                columns: [
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'codigo',
                        header: 'Código',
                        sortable: true,
                        width: 70
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'cliente',
                        header: 'Cliente',
                        sortable: true,
                        width: 260
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'fantasia',
                        header: 'Fantasia',
                        sortable: true,
                        width: 165
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'doc',
                        header: 'CNPJ/;CPF',
                        sortable: true,
                        width: 120
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'valor',
                        header: 'Valor',
                        sortable: true,
                        width: 80,
                        align: 'right'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'totalQtd',
                        header: 'Qtd',
                        sortable: true,
                        width: 70,
                        align: 'right'
                    }
                ]
            }
        ];
        RelMovCliUi.superclass.initComponent.call(this);
    }
});



var RelMovCliUi<?php echo $namespace?> = new RelMovCliUi({
	id : 'RelMovCliUi<?php echo $namespace?>',
	name : 'RelMovCliUi<?php echo $namespace?>',
	renderTo: Ext.getBody()
});

RelMovCliUi<?php echo $namespace?>.show();
addtask('RelMovCliUi<?php echo $namespace?>');

OBJ('<?php echo $namespace?>cmdpesq').on('click', pesquisaRomanei<?php echo $namespace?>);
OBJ('<?php echo $namespace?>cmdimprimir').on('click', printReport<?php echo $namespace?>);
OBJ('<?php echo $namespace?>cmdcancelar').on('click', function(){OBJ('RelMovCliUi<?php echo $namespace?>').close();});

setTimeout("trocaTAB();Ext.getCmp('<?php echo $namespace?>txtdatainicial').focus();",1000);
OBJ('<?php echo $namespace?>idcliente').setValue(0);

detalheos<?php echo $namespace;?> = function(){
	
	Data = "data1=" + Ext.getCmp('<?php echo $namespace?>txtdatainicial').getRawValue() + "&data2=";
	Data = Data + Ext.getCmp('<?php echo $namespace?>txtdatafinal').getRawValue();
	
	varID = Ext.getCmp('<?php echo $namespace?>gridsimples').getSelectionModel().getSelected().data.id;
	window.open('./php/wb_print_romanei_all.php?print=false&id='+varID + '&' + Data, "_new");
}

Ext.getCmp('<?php echo $namespace?>gridsimples').on('dblclick', detalheos<?php echo $namespace;?>, this)


