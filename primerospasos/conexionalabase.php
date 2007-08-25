<?php

$base="mias_base";
$admin="mias_admin";
$var_adminp="base_pass";
$adminp="unknown";

setear_var($adminp,$_POST,$var_adminp) || setear_var($adminp,$_GET,$var_adminp) || setear_var($adminp,$_COOKIE,$var_adminp);

if($adminp=="unknown"){
  print("<h2>Falta indicar base_pass</h2>\n");
  die("<h3>error fatal</h3>");
}else{
  setearSiEsCorrecto($base,$admin,$adminp);
}

print "<h1>conexion a la base V0.04</h1>\n";

function setear_var(&$variable,$vector,$clave){
  // if(isset($vector[$clave])){
  if(array_key_exists($clave,$vector)){
    $variable=$vector[$clave];
    return true;
  }
  return false;
}

function setearSiEsCorrecto($base,$admin,$adminp){
  $rta=comprobarBase($admin,$adminp,$base);
  if($rta=="ok"){
    setcookie("base_pass",$adminp);
    print "<h2>Lista la cookie</h2>\n";
    print "<h2>Conexion correcta!</h2>\n";
  }else{
    print "<h2>Fall&oacute; la comprobaci&oacute;n de la base</h2>\n";
    print $rta;
  }
}

function comprobarBase($user,$password,$database){
  $connect_id = mysql_connect('localhost',$user,$password);
  if(!$connect_id){
    return("<p> Sin conexi&oacute;n para $user,$password,$database</p>");
  }
  if(!mysql_select_db($database)){
    return("<p> Sin base de datos {".mysql_error()."}</p>");
  }
  return "ok";
}


?>
