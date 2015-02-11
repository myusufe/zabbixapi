#!/bin/php

 <?php

// load ZabbixApi
require 'ZabbixApiAbstract.class.php';
require 'ZabbixApi.class.php';

$GLOBALS["server_url"] = "http://10.203.24.127/zabbix/api_jsonrpc.php";
$GLOBALS["useradmin"] = "admin";
$GLOBALS["password"] = "zabbix";

$api = new ZabbixApi($GLOBALS["server_url"], $GLOBALS["useradmin"], $GLOBALS["password"]);


try {


    // get all items
        $inputdata = array(
              //  'search' => array('itemids' => '24115')
		'templateids' => '10114'
        );

    $templates = $GLOBALS["api"]->templateGet($inputdata);

    // print all triggerss component
    foreach($templates as $template){
        echo "Template name : ".$template->host."\n";
      }


} catch(Exception $e) {

    // Exception in ZabbixApi catched
    echo $e->getMessage();

}


?>
