<?php
	function right($value, $count){
		return substr($value, ($count*-1));
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}
	$pasta = $_GET['pasta'];
	$diretorio = getcwd(); 
	$diretorio = $diretorio . "\\" . $pasta; 
	$ponteiro  = opendir($diretorio);
	while ($nome_itens = readdir($ponteiro)) {
		if ($nome_itens != '' && $nome_itens != '.' && $nome_itens != '..') {
			echo "." . left($nome_itens, strlen($nome_itens)-4) . "{".CHR(13);
			echo "	background-image: url(".$pasta.$nome_itens.") !important;".CHR(13);
			echo "}".CHR(13);
		}
	}
?>
