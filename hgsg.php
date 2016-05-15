<?php
//
// Schedule a Downtime
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
?>

<style type='text/css'>
select {
    font-family: 'Courier New';
}
</style>
<?php
recurringdowntime_show_downtime();

//
// begin function show_downtime()
//
function recurringdowntime_show_downtime()
{
    global $request;
    global $lstr;
    do_page_start(array("page_title" => $lstr['RecurringDowntimePageTitle']), true);
    $showall = true;
    ?>
    <h1>View Hostgroup or Servicegroup Members</h1>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#tabs").tabs();
        });
    </script>

    <div id="tabs">
    <ul>
        <li><a href="#hostgroup-tab"><?php echo gettext("Hostgroups"); ?></a></li>
        <li><a href="#servicegroup-tab"><?php echo gettext("Servicegroups"); ?></a></li>
    </ul>

<!-- Hostgroup Tab Start -->
    <div id='hostgroup-tab'>
    <div class="infotable_title" style="float:left"><?php echo "Hostgroup Members"; ?></div>
    <?php if (!is_readonly_user(0)) { ?>
    <div style="clear: left; margin: 0 0 10px 0;">
        <?php echo get_nagios_session_protector(); ?>
        <table class="editDataSourceTable">
        <tr><form name="myformhg">
                <td>
	                <SCRIPT TYPE="text/javascript" SRC="js/poplist.js"></SCRIPT>
	                <SCRIPT TYPE="text/javascript" SRC="js/filterlist.js"></SCRIPT>
        	        <input  type="text"  id="filterHosts" style="display:inline-block;width:auto;min-width:250px;font-family:Courier New;" placeholder="Hostgroups Filter..." onKeyUp="myfilterhg.set(this.value)"></input>
                	<br><br>
	                <select name="hgs" id="hgs" style="display:inline-block;width:auto;min-width:250px;font-family:Courier New;" size=25 onchange="return pophg();">
        		        <?php $options = hgsg_get_hostgroups_option_list();echo $options;?></select>
        		<SCRIPT TYPE="text/javascript">
	                	<!--
	                	var myfilterhg = new filterlist(document.myformhg.hgs);
        		        //-->
	                </SCRIPT>
		</td>
                <td>
	                <div id="output">Members Will Be Listed Here</div>
                </td>
	</form></tr>
        </table>
    </div><?php } ?>
    </div>
<!-- Hostgroup Tab End -->

<!-- Servicegroup Tab Start -->
    <div id='servicegroup-tab'>
    <div class="infotable_title" style="float:left"><?php echo "Servicegroup Members"; ?></div>
    <?php if (!is_readonly_user(0)) { ?>
    <div style="clear: left; margin: 0 0 10px 0;">
        <table class="editDataSourceTable">
        <tr><form name="myformsg">
                <td>
                        <SCRIPT TYPE="text/javascript" SRC="js/filterlist.js"></SCRIPT>
                        <input  type="text"  id="filterservices" style="display:inline-block;width:auto;min-width:250px;font-family:Courier New;" placeholder="Servicegroups Filter..." onKeyUp="myfiltersg.set(this.value)"></input>
                        <br><br>
                        <select name="sgs" id="sgs" style="display:inline-block;width:auto;min-width:250px;font-family:Courier New;" size=25 onchange="return popsg();">
                                <?php $options = hgsg_get_servicegroups_option_list();echo $options;?></select>
                        <SCRIPT TYPE="text/javascript">
                                <!--
                                var myfiltersg = new filterlist(document.myformsg.sgs);
                                //-->
                        </SCRIPT>
                </td>
                <td>
                        <div id="outputsg">Members Will Be Listed Here</div>
                </td>
        </form></tr>
        </table>
    </div><?php } ?>
    </div>
<!-- Servicegroup Tab End -->

<?php { ?>
</div> <?php }
do_page_end(true);
}
//
// end function recurringdowntime_show_downtime()
