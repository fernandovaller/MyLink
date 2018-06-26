<?php
/*
* Funções que acessam ou utilizam o banco de dados
* todas as funções aqui devem ser restritas ao negocio (tipo de sistema)
*/

/* 
* Obtem a url tratada e limpa
* 0 => string 'controller' (length=10)
* 1 => string 'action' (length=6)
* 2 => string 'param' (length=5)
*/
function URL($url = '', $remove_zero = true){
  if(empty($url) || $url == null) 
    return false;

  $_url = explode('/', filter_var( rtrim(urldecode($url), '/') , FILTER_SANITIZE_URL));  
  $_url = array_filter(array_map('anti_injection', $_url));    
  if($remove_zero) array_shift($_url);
  return $_url;  
}


function getURL($index, $default = null) {
    $url = URL($_SERVER['REQUEST_URI'], false);
    return (isset($url[$index]) && !empty($url[$index])) ? $url[$index] : $default;
}

function url_get_page($url){
  $aux = explode("?", $url);
  return $aux[0];
}

function em_manutencao($val = false){  
  if($val) die("SITE EM MANUTENÇÂO :(");
}

function debug(){
  date_default_timezone_set('America/Sao_Paulo');
  //define('DEBUG', true);
  //error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
  //ini_set('display_errors', DEBUG ? 'On' : 'Off');
}

function helper_sql_regcase($str){

    $res = "";

    $chars = str_split($str);
    foreach($chars as $char){
        if(preg_match("/[A-Za-z]/", $char))
            //$res .= "[".mb_strtoupper($char, 'UTF-8').mb_strtolower($char, 'UTF-8')."]";
            $res .= "[".strtoupper($char).strtolower($char)."]";
        else
            $res .= $char;
    }

    return $res;
}

function anti_injection($sql){
  //$sql = preg_replace(helper_sql_regcase("/(http|www|wget|from|select|insert|delete|where|.dat|.txt|.gif|drop table|show tables| or |#|\*|--|\\\\)/"),"",$sql);
  $sql = preg_replace(helper_sql_regcase("/(http|www|wget|from|select|insert|where|.txt|.gif|drop table|show tables| or |#|\*|--|\\\\)/"),"",$sql);
  $sql = trim($sql);
  $sql = strip_tags($sql);
  $sql = addslashes($sql);
  return $sql;
}

function anti_injection2($sql){
  $sql = preg_replace(helper_sql_regcase("/(http|www|wget|from|select|insert|delete|where|.dat|.txt|.gif|drop table|show tables| or |#|\*|--|\\\\)/"),"",$sql);
  $sql = trim($sql);  
  $sql = addslashes($sql);
  return $sql;
}

function anti_script($sql){
  $sql = preg_replace(helper_sql_regcase("/(http|www|wget|from|select|insert|delete|where|.dat|.txt|.gif|drop table|show tables| or |#|\*|--|\\\\)/"),"",$sql);
  $sql = trim($sql);
  $sql = str_replace('script',"",$sql);
  $sql = addslashes($sql);
  return $sql;
}

//tranforma data tipo DD/MM/AAAA para AAAA-MM-DD
function converter_dma_amd($data_dma){
  //trasforma data para inclusao no MySQL
  $data_array = split("/",$data_dma);
  $data = $data_array[2] ."-".$data_array[1]."-".$data_array[0];
  return $data;
}

function data_mysql($data_dma){
  //trasforma data para inclusao no MySQL
  $data_array = split("/",$data_dma);
  $data = $data_array[2] ."-".$data_array[1]."-".$data_array[0];
  return $data;
}

function ajustar_data($valor){
  $data = date("d.m.Y", strtotime($valor));
  $hora = date("G:i", strtotime($valor));
  return $hora . ' ' . $data;
}

function data($valor){
  if($valor){
    $data = date("d/m/Y", strtotime($valor));
    $hora = date("G:i", strtotime($valor));
    return $data.' '.$hora;
  }
}

function data2($valor){
  if($valor){
    $data = date("d/m/Y", strtotime($valor));
    $hora = date("G:i", strtotime($valor));
    if($data === date("d/m/Y")){ $data = 'Hoje'; }
    return $data.' '.$hora;
  }
}

function data_hora($valor){
  if($valor && $valor != '0000-00-00 00:00:00'){
    $data = date("d/m/Y", strtotime($valor));
    $hora = date("G:i:s", strtotime($valor));
    return $data.' '.$hora;
  }
}

