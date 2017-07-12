<?php 
version_compare(PHP_VERSION, '5.5.0') <= 0 ? error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED & E_ERROR) :error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED  & E_ERROR & ~E_STRICT);
//CAMINHO DE DIRETORIO DO SISTEMA
define('WEBSERVICE_IP','localhost');


//CONFIGURAÇÃO DE BASE DE DADOS OPERAÇÔES
define('DB_TYPE', 'mysql');
define('DB_HOST', '10.1.0.10');
///BASE DE DADOS OPERACOES
define('DB_NAME_OPERACOES', 'operacoes');
///BASE DE DADOS TANGIVEIS
define('DB_NAME_TANGIVEIS', 'tangiveis');
///BASE DE DADOS WEBSERVICE
define('DB_NAME_WEBSERVICE', 'WebService');
define('DB_USER', 'kleber.moris');
define('DB_PASS', 'p@ssw0rd');




?>




