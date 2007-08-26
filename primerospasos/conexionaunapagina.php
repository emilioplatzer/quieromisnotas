<?php

$hoja=new hojeador;
$hoja->iniciar_variables();

if(isset($_GET['comenzar'])){
  $hoja->abrir_canal()
  && $hoja->mandar_autenticacion();
}else{
  phpinfo();
}

class hojeador{
  var $user;
  var $pass;
  var $planilla;
  var $canal;

  function iniciar_variables(){
    $this->user='quieromisnotas';
    $this->pass='perolasquieroya';
    $this->planilla='QuieroMisNotas-CasoEjemplo';
  }

  function debugm($mensaje){
    print "<p><b><i><small>$mensaje</small></i></b></p>\n";
  }

  function abrir_canal(){
    $errno=-1;
    $errstr="";
    $this->debugm("conectando");
    $this->canal=fsockopen("www.mias.com.ar", 80, $errno, $errstr, 30);
    $this->debugm("resultado conexión ($errno), ($errstr)");
    if($errno<>0){
      return false;
    }
    if(!stream_set_blocking($this->canal,false)){
      $this->debugm("no pude poner la conexión en no bloqueante");
      return false;
    }
    return true;
  }

  function mandar_autenticacion(){
    $vrta=
      "GET / HTTP/1.1\r\n".
      "Host: www.mias.com.ar\r\n".
      "Connection: Close\r\n\r\n";
    $this->debugm("enviando");
    fwrite($this->canal, $vrta);
    $this->debugm("recibiendo");
    $i=1;
    while (!feof($this->canal) && ($i<200000)) {
        $i++;
        $s=fgets($this->canal, 1000);
        if($s){
          print "<p>".$i.":".$s."</p>\n";
        }
    }
    $this->debugm("cerrando");
    fclose($this->canal);
    return true;
  }

}
?>