function data_hora2($valor){
  if($valor && $valor != '0000-00-00 00:00:00'){        
    return gmdate('d/m/Y G:i', strtotime($valor));
  }
}

function data_br($valor){
  if($valor){
    $data = date("d/m/Y", strtotime($valor));    
    return $data;
  }
}

function menu($valor, $link, $option = 'active'){
  return $valor == $link ? 'class="'.$option.'"' : '';
}


#LIMITA TEXTO POR QUANTIDADE PALAVRAS
function limitador($palavras,$texto) {
  $texto = explode(" ", $texto);
  $texto = preg_replace("/<(\/)?p>/i", "", $texto);
  for ($i=0;$i<$palavras;$i++) {
    $texto_ok = $texto_ok." ".$texto[$i];
  }
  $texto_ok = trim($texto_ok);
  $texto_ok = $texto_ok."";
  $texto_ok = trim($texto_ok);
  $texto_ok = strip_tags($texto_ok);
  return "$texto_ok";
}


//REMOVE OS ACENTOS SEM PROBLEMAS DE ENCODES
//$enc pode ser [iso-8859-1/UTF-8/etc..]
function RemoveAcentos($str, $enc = 'UTF-8'){

  $acentos = array(
      'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
      'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
      'C' => '/&Ccedil;/',
      'c' => '/&ccedil;/',
      'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
      'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
      'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
      'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
      'N' => '/&Ntilde;/',
      'n' => '/&ntilde;/',
      'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
      'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
      'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
      'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
      'Y' => '/&Yacute;/',
      'y' => '/&yacute;|&yuml;/',
      'a.' => '/&ordf;/',
      'o.' => '/&ordm;/'
  );

    return preg_replace($acentos, array_keys($acentos), htmlentities($str,ENT_NOQUOTES, $enc));
}

function RemovePontuacao($string){
  $especiais= Array(".",",",";","!","@","#","%","¨","*","(",")","+","-","=",
  "§","$","|","\\",":","/","<",">","?","{","}","[","]","&","'",'"',"´","`","?",'“','”','$',"'","'");
  $string = str_replace($especiais,"",trim($string));
  return $string;
}

function remove_accents($string) {
  if ( !preg_match('/[\x80-\xff]/', $string) )
    return $string;

  if (seems_utf8($string)) {
    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
    chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
    chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
    chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
    chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
    chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
    chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
    chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
    chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
    chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
    chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
    chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
    chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
    chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
    // Decompositions for Latin Extended-B
    chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
    chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
    // Euro Sign
    chr(226).chr(130).chr(172) => 'E',
    // GBP (Pound) Sign
    chr(194).chr(163) => '');

    $string = strtr($string, $chars);
  } else {
    // Assume ISO-8859-1 if not UTF-8
    $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
      .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
      .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
      .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
      .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
      .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
      .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
      .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
      .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
      .chr(252).chr(253).chr(255);

    $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

    $string = strtr($string, $chars['in'], $chars['out']);
    $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
    $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
    $string = str_replace($double_chars['in'], $double_chars['out'], $string);
  }

  return $string;
}

function seems_utf8($str) {
  $length = strlen($str);
  for ($i=0; $i < $length; $i++) {
    $c = ord($str[$i]);
    if ($c < 0x80) $n = 0; # 0bbbbbbb
    elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
    elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
    elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
    elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
    elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
    else return false; # Does not match any model
    for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
      if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
        return false;
    }
  }
  return true;
}

//URL AMIGAVEL
function nome_url_2($id, $titulo){
  $titulo = RemoveAcentos($titulo);
  $titulo = RemovePontuacao($titulo);
  $titulo = trim(strtolower($titulo));
  $titulo = str_replace("  ",'-', $titulo);
  $new_url = str_replace(" ", '-', $titulo);
  //$new_url = str_replace('--', '-', $titulo);
  $new_url = "$new_url-$id.html";
  return $new_url;
}

