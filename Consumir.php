<?php

require_once('config/config.php');
require_once('Tangiveis.php');




    $json = json_decode(file_get_contents("http://localhost/webservice/WebService.php"));

    for($i = 0; $i < count($json); $i++) {

        

        try{
            ///// Dados Consumidos pelo WebService /////
            $num_chamado_legado = $json[$i]->{'num_chamado_Legado'};
            $num_chamado = $json[$i]->{'num_chamado'};
            $tipo_tangivel = $json[$i]->{'tipo_tangivel"'};
            $produto = $json[$i]->{'produto'};
            $apolice = $json[$i]->{'apolice'};
            $relacionado_a = $json[$i]->{'relacionado_a'};
            $cpf_cnpj = $json[$i]->{'cpf_cnpj'};
            $vigencia_seguro = $json[$i]->{'vigencia_seguro'};
            $num_certificado = $json[$i]->{'num_certificado'};
            $ano_periodo = $json[$i]->{'ano_periodo'};
            $id_atividade = $json[$i]->{'id_atividade'};



            $mysql = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME_WEBSERVICE.';charset=utf8', DB_USER, DB_PASS);
            $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_log_trans = "SELECT id_atividade FROM log_transmissao_tangiveis where id_atividade = '$id_atividade'";
            $stmt = $mysql->prepare($sql_log_trans);
            $stmt->execute();
            $resultado_log = $stmt->fetchAll();

            if(empty($resultado_log)){

                    ////// Verifica se foi trasmitido para o tangiveis o cliente /////
                $mysql = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME_WEBSERVICE.';charset=utf8', DB_USER, DB_PASS);
                $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                $mysql = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME_WEBSERVICE.';charset=utf8', DB_USER, DB_PASS);
                $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_trans = "INSERT INTO log_transmissao_tangiveis (id_atividade) values('$id_atividade')";
                $stmt = $mysql->prepare($sql_insert_trans);
                $stmt->execute();


                $consulta_seq = OperacaoCadastraTangiveis();

                $mysql = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME_TANGIVEIS.';charset=utf8', DB_USER, DB_PASS);
                $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql_potential = "SELECT distinct potentialid FROM tangiveis.vtiger_potential ORDER BY 1 DESC LIMIT 1";
                $stmt = $mysql->prepare($sql_potential);
                $stmt->execute();
                $resultado_potential_seq = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // PEGA SEQUENCIAL DA TABELA TANGIVEIS(PONTENTIAL)
                $resultado_potential_id = $resultado_potential_seq[0][potentialid];

                

                $sql_update = "UPDATE vtiger_potential SET potentialname = '$relacionado_a' where potentialid = $resultado_potential_id ";
                $stmt = $mysql->prepare($sql_update);
                $stmt->execute();


                $sql_update = "UPDATE vtiger_potentialscf SET cf_813 = '$apolice' where potentialid = $resultado_potential_id ";
                $stmt = $mysql->prepare($sql_update);
                $stmt->execute();

                echo "Consumido</br>";

            }else{


                echo "Ja Cadastrado\n";


            }

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            

            //////////////////// CONTINUE NO PROXIMO CAPITULO o CODIGO*///////////////////////


        }catch(PDOException $e){

            $fp = fopen($_SERVER['DOCUMENT_ROOT']."/webservice/LogError/Consumir_LogError.txt", "a"); 
         
            \fwrite($fp, utf8_encode($e->getMessage()));
         
        
            fclose($fp);



            exit;
    }

}




   
    


    

?>