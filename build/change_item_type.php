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

        $inputdata = array(
	//	'itemids' => '24116',
		'templateids' => '10114'
        );

    $items = $GLOBALS["api"]->itemGet($inputdata);


    // print all triggerss component
    foreach($items as $item){
	$input_update = array(
		'itemid' => $item->itemid,
		'value_type' => "-1",
		'valuemapid' => "15",
	);
        //echo "Item name : ".$item->name." ".$item->itemid." ".$item->value_type."\n";

	$api->itemUpdate($input_update);
      }


} catch(Exception $e) {

    // Exception in ZabbixApi catched
    echo $e->getMessage();

}


?>