function nome_url($id, $titulo){
  $pword = array(' diz ', ' que ', ' pra ', ' ou ', ' mas ', ' seu ', ' ter ', ' dar ', ' a ', ' e ', ' i ', ' o ', ' u ', ' de ', ' em ', ' por ', ' ao ', ' do ', ' no ', ' pelo ', ' da ', ' na ', ' pela ', ' os ', ' aos ', ' dos ', ' nos ', ' pelos ', ' as ', ' das ', ' nas ', ' um ', ' dum ', ' num ', ' uma ', ' duma ', ' numa ', ' uns ', ' duns ', ' nuns ', ' umas ', ' dumas ', ' numas ', ' ante ', ' apos ', ' ate', ' com ', ' de ', ' desde ', ' em ', ' entre ', ' para ', ' por ', ' sem ', ' sob ', ' sobre ', ' tras ', ' so ', ' assim', ' b ', ' c ', ' d ', ' f ', ' g ', ' h ', ' j ', ' l ',' m ', ' n ',' p ', ' q ', ' r ', ' s ', ' t ', ' v ', ' x ', ' z ', ' w ', ' y ');
  $a = array('&agrave', '&aacute', '&acirc', '&atilde', '&auml', '&aring', '&aelig', '&ccedil', '&egrave', '&eacute', '&ecirc', '&euml', '&igrave', '&iacute', '&icirc', '&iuml', '&eth', '&ntilde', '&ograve', '&oacute', '&ocirc', '&otilde', '&ouml', '&ugrave', '&uacute', '&ucirc', '&uuml', '&quot');
  $b = array('&#224;', '&#225;', '&#226;', '&#227;', '&#228;', '&#229;', '&#230;', '&#231;', '&#232;', '&#233;', '&#234;', '&#235;', '&#236;', '&#237;', '&#238;', '&#239;', '&#240;', '&#241;', '&#242;', '&#243;', '&#244', '&#245;', '&#246;', '&#249;', '&#250;', '&#251;', '&#252;', '&#34;');
  $c = array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', '');
  if($titulo){
    $titulo_raw = str_replace($a, $c, $titulo);
    $titulo_raw = str_replace($b, $c, $titulo_raw);
    $titulo_raw = remove_accents($titulo_raw);
    $titulo_raw = preg_replace('/[^a-z]/i', ' ', $titulo_raw);
    $titulo_raw = strtolower($titulo_raw);
    $titulo_raw = str_replace($pword, " ", $titulo_raw);
    $titulo_raw_array = str_word_count($titulo_raw, 1);
    foreach($titulo_raw_array as $word){ $titulo_raw_a[] = trim($word); $i++; if($i >= 10) break; }
    $titulo_raw = implode("-", $titulo_raw_a);
  }
  return $titulo_raw."-$id.html";
}

function url_sanitize($url){
  $pword = array(' diz ', ' que ', ' pra ', ' ou ', ' mas ', ' seu ', ' ter ', ' dar ', ' a ', ' e ', ' i ', ' o ', ' u ', ' de ', ' em ', ' por ', ' ao ', ' do ', ' no ', ' pelo ', ' da ', ' na ', ' pela ', ' os ', ' aos ', ' dos ', ' nos ', ' pelos ', ' as ', ' das ', ' nas ', ' um ', ' dum ', ' num ', ' uma ', ' duma ', ' numa ', ' uns ', ' duns ', ' nuns ', ' umas ', ' dumas ', ' numas ', ' ante ', ' apos ', ' ate', ' com ', ' de ', ' desde ', ' em ', ' entre ', ' para ', ' por ', ' sem ', ' sob ', ' sobre ', ' tras ', ' so ', ' assim', ' b ', ' c ', ' d ', ' f ', ' g ', ' h ', ' j ', ' l ',' m ', ' n ',' p ', ' q ', ' r ', ' s ', ' t ', ' v ', ' x ', ' z ', ' w ', ' y ');
  $a = array('&agrave', '&aacute', '&acirc', '&atilde', '&auml', '&aring', '&aelig', '&ccedil', '&egrave', '&eacute', '&ecirc', '&euml', '&igrave', '&iacute', '&icirc', '&iuml', '&eth', '&ntilde', '&ograve', '&oacute', '&ocirc', '&otilde', '&ouml', '&ugrave', '&uacute', '&ucirc', '&uuml', '&quot');
  $b = array('&#224;', '&#225;', '&#226;', '&#227;', '&#228;', '&#229;', '&#230;', '&#231;', '&#232;', '&#233;', '&#234;', '&#235;', '&#236;', '&#237;', '&#238;', '&#239;', '&#240;', '&#241;', '&#242;', '&#243;', '&#244', '&#245;', '&#246;', '&#249;', '&#250;', '&#251;', '&#252;', '&#34;');
  $c = array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', '');
  if($titulo){
    $titulo_raw = str_replace($a, $c, $titulo);
    $titulo_raw = str_replace($b, $c, $titulo_raw);
    $titulo_raw = remove_accents($titulo_raw);
    $titulo_raw = preg_replace('/[^a-z]/i', ' ', $titulo_raw);
    $titulo_raw = strtolower($titulo_raw);
    $titulo_raw = str_replace($pword, " ", $titulo_raw);
    $titulo_raw_array = str_word_count($titulo_raw, 1);
    foreach($titulo_raw_array as $word){ $titulo_raw_a[] = trim($word); $i++; if($i >= 10) break; }
    $titulo_raw = implode("-", $titulo_raw_a);
  }
  return $titulo_raw;
}

function href($titulo){
  $titulo = RemoveAcentos($titulo);
  $titulo = RemovePontuacao($titulo);
  $titulo = strtolower($titulo);
  $titulo = str_replace('  ',' ', $titulo);
  $new_url = str_replace(' ', '-', $titulo);
  $new_url = "$new_url";
  return $new_url;
}

function trata_url($url_exp){
  $url_exp = explode ("-", $url_exp);
  $url_exp = array_reverse($url_exp);
  return (int) $url_exp[0];
}

function get_url_id($url){
  $url_aux = explode("-", $url);
  $url_aux = array_reverse($url_aux);
  $url_aux = str_replace('.html', '', $url_aux);
  $id = $url_aux[0];
  if($id){ return $id; }
}

/* retorna um hash */
function get_alunos_hash_gerar($id){
  $code = md5($id.'pgesenha');
  return $code;
}

function alunos_nome($nome){
  $aux = explode(" ", $nome);
  return $aux[0].' '.$aux[1];
}

function arquivos_deletar($arquivo, $path){
    if($arquivo){
      if(file_exists($path.$arquivo)){
        unlink($path.$arquivo);
        return true;
      }
    }
}


//final da funcao de upload
function upload_file($arquivo, $caminho, $name = ''){

  if(!(empty($arquivo))){

    $arquivo1 = $arquivo;

    preg_match("/\.(doc|docx|pdf|xml){1}$/i", $arquivo1['name'], $ext);

    if($ext){

      $arquivo_tratado = md5(uniqid(time())) . "." . strtolower($ext[1]);

      $arquivo_tratado = $name != '' ? $name.'-'.$arquivo_tratado : $arquivo_tratado;

      $destino = $caminho.$arquivo_tratado;

      move_uploaded_file($arquivo1['tmp_name'], $destino);

      return $arquivo_tratado;

    }

  }

}

//final da funcao de upload
function upload($arquivo, $caminho, $name = ''){

  if(!(empty($arquivo))){

    $arquivo1 = $arquivo;

    preg_match("/\.(png|jpg|jpeg){1}$/i", $arquivo1['name'], $ext);

    $arquivo_tratado = md5(uniqid(time())) . "." . strtolower($ext[1]);

    $arquivo_tratado = $name != '' ? $name.'-'.$arquivo_tratado : $arquivo_tratado;

    $destino = $caminho.$arquivo_tratado;

    move_uploaded_file($arquivo1['tmp_name'], $destino);

    return $arquivo_tratado;
  }

}

function limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    $text = implode(" ",array_splice($words,0,$word_limit));
    return strip_tags($text);
}

function moeda($valor) {
  if($valor){
    $valor = str_replace(",", "", $valor);
    $valor = number_format($valor, 2, ',', '.');
    return $valor;
  } else { return '0,00'; }
}

function status($status){

  switch($status){
    case '0' : $r = 'Desativado'; $l = 'info'; break;
    case '1' : $r = 'Ativo'; $l = 'success'; break;
    default : $r = $status; break;
  }

  return "<div class=\"label label-{$l}\">{$r}</div>";
  //return $r;
}

function gera_chave_numerica($t){
  $car = "0123456789";
  for ($i = 0; $i < $t; $i++) {
    $key .= $car{rand(0, strlen($car) - 1)};
  }
  return $key;
}

function gera_chave($t){
    $car = "23456789ABCDEFGIJKMNPQRSTUVXYZ";
    for ($i = 0; $i < $t; $i++) {
        $key .= $car{rand(0, strlen($car) - 1)};
    }
    return $key;
}


function validar_email($email){
  if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    return true;
  }else{ return false; }
}


/* Retorna os option de uma consulta */
function form_select($sql, $value, $option, $selected = ''){

  $res = mysql_query("$sql");
  while($row = mysql_fetch_array($res)){
    if(is_array($selected)){      
      $sd = in_array($row[$value], $selected) ? 'selected="selected"' : '';
      $op .= "<option value=\"$row[$value]\" $sd>$row[$option] (#$row[$value])</option>";
    } else {
      $sd = ($selected == $row[$value]) ? 'selected="selected"' : '';
      $op .= "<option value=\"$row[$value]\" $sd>$row[$option] (#$row[$value])</option>";  
    }
    
  }

  return $op;
}

/* Retorna os option de uma consulta */
function form_select2($sql, $value, $option, $selected = ''){

  $res = mysql_query("$sql");
  while($row = mysql_fetch_array($res)){
    if(is_array($selected)){      
      $sd = in_array($row[$value], $selected) ? 'selected="selected"' : '';
      $op .= "<option value=\"$row[$value]\" $sd>$row[$option]</option>";
    } else {
      $sd = ($selected == $row[$value]) ? 'selected="selected"' : '';
      $op .= "<option value=\"$row[$value]\" $sd>$row[$option]</option>";  
    }
    
  }

  return $op;
}


function data_aviso($data, $dias, $return = 'icon'){

  //Comparação entre datas
  $d1 = new DateTime();
  $d2 = new DateTime($data);
  $diff = $d2->diff($d1);

    $days = $diff->days;

    if($days <= $dias){
        //return '<i class="fa fa-bell faa-ring animated ml10 text-warning" title="Novo Arquivo"></i>';
        return  $return == 'icon' ? '<img src="/images/icon-new.gif" title="Novo Arquivo">' : true;
    }

}

function porcento($valor1, $valor2, $precision = 0){
  if($valor1 && $valor2){
    return round(($valor1/$valor2) * 100, $precision);
  } else {  return '0'; }
}


function cpf_validar($cpf = null) {
 
    // Verifica se um número foi informado
    if(empty($cpf)) {
        return false;
    }
 
    // Elimina possivel mascara
    $cpf = ereg_replace('[^0-9]', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
     
    // Verifica se o numero de digitos informados é igual a 11 
    if (strlen($cpf) != 11) {
        return false;
    }
    
    // Verifica se nenhuma das sequências invalidas abaixo 
    // foi digitada. Caso afirmativo, retorna falso
    else if ($cpf == '00000000000' || 
        $cpf == '11111111111' || 
        $cpf == '22222222222' || 
        $cpf == '33333333333' || 
        $cpf == '44444444444' || 
        $cpf == '55555555555' || 
        $cpf == '66666666666' || 
        $cpf == '77777777777' || 
        $cpf == '88888888888' || 
        $cpf == '99999999999') {
        return false;
     // Calcula os digitos verificadores para verificar se o
     // CPF é válido
     } else {   
         
        for ($t = 9; $t < 11; $t++) {
             
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
 
        return true;
    }
}


function print_pre($value, $dump = false, $break = false){
  echo '<pre>';
  print_r($value);
  echo '</pre>';
  if($dump) var_dump($value);
  //if($break) { break; }
}

function redirect($url) {
    if (!headers_sent()) {
        header('Location: '.$url);        
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
    }
    exit();
}

function error_print($erros){
  if(count($errors) > 0){
    foreach ($erros as $key => $erro) {
      echo $erro;
    }
  }
}

function bs3_alert($msg, $tipo = 'success', $title = ''){
  return '<div class="alert alert-'.$tipo.'">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>'.$title.'</strong> '.$msg.'
  </div>';
}

function data_twitter($date){
  $return = '';
  if(!$date) return false;

  $date = strtotime($date);
  $diff = time() - $date;
  
  if ($diff < 86400) {
    if ($diff < 3600) { // Output e.g. 35 minutes ago
      $return = $mins = floor($diff / 60);
      $return .= ' min';
      //if ($mins > 1 || $mins === 0) $return .= 's';
      $return .= ' atrás'; 
    }
    else { // Output e.g. 5 hours ago
      $return = $hours = floor($diff / 3600);
      $return .= ' h';
      //if ($hours > 1 || $hours === 0) $return .= 's';
      $return .= ' atrás';
    }
  }
  else { // Output e.g. 1 Dec
    $return = date('j M', $date);
  }
  
  return $return;  
}

function get_msg(){  
  if(isset($_SESSION['msg'])){
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
  }     
  return $msg;
}

function set_msg($msg){  
  $_SESSION['msg'] = $msg; 
}

function menu_select($link, $select = 'active'){  
  if($_SERVER['QUERY_STRING'] == $link) return $select;
}

function get($name,$def='') {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $def;
}


