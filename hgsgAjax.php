<?php
//
// Functions for HGSG Members
//

require_once(dirname(__FILE__) . '/../../common.inc.php');

// Initialization stuff
pre_init();
init_session();

// Grab GET or POST variables and do prereq/auth checks
grab_request_vars();
check_prereqs();
check_authentication(false);

$title = gettext("Nagios XI - HG SG Members");

do_page_start(array("page_title" => $title), true);

if ($_GET["mode"] =="host"){
	?><br><br><br>
	<textarea rows="25" cols="30">
		<?php
			$options = hgsg_get_hostgroupmembers_option_list($_GET['hg']);
			echo $options;
		?>
	</textarea>
<?php
} else if ($_GET["mode"] == "service"){
	?><br><br><br>
	<textarea rows="25" cols="60">
		<?php
		        $options = hgsg_get_servicegroupmembers_option_list($_GET['sg']);
		        echo $options;
		?>
	</textarea>
<?php
}

FUNCTION hgsg_get_hostgroupmembers_option_list($hgname){
    $option_list = "\r";
    $args = array(
        "hostgroup_name" => $hgname,
    );
    $hgs = get_xml_hostgroup_member_objects($args);
    $count = 1;
    foreach($hgs as $data){
    foreach($data as $data2){
    foreach($data2 as $host){
        $host =(array)$host;
	$hlist[] = $host['host_name'];
    }
    }
    }
    natcasesort($hlist);
    foreach($hlist as $temp){
        $option_list .= $temp."\r";
    }
return $option_list;
}

FUNCTION hgsg_get_servicegroupmembers_option_list($sgname){
    $option_list = "\r";
    $args = array(
        "servicegroup_name" => $sgname,
    );
    $sgs = get_xml_servicegroup_member_objects($args);
    $count = 1;
    foreach($sgs as $data){
    foreach($data as $data2){
    foreach($data2 as $host){
    $host =(array)$host;
    if (strlen($host['host_name']) >= 21){
        $hostname = substr($host['host_name'],0,20) . " ";
    } else {
        $hostname = $host['host_name'] . str_repeat('&nbsp;', 20 - strlen($host['host_name']));
    }
        $service = $host['service_description'];
        $hslist[] = "$hostname - $service";
    }
    }
    }
    natcasesort($hslist);
    foreach($hslist as $temp){
        $option_list .= $temp."\r";
    }
    
    return $option_list;
}
?>
