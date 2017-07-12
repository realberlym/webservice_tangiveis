<?php 


header ('Content-type: text/html; charset=UTF-8');
header('Pragma: no-cache');

require_once('config/config.php');




function OperacaoBuscaTangiveis(){



	try{

			$mysql = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME_OPERACOES.';charset=utf8', DB_USER, DB_PASS);
			$mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT
					vtiger_troubletickets.title as 'num_chamado_legado',
					vtiger_troubletickets.ticket_no as 'num_chamado',
					vtiger_ticketcf.cf_1010 as 'tipo_tangivel',
					vtiger_ticketcf.cf_1034 as 'produto_tangivel',
					vtiger_ticketcf.cf_783 as 'produto',
					vtiger_ticketcf.cf_1026 as 'apolice',
					vtiger_account.accountname as 'relacionado_a',
					vtiger_accountscf.cf_751 as 'cpf_cnpj',
					vtiger_ticketcf.cf_1030 as 'vigencia_seguro',
					vtiger_ticketcf.cf_1032 as 'num_certificado',
					vtiger_ticketcf.cf_1036 as 'ano_periodo',
					vtiger_activitycf.activityid as 'id_atividade'
					FROM vtiger_seactivityrel
					INNER JOIN vtiger_ticketcf ON vtiger_seactivityrel.crmid = vtiger_ticketcf.ticketid
					INNER JOIN vtiger_activitycf ON vtiger_seactivityrel.activityid = vtiger_activitycf.activityid
					INNER JOIN vtiger_troubletickets ON vtiger_seactivityrel.crmid = vtiger_troubletickets.ticketid
					INNER JOIN vtiger_crmentity ON vtiger_seactivityrel.crmid = vtiger_crmentity.crmid
					INNER JOIN vtiger_account ON vtiger_troubletickets.parent_id = vtiger_account.accountid
					INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid
					WHERE vtiger_ticketcf.cf_1038 > 0 AND vtiger_crmentity.deleted = 0";

			
			$stmt = $mysql->prepare($sql);
			$stmt->execute();
			$resultado_row = $stmt->fetchAll(PDO::FETCH_ASSOC);

			
		

	}catch(PDOException $e){


			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/webservice/LogError/OperacaoBuscaTangiveis_LogError.txt", "a"); 
		 
			\fwrite($fp, utf8_encode($e->getMessage()));
		 
		
			fclose($fp);


		    

		exit;


	}

	return $resultado_row;
}


// TEM FUNÇÃO CADASTRAR AS TANGIVEIS NA TABELA DO VTIGER SEGUINDO A SINCRONIZAÇÃO DO VTIGER
function OperacaoCadastraTangiveis(){

	try{

		$mysql = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME_TANGIVEIS.';charset=utf8', DB_USER, DB_PASS);
		$mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//CONSULTA CRM SEQUENCIAL PARA QUE POSSA INCREMENTAL NA TABELA A BAIXO
		$sql_crmseq = "SELECT * FROM vtiger_crmentity_seq";
		$stmt = $mysql->prepare($sql_crmseq);
		$stmt->execute();
		$resultado_crm_seq = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// PEGA SEQUENCIAL E SOMA MAIS UM
		$resultado_crm_id = $resultado_crm_seq[0][id] + 1;

		date_default_timezone_set('America/Sao_Paulo');
		$date = date('Y-m-d H:i:s');


		//INSERIR OS DADOS NA TABELA CRM_ENTITY
		$sql_crm_entity = "INSERT INTO vtiger_crmentity 
						   (crmid, smcreatorid, smownerid, modifiedby, setype, createdtime, version, presence, deleted)
						   VALUES ($resultado_crm_id, 1, 1, 0, 'Potentials', '$date', 0, 1, 0)";
		$stmt = $mysql->prepare($sql_crm_entity);
		$stmt->execute();

		//INSERIR OS DADOS NA TABELA POTENTIAL(A TANGIVEL)
		$sql_pontential = "INSERT INTO vtiger_potential
							(potentialid, potential_no, related_to, potentialname, amount, closingdate)
							VALUES($resultado_crm_id, '', 0, '', 0.00000000, '$date')";
		$stmt = $mysql->prepare($sql_pontential);
		$stmt->execute();

		//INSERIR OS DADOS NA TABELA POTENTIAL_CF(A TANGIVEL CUSTOM FIELD)
		$sql_pontential_cf = "INSERT INTO vtiger_potentialscf
							  (potentialid)
							  VALUES($resultado_crm_id) ";
		$stmt = $mysql->prepare($sql_pontential_cf);
		$stmt->execute();

		//FAZ UPDATE NA TABELA REFERENCIAL DE ID CHAMADA CRM ENTITY ID
		$sql_update_crm_seq = "UPDATE vtiger_crmentity_seq SET id = $resultado_crm_id";
		$stmt = $mysql->prepare($sql_update_crm_seq);
		$stmt->execute();


	}catch(PDOException $e){


			

			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/webservice/LogError/OperacaoCadastraTangiveis_LogError.txt", "a"); 
		 
			\fwrite($fp, utf8_encode($e->getMessage()));
		 
		
			fclose($fp);


		exit;


	}

	return $resultado_row;

}


