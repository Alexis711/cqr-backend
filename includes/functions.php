<?php
//Funcion para generar id automatico
function gene_id(){
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
      mt_rand( 0, 0xffff ),
      mt_rand( 0, 0x0fff ) | 0x4000,
      mt_rand( 0, 0x3fff ) | 0x8000,
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
  );
}
//Funcion para obtener la fecha de Mexico
function gene_date_complete() {
    date_default_timezone_set('America/Mexico_City');
    setlocale(LC_TIME, 'es_MX.UTF-8');
    return  date('Y-m-d H:i:s');
}
//Funcion para obtener la fecha de Mexico
function gene_date() {
    date_default_timezone_set('America/Mexico_City');
    setlocale(LC_TIME, 'es_MX.UTF-8');
    return  date('Y-m-d');
}
//Funcion para obtener la fecha de Mexico
function gene_week_day() {
    date_default_timezone_set('America/Mexico_City');
    setlocale(LC_TIME, 'es_MX.UTF-8');
    return  date('w');
}
//Funcion para obtener la hora de Mexico
function gene_time() {
    date_default_timezone_set('America/Mexico_City');
    setlocale(LC_TIME, 'es_MX.UTF-8');
    $time = date('H:i:s');
    // Restar una hora
    $adjusted_time = date('H:i:s', strtotime($time) - 3600);
    return  $adjusted_time;
}
//Funcion para encriptar
function gene_encryp($string){
    $output=FALSE;
    $key=hash('sha512', SECRET_KEY);
    $iv=substr(hash('sha512', SECRET_IV), 0, 16);
    $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
    $output=base64_encode($output);
    return $output;
}
//Funcion para desencriptar
function gene_decryp($string){
    $key=hash('sha512', SECRET_KEY);
    $iv=substr(hash('sha512', SECRET_IV), 0, 16);
    $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
    return $output;
}

?>