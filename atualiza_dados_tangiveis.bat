@echo OFF
REM #*********************************************************************************
REM # CONSUMO DE DADOS WEBSERVICE INICIADO
REM #
REM # ********************************************************************************

set VTIGERCRM_ROOTDIR="C:\xampp\htdocs\webservice"
set PHP_EXE="C:\xampp\php\php.exe"

cd /D %VTIGERCRM_ROOTDIR%

%PHP_EXE% -f Consumir.php 