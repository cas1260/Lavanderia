<?php

function whois ($domain, $tipo) { 
        /* 
   Desenvolvido por: Otavio James Bernardes Junior<br>
   Programação PHP - (0xx12) 9765-8667
        */ 
        $server[0] = "whois.registro.br"; //--> Domínios Nacionais 
        $server[1] = "whois.internic.net"; //--> Domínios .com, .net, .org, .edu 
        $server[2] = "whois.networksolutions.com"; //--> Domínios .aero, .arpa, .biz, .coop, .info, .int, .museum 

        $domain = strtolower($domain); 
        if (trim($domain) <> "") { 
                $domain = trim($domain); 
                $final = substr($domain, -4); 
                // Verifica dominios do brasil 
                if (substr("$domain", -3) == ".br") { 
                        $br = fsockopen($server[0], 43, $errno, $errstr, 30); 
                        if (!$br) { 
                                $dados .= "$errstr ($errno)"; 
                        } else { 
                                fputs($br, "$domain\r\n"); 
                                while (!feof($br)) { 
                                        $buffer .= fread($br,128); 
                                } 
                                if ($tipo == 1) { 
                                        if (strpos($buffer, "No match for") > 0) $dados .= false; else $dados .= true; 
                                } else { 
                                        $dados .= str_replace("\n","<BR>\n",trim($buffer)); 
                                } 
                                fclose ($br); 
                        } 

                // Verifica domínios .com, .net, .org, .edu 
                } elseif (($final == '.com') OR ($final == '.net') OR ($final == '.org') OR ($final == '.edu')) { 
                        $internic = fsockopen($server[1], 43, $errno, $errstr, 30); 
                        if (!$internic) { 
                                $dados .= "$errstr ($errno)"; 
                        } else { 
                                fputs($internic, "$domain\r\n"); 
                                while (!feof($internic)) { 
                                        $buffer .= fread($internic,128); 
                                } 
                                if ($tipo == 1) { 
                                        if (strpos($buffer, "No match for") > 0) $dados .= false; else $dados .= true; 
                                } else { 
                                        if (strpos($buffer, "No match for") > 0) { 
                                                $dados .= "<pre>" . trim($buffer) . "</pre>"; 
                                        } else { 
                                                $servidor = substr($buffer, strpos($buffer, "Whois Server:")+14, strlen($buffer)); 
                                                $servidor = substr($servidor, 0, strpos($servidor, "\n")); 
                                                $entidade = fsockopen("$servidor", 43, $errno, $errstr, 30); 
                                                if (!$entidade) { 
                                                        $dados .= "$errstr ($errno)"; 
                                                } else { 
                                                        $buffer .= "-------------------------------------------------------------------------------<BR> <BR>"; 
                                                        fputs($entidade, "$domain\r\n"); 
                                                        while (!feof($entidade)) { 
                                                                $buffer .= fread($entidade,128); 
                                                        } 
                                                        $dados .= str_replace("\n","<BR>\n",trim($buffer)); 
                                                        fclose ($entidade); 
                                                } 
                                        } 
                                } 
                                fclose ($internic); 
                        } 

                // Verifica os outros 
                } else { 
                        $network = fsockopen($server[2], 43, $errno, $errstr, 30); 
                        if (!$network) { 
                                $dados .= "$errstr ($errno)"; 
                        } else { 
                                fputs($network, "$domain\r\n"); 
                                while (!feof($network)) { 
                                        $buffer .= fread($network,128); 
                                } 
                                if ($tipo == 1) { 
                                         if (strpos($buffer, "NOT FOUND") > 0) $dados .= false; else $dados .= true; 
                                } else { 
                                        $dados .= str_replace("\n","<BR>\n",trim($buffer)); 
                                } 
                                fclose ($network); 
                        } 
                } 
        } 
return $dados; 
} 

?> 
<form method=get>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
  <input type=text name=dominio size=30>
  <input type=submit value=Consultar>
  </font> 
</form>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
<? if (strlen($_GET["dominio"]) > 0) { ?>
</font> 
<HR>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Resposta simplificada:</b><BR>
<BR>
O Domínio <b> 
<?=$_GET["dominio"]?>
</b> 
<? if (whois($_GET["dominio"],1)  == 1) echo "<br><b><img src=registrado.jpg width=32 height=32 align=absmiddle>  <font color=#FF0000 size=1 face=Verdana, Arial, Helvetica, sans-serif>DOMINIO REGISTRADO</font></b>"; else echo "<br><b><img src=livre.jpg  width=32 height=32 align=absmiddle><font color=#66CC66 size=1 face=Verdana, Arial, Helvetica, sans-serif>DOMINIO 
  LIVRE </b> </font>";?>
</font> 
<HR>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Resposta completa:</b><BR>
<BR>
<?= whois($_GET["dominio"],2)?>
<? } ?>
<br>
<br>
<br>