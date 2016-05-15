<?php
//
//

// include the helper file
require_once(dirname(__FILE__) . '/../componenthelper.inc.php');

// respect the name
$hgsg_component_name = "hgsg";

// run the initialization function
hgsg_component_init();

////////////////////////////////////////////////////////////////////////
// COMPONENT INIT FUNCTIONS
////////////////////////////////////////////////////////////////////////

function hgsg_component_init()
{
    global $hgsg_component_name;

    //boolean to check for latest version
    $versionok = hgsg_component_checkversion();

    //component description
    $desc = gettext("This component allows users to list members of HG and SG. ");

    if (!$versionok)
        $desc = "<b>" . gettext("Error: This component requires Nagios XI 2009R1.2B or later.") . "</b>";

    //all components require a few arguments to be initialized correctly.
    $args = array(

        // need a name
        COMPONENT_NAME => $hgsg_component_name,
        COMPONENT_VERSION => '1.3',
        COMPONENT_DATE => '3/25/2015',

        // informative information
        COMPONENT_AUTHOR => "IT Convergence",
        COMPONENT_DESCRIPTION => $desc,
        COMPONENT_TITLE => "ITC HG SG Members",
    );

    // Register this component with XI
    register_component($hgsg_component_name, $args);

    // Register the addmenu function
    if ($versionok) {
        register_callback(CALLBACK_MENUS_INITIALIZED, 'hgsg_component_addmenu');
    }
}


///////////////////////////////////////////////////////////////////////////////////////////
// MISC FUNCTIONS
///////////////////////////////////////////////////////////////////////////////////////////

function hgsg_component_checkversion()
{

    if (!function_exists('get_product_release'))
        return false;
    //requires greater than 2009R1.2
    if (get_product_release() < 114)
        return false;

    return true;
}

function hgsg_component_addmenu($arg = null)
{
    global $hgsg_component_name;
    //retrieve the URL for this component
    $urlbase = get_component_url_base($hgsg_component_name);
    //figure out where I'm going on the menu
    $mi = find_menu_item(MENU_HOME, "menu-home-hostgroupsummary", "id");
    if ($mi == null) //bail if I didn't find the above menu item
        return;

    $order = grab_array_var($mi, "order", ""); //extract this variable from the $mi array
    if ($order == "")
        return;

    $neworder = $order - 0.1; //determine my menu order

    //add this to the main home menu
    add_menu_item(MENU_HOME, array(
        "type" => "link",
        "title" => gettext("ITC HG SG Members"),
        "id" => "menu-home-hgsg",
        "order" => $neworder,
        "opts" => array(
            //this is the page the menu will actually point to.
            //all of my actual component workings will happen on this script
            "href" => $urlbase . "/hgsg.php",
        )
    ));

}

FUNCTION hgsg_get_hosts_option_list(){
    $option_list = '';
        #$option_list .='<option value="0" selected>Select Host..</option>';
        #Guido: This function call all the existing hosts this function return a simple xml object.
    $hosts = get_xml_host_objects();
        #Guido: the count variable is really important!! the first array only contains the total of records found.
        $count = 1;
    foreach($hosts as $data){
          # This is critical !!, the function get_xml_host_objects just return an xml simple object, we need to convert the xml object to an array.
          $data =(array)$data;
          #Guido: start extracting the host_names or values after the first loop.
          if ($count>1){
        $option_list .='<option value="'.$data['host_name'].'">'.$data['host_name'].'</option>';
          }
          $count = $count + 1;
    }
        return $option_list;
}

FUNCTION hgsg_get_servicegroups_option_list(){
    $option_list = '';
    $sgs = get_xml_servicegroup_objects();
        $count = 1;
    foreach($sgs as $data){
        $data =(array)$data;
        print_r($data);
	if ($count>1){
	  $sglist[] = $data['servicegroup_name'];
//          $option_list .='<option value="'.$data['servicegroup_name'].'">'.$data['servicegroup_name'].'</option>';
        }
        $count = $count + 1;
    }
    natcasesort($sglist);
    foreach($sglist as $temp){
	$option_list .='<option value="'.$temp.'">'.$temp.'</option>';
    }
    return $option_list;
}

FUNCTION hgsg_get_hostgroups_option_list(){
    $option_list = '';
    $hgs = get_xml_hostgroup_objects();
        $count = 1;
    foreach($hgs as $data){
        $data =(array)$data;
        print_r($data);
        if ($count>1){
          $hglist[] = $data['hostgroup_name'];
//          $option_list .='<option value="'.$data['hostgroup_name'].'">'.$data['hostgroup_name'].'</option>';
        }
        $count = $count + 1;
    }
    natcasesort($hglist);
    foreach($hglist as $temp){
        $option_list .='<option value="'.$temp.'">'.$temp.'</option>';
    }
    return $option_list;
}
