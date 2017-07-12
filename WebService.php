<?php 
require_once('Tangiveis.php');


$jsonReturn = OperacaoBuscaTangiveis();


header('Content-type: application/json');


echo json_encode($jsonReturn);



