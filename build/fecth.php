#!/bin/php

//
// Muhammad Yusuf Efendi
// myusufe@gmail.com
// 02/11/2015
//

 <?php

// load ZabbixApi
require 'ZabbixApiAbstract.class.php';
require 'ZabbixApi.class.php';

$GLOBALS["server_url"] = "http://10.203.24.127/zabbix/api_jsonrpc.php";
$GLOBALS["useradmin"] = "admin";
$GLOBALS["password"] = "zabbix";

$api = new ZabbixApi($GLOBALS["server_url"], $GLOBALS["useradmin"], $GLOBALS["password"]);


if($argc != 2 || in_array($argv[1], array('--help', '-help', '-h', '-?'))){
	echo "Script usage:\n";
	echo "php ".$argv[0]." --version or listtemplate or additem\n";
}
elseif($argc == 2 && in_array($argv[1], array('--version', '-version', '-v','-V'))){
    version_api();
}
elseif($argc == 2 && in_array($argv[1], array('ltemp', 'listtemplate'))){
	list_template();	
}

elseif($argc == 2 && in_array($argv[1], array('li','listitems'))){
        list_items();
}
elseif($argc == 2 && in_array($argv[1], array('ltrig','listtriggers'))){
        list_triggers();
}
elseif($argc == 2 && in_array($argv[1], array('atrig','addtriggers'))){
        add_triggers();
}


else{

	none();
}

function none(){

	print "None";
}

function list_items(){

try {

    // get all items
	$inputdata = array(
		'search' => array('hostid' => '10114')
	);

    $items = $GLOBALS["api"]->itemGet($inputdata);

    // print all triggerss component
    foreach($items as $item){

	//if($item->hostid == "10114")
	//if($trigger->description == "Cinder Service Volume To Create Verify Instance")
        echo "Host ID : ".$item->hostid.", Description : ".$item->description."\n";
	}
//print_r(array_keys($items));
//print_r(array_values($items));


} catch(Exception $e) {

    // Exception in ZabbixApi catched
    echo $e->getMessage();

 }
}

function list_triggers(){

try {

 $templateids = '10115';
// get all itemid
   $all_itemids = get_all_itemid($templateids);

   foreach($all_itemids as $all_itemid){
    // get all items
        $inputdata = array(
		'itemids' => $all_itemid,
		'templateids' => $templateids,
        );

    $triggers = $GLOBALS["api"]->triggerGet($inputdata);
    // print all triggerss component
    foreach($triggers as $trigger){

	$priority = array("1","2","4");
	$service_state = array("3","1","2");
	$add_name = array(" Status Information"," Status Warning"," Status High");

	for($i=0;$i<3;$i++){
	  if($i == 2){
           $input_update_triggers = array (
                'triggerid' => $trigger->triggerid,
                'itemids' => $all_itemid,
                'templateids' => $templateids,
                'description' => $trigger->description.$add_name[$i],
                'status' => '0',
                'priority' => $priority[$i],
                'expression' => '{'.get_template_name($templateids).":".get_item_name($all_itemid,$templateids).".last()}=".$service_state[$i],
            );
	    $GLOBALS["api"]->triggerUpdate($input_update_triggers);
	   }
	   else{
	    $input_create_triggers = array (
//		'itemids' => '24116',
		'itemids' => $all_itemid,
		'templateids' => $templateids,
		'description' => $trigger->description.$add_name[$i],
		'status' => '0',
		'priority' => $priority[$i],
		'expression' => '{'.get_template_name($templateids).":".get_item_name($all_itemid,$templateids).".last()}=".$service_state[$i],
	);
  	    $GLOBALS["api"]->triggerCreate($input_create_triggers);	
	   }  // if
	} // for looping

      } // foreach trigger
   } // $all_itemids

} catch(Exception $e) {

    // Exception in ZabbixApi catched
    echo $e->getMessage();

 }
}

function get_template_name($templateid){

        $inputdata = array(
                'templateids' => $templateid,
        );

    $templates = $GLOBALS["api"]->templateGet($inputdata);

    foreach($templates as $template){
        return $template->host;
      }
}

function get_all_itemid($templateid){

	$itemid_array = array();

        $inputdata = array(
                'templateids' => $templateid,
        );

    $items = $GLOBALS["api"]->itemGet($inputdata);

    // get all itemid array 
    foreach($items as $item){
        //echo "Item name : ".$item->name." Itemid : ".$item->itemid."\n";
	if($item->itemid != ""){
	   array_push($itemid_array,$item->itemid);
	}
      }

    return $itemid_array;

}


function get_item_name($itemids, $templateids){

        $inputdata = array(
                'itemids' => $itemids,
		'templateids' => $templateids,
        );

    $items = $GLOBALS["api"]->itemGet($inputdata);

    foreach($items as $item){
        return $item->name;
      }
}

function add_items(){

try {

    // get all items
    $items = $GLOBALS["api"]->itemsGet();

        #echo $items;
    // print all triggerss component
//    foreach($items as $item){
 //       if($item->templateid == "13624")
        //if($trigger->description == "Cinder Service Volume To Create Verify Instance")
  //      echo "Template ID : ".$trigger->templateid.",Description : ".$trigger->description." Expression : ".$trigger->expression."\n";
    //    }

print_r(array_keys($items));
print_r(array_values($items));

} catch(Exception $e) {

    // Exception in ZabbixApi catched
    echo $e->getMessage();

 }
}

function list_template(){

try {

    // get all items
    $items = $GLOBALS["api"]->templateGet();

        #echo $items;
    // print all triggerss component
    foreach($items as $item){
        echo "Template ID : ".$item->host.",Templateid : ".$item->templateid."\n";
        }
//print_r(array_keys($items));
//print_r(array_values($items));

} catch(Exception $e) {

    // Exception in ZabbixApi catched
    echo $e->getMessage();

 }
}

?> 


<?php

function version_api(){

try {


    $res = $GLOBALS["api"] ->apiinfoVersion();
    var_dump($res);
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

}

?>
