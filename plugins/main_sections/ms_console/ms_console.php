<?php
/*
 * Created on 13 nov. 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 $exlu_group=" deviceid != '_SYSTEMGROUP_' 
									and deviceid != '_DOWNLOADGROUP_' ";
 require_once('require/function_table_html.php');
 require_once('require/function_graphic.php');
  require_once('require/function_console.php');
echo "<script language=javascript>
		function garde_valeur_console(form_name,did,hidden_name,did2,hidden_name2){
				document.getElementById(hidden_name).value=did;
				document.getElementById(hidden_name2).value=did2;
				document.getElementById(form_name).submit();
		}

</script>";






if( $_SESSION['OCS']['CONFIGURATION']['CONSOLE']=="YES") {
	
	//Value of FREQUENCY
	$sql_frequency="select ivalue from config where name='FREQUENCY'";
	$result_frequency = mysql_query( $sql_frequency, $_SESSION['OCS']["readServer"]);
	$item_frequency = mysql_fetch_object($result_frequency);
	
	if (isset($protectedPost['supp']) and $protectedPost['supp'] != ""){
		$sql_not_show="delete from config where name='".addslashes($protectedPost['supp'])."'";
		mysql_query( $sql_not_show, $_SESSION['OCS']["writeServer"] );
		
	}	
	
	 if ($protectedPost['DELETE_OPTION'] != "" and isset($protectedPost['DELETE_OPTION'])){
			$sql_not_show="insert into config (NAME,IVALUE) values ('OSC_REPORT_".$protectedPost['DELETE_OPTION']."',1)";
			mysql_query( $sql_not_show, $_SESSION['OCS']["writeServer"] );

	 }
	 if ($protectedPost['USE_OPTION'] != "" and isset($protectedPost['USE_OPTION'])){
			$sql_show="delete from config where name='OSC_REPORT_".$protectedPost['USE_OPTION']."'";
			
			mysql_query( $sql_show, $_SESSION['OCS']["writeServer"] );

	 }
	if (isset($protectedPost['Valid']) and $protectedPost['onglet'] == "CONFIG"){
		foreach ($protectedPost as $key=>$value){
			
			if ($value != "" and $key != "Valid" and $key != 'onglet' and $key !='pcparpage'){
					//Check correct value for LAST_DIFF
				if ($key == 'LAST_DIFF' and $value < $item_frequency->ivalue)
					echo "<script> alert('La valeur de LAST_DIFF doit �tre supp�rieure � celle de FREQUENCY')</script>";
				else{
					$sql="delete from config where NAME='GUI_REPORT_".$key."'";
					mysql_query( $sql, $_SESSION['OCS']["writeServer"] );
					$sql="insert into config (NAME,IVALUE) value ('GUI_REPORT_".$key."',".$value.")";
					mysql_query( $sql, $_SESSION['OCS']["writeServer"] );
				}
				
			}
			
		}
	}elseif ($protectedPost['onglet'] == "MSG" and isset($protectedPost['Val'])){
		$sql_msg="select name from config where name like 'GUI_REPORT_MSG%'";
		$result_msg = mysql_query( $sql_msg, $_SESSION['OCS']["readServer"]);
		while($item_msg = mysql_fetch_object($result_msg)){
			$list_name_msg[]=substr($item_msg ->name,14);		
		}
		if (isset($list_name_msg)){
			$i=1;
			foreach ($list_name_msg as $k=>$v){
				if ($v == $i)
				$i++;			
			}
		}else
		$i=1;
		if (trim($protectedPost['GROUP']) != "" and is_numeric($protectedPost['GROUP']) and trim($protectedPost['MSG'])!=""){
			$sql="insert into config (NAME,IVALUE,TVALUE) value ('GUI_REPORT_MSG".$i."',".$protectedPost['GROUP'].",'".addslashes($protectedPost['MSG'])."')";
			mysql_query( $sql, $_SESSION['OCS']["writeServer"] );
		}else
			msg_error($l->g(239));
		
	}
}
  
 //witch fields not show
 $sql_search_option="select substr(NAME,12) NAME from config where name like 'OSC_REPORT_%'";
 $result_search_option = mysql_query( $sql_search_option, $_SESSION['OCS']["readServer"]);
while($item_search_option = mysql_fetch_object($result_search_option))
	$list_no_show[$item_search_option ->NAME]=$item_search_option ->NAME;	

//witch option fields
 $sql_search_option="select substr(NAME,12) NAME,IVALUE from config where name like 'GUI_REPORT_%'";
 $result_search_option = mysql_query( $sql_search_option, $_SESSION['OCS']["readServer"]);
while($item_search_option = mysql_fetch_object($result_search_option))
	$list_option[$item_search_option ->NAME]=$item_search_option ->IVALUE;	

//all fields repart on categories
$repart=array("WORKGROUP"=>"ELSE",
			  "TAG"=>"ELSE",
			  "IPSUBNET"=>"ELSE",
			  "NB_NOTIFIED"=>"ELSE",
			  "NB_ERR"=>"ELSE",
			  "OSNAME"=>"SOFT",
 			  "USERAGENT"=>"SOFT",
			  "PROCESSORT"=>"HARD",
		      "RESOLUTION"=>"HARD",
			  "NB_LIMIT_FREQ_H"=>"HARD",
			  "NB_LIMIT_FREQ_M"=>"HARD",
			  "NB_LIMIT_FREQ_B"=>"HARD",
			  "NB_LIMIT_MEM_H"=>"HARD",
			  "NB_LIMIT_MEM_M"=>"HARD",
			  "NB_LIMIT_MEM_B"=>"HARD",
			  "NB_ALL_COMPUTOR"=>"ACTIVITY",
			  "NB_COMPUTOR"=>"ACTIVITY",
			  "NB_CONTACT"=>"ACTIVITY",
			  "NB_INV"=>"ACTIVITY",
			  "NB_4_MOMENT"=>"ACTIVITY",
			  "NB_HARD_DISK_H"=>"HARD",
			  "NB_HARD_DISK_M"=>"HARD",
			  "NB_HARD_DISK_B"=>"HARD"
			  ,"NB_IPDISCOVER"=>"ACTIVITY"
			  ,"NB_LAST_INV"=>"ACTIVITY"
			  );
//all lbl fields
$lbl_field=array("WORKGROUP"=>$l->g(778),
			  "TAG"=>$l->g(779),
			  "IPSUBNET"=>$l->g(780),
			  "NB_NOTIFIED"=>$l->g(781),
			  "NB_ERR"=>$l->g(782),
			  "OSNAME"=>$l->g(783),
 			  "USERAGENT"=>$l->g(784),
			  "PROCESSORT"=>$l->g(785),
		      "RESOLUTION"=>$l->g(786),
			  "NB_LIMIT_FREQ_H"=>$l->g(787),
			  "NB_LIMIT_FREQ_M"=>$l->g(788),
			  "NB_LIMIT_FREQ_B"=>$l->g(789),
			  "NB_LIMIT_MEM_H"=>$l->g(790),
			  "NB_LIMIT_MEM_M"=>$l->g(791),
			  "NB_LIMIT_MEM_B"=>$l->g(792),
			  "NB_ALL_COMPUTOR"=>$l->g(793),
			  "NB_COMPUTOR"=>$l->g(794),
			  "NB_CONTACT"=>$l->g(795),
			  "NB_INV"=>$l->g(796),
			  "NB_4_MOMENT"=>$l->g(797),
			  "NB_HARD_DISK_H"=>$l->g(813),
			  "NB_HARD_DISK_M"=>$l->g(814),
			  "NB_HARD_DISK_B"=>$l->g(815)
			  ,"NB_IPDISCOVER"=>$l->g(913)
			  ,"NB_LAST_INV"=>$l->g(914)
			  );

//d�finition des onglets
$data_on['ACTIVITY']=$l->g(798);
$data_on['SOFT']=strtoupper($l->g(20));
$data_on['HARD']=$l->g(799);
$data_on['ELSE']=$l->g(800);
		  
foreach ($repart as $key=>$value){
	$list_champs[$key]=$key;
}
if (isset($list_no_show)){
	$list_champs=array_diff($list_champs,$list_no_show);
	//create field list for configuration (add a field)
	foreach ($list_no_show as $key=>$value){
		$list_no_show_cat[$key]=$lbl_field[$value]." (".$data_on[$repart[$key]].")";	
	}
}
//create field list for configuration (delete a field)
foreach ($list_champs as $key=>$value){
	$show_on[$repart[$key]]=$repart[$key];
	$list_champs_cat[$key]=$lbl_field[$value]." (".$data_on[$repart[$key]].")";	
}

//show only onglet not empty
foreach ($data_on as $key=>$value){
	if (!isset($show_on[$key]))
	unset($data_on[$key]);
	elseif (!isset($default))
	$default = $key;
}

//onglet que pour Admins
if( $_SESSION['OCS']['CONFIGURATION']['CONSOLE']=="YES") {
$data_on['CONFIG']=strtoupper($l->g(107));
$data_on['MSG']=strtoupper($l->g(915));

if (!isset($default))
	$default = 'CONFIG';
}

//if no onglet selected
if (!isset($protectedPost['onglet']) and isset($default))
 $protectedPost['onglet']=$default;
elseif(!isset($default))
echo "<table align=center><tr><td align=center><img src='image/fond.png'></td></tr></table>";



if (isset($default)){
	$form_name = "console";
	echo "<form name='".$form_name."' id='".$form_name."' method='POST' action=''>";
	 onglet($data_on,$form_name,"onglet",8);
	 	echo "<table ALIGN = 'Center' class='mlt_bordure'><tr><td align =center>";
	if( $_SESSION['OCS']['RESTRICTION']['GUI'] == "YES") {
		$sql_hardware_id="select hardware_id id from accountinfo a  where ".$_SESSION['OCS']["mesmachines"];
		$result_hardware_id = mysql_query( $sql_hardware_id, $_SESSION['OCS']["readServer"]);
		$list_hardware_id="";
		$nb_computer=0;
		while($item_hardware_id = mysql_fetch_object($result_hardware_id)){
			$nb_computer++;
			$list_hardware_id.=$item_hardware_id ->id.",";		
		}
		$list_hardware=substr($list_hardware_id,0,-1);
		$list_hardware_id = " and h.id in(".$list_hardware.")";
		$list_id = " and h.hardware_id in(".$list_hardware.")";
	}
	if (substr($list_hardware_id,4)!="")
		$list_on_hardware=" where ".substr($list_hardware_id,4);
	if (substr($list_id,4)!="")
		$list_on_else=" where ".substr($list_id,4);
	if ($protectedPost['onglet'] == "ACTIVITY"){
		
		//count number of all computers
		if (!isset($list_no_show['NB_ALL_COMPUTOR']) and !isset($list_no_show['NB_COMPUTOR'])){
			$sql_count_computer="select count(*) c from hardware h 
								 where ".$exlu_group;
			$result_count_computer = mysql_query( $sql_count_computer, $_SESSION['OCS']["readServer"]);
			$item_count_computer = mysql_fetch_object($result_count_computer);
			if (!isset($list_no_show['NB_ALL_COMPUTOR'])){
				$data['NB_ALL_COMPUTOR']['data']=$item_count_computer-> c;
				$data['NB_ALL_COMPUTOR']['lbl']=$lbl_field['NB_ALL_COMPUTOR'];
			}
			if (!isset($list_no_show['NB_COMPUTOR'])){
				if (isset($nb_computer))
		 		$data['NB_COMPUTOR']['data']= $nb_computer;
		 		else
		 		$data['NB_COMPUTOR']['data']=$item_count_computer-> c;
		 		$data['NB_COMPUTOR']['lbl']=$lbl_field['NB_COMPUTOR'];
			}
		}
	 	query_with_condition("where lastcome > date_format(sysdate(),'%Y-%m-%d 00:00:00') ",
							 $lbl_field['NB_CONTACT'],'NB_CONTACT');
		query_with_condition("where lastdate > date_format(sysdate(),'%Y-%m-%d 00:00:00') ",
							 $lbl_field['NB_INV'],'NB_INV');
		//query_on_table_count("NAME",$lbl_field['NB_4_MOMENT']." ".$list_option['NOT_VIEW']." ".$l->g(496),"hardware"," and unix_timestamp(lastdate) < unix_timestamp(sysdate())-(".$list_option['NOT_VIEW']."*86400)");
		//select floor((unix_timestamp(lastcome) - unix_timestamp(lastdate) )/86400),lastcome,lastdate from hardware 
//		query_with_condition("where unix_timestamp(lastdate) - unix_timestamp(lastcome) < ".$list_option['AGIN_MACH']." ",
//							 $lbl_field['NB_4_MOMENT']." ".$list_option['AGIN_MACH']." ".$l->g(496),'NB_4_MOMENT');
		query_with_condition("where unix_timestamp(lastdate) < unix_timestamp(sysdate())-(".$list_option['AGIN_MACH']."*86400) ",
							 $lbl_field['NB_4_MOMENT']." ".$list_option['AGIN_MACH']." ".$l->g(496),'NB_4_MOMENT');
		
		//count number of all computers
		if (!isset($list_no_show['NB_IPDISCOVER'])){
			if( $_SESSION['OCS']['RESTRICTION']['GUI'] == "YES"){
				if (isset($_SESSION['OCS']['S3G_IP'])){
					foreach ($_SESSION['OCS']['S3G_IP'] as $S3G=>$IP){
							$list_dept[substr($S3G,3,2)]=substr($S3G,3,2);
					}
				}
				if ($list_dept != "")
				$dpt = implode("','",$list_dept );
				if (trim($dpt) != ""){
					$dpt = "IN ('".$dpt."') ";		
					$totNinvReqLoc = "
							SELECT COUNT(DISTINCT mac) AS total 
							FROM netmap n 
							LEFT OUTER JOIN networks        ns ON ns.macaddr = mac 
							LEFT OUTER JOIN network_devices nd ON nd.macaddr = mac
							INNER      JOIN subnet          s  ON s.netid    = n.netid 
							WHERE s.id $dpt
							AND ns.macaddr IS NULL 
							AND nd.macaddr IS NULL";
				}
				
			}else{

				$totNinvReqLoc="select count(*) AS total 
							from netmap left join networks on netmap.MAC=networks.MACADDR 
							where networks.MACADDR is null";	
	
			}
			if (isset($totNinvReqLoc)){
			$totNinvResLoc = mysql_query( $totNinvReqLoc, $_SESSION['OCS']["readServer"]) or die(mysql_error($_SESSION['OCS']["readServer"]));
			$totNinvValLoc = mysql_fetch_array( $totNinvResLoc );
			$data['NB_IPDISCOVER']['data']="<a href='index.php?".PAG_INDEX."=".$pages_refs['ms_ipdiscover']."' target='_blank'>".$totNinvValLoc['total']."</a>";
		 	$data['NB_IPDISCOVER']['lbl']=$lbl_field['NB_IPDISCOVER'];
			}
		}
		if (isset($list_option['LAST_DIFF'])){
				query_with_condition("where floor((unix_timestamp(lastcome) - unix_timestamp(lastdate) )/86400) >= ".$list_option['LAST_DIFF'],
							 $lbl_field['NB_LAST_INV']." ".$list_option['LAST_DIFF']." ".$l->g(496),'NB_LAST_INV');
			}	
	}
	
	if ($protectedPost['onglet'] == "ELSE"){
		query_on_table_count("WORKGROUP",$lbl_field["WORKGROUP"]);
		query_on_table_count("TAG",$lbl_field["TAG"],"accountinfo");
		query_on_table_count("IPSUBNET",$lbl_field["IPSUBNET"],"networks");
		query_with_condition("  where h.name='DOWNLOAD' and h.tvalue='NOTIFIED'",$lbl_field['NB_NOTIFIED'],'NB_NOTIFIED',"devices");
		query_with_condition(" where  h.name='DOWNLOAD' and substring(h.tvalue,1,3)='ERR'",$lbl_field['NB_ERR'],'NB_ERR',"devices");
	}
	if ($protectedPost['onglet'] == "SOFT"){
		query_on_table_count("OSNAME",$lbl_field["OSNAME"]);
		query_on_table_count("USERAGENT",$lbl_field["USERAGENT"]);
		
	}
	
	if ($protectedPost['onglet'] == "HARD"){
		query_on_table_count("PROCESSORT",$lbl_field["PROCESSORT"]);
		query_on_table_count("RESOLUTION",$lbl_field["RESOLUTION"],"videos");
		query_with_condition("where processors>=".$list_option['PROC_MAX'],
								$lbl_field['NB_LIMIT_FREQ_H']." ".$list_option['PROC_MAX']." MHz",'NB_LIMIT_FREQ_H');
		query_with_condition("where processors<=".$list_option['PROC_MINI'],
								$lbl_field['NB_LIMIT_FREQ_M']." ".$list_option['PROC_MINI']." MHz",'NB_LIMIT_FREQ_M');
		query_with_condition("where processors>".$list_option['PROC_MINI']." and processors<".$list_option['PROC_MAX'],
								$lbl_field['NB_LIMIT_FREQ_B']." ".$list_option['PROC_MINI']." MHz ".$l->g(582)." ".$list_option['PROC_MAX']." MHz",'NB_LIMIT_FREQ_B');
		query_with_condition("where memory>=".$list_option['RAM_MAX'],
								$lbl_field['NB_LIMIT_MEM_H']." ".$list_option['RAM_MAX']." MB",'NB_LIMIT_MEM_H');
		query_with_condition("where memory<=".$list_option['RAM_MINI'],
							$lbl_field['NB_LIMIT_MEM_M']." ".$list_option['RAM_MINI']." MB",'NB_LIMIT_MEM_M');
		query_with_condition("where memory>".$list_option['RAM_MINI']." and memory <".$list_option['RAM_MAX'],
								$lbl_field['NB_LIMIT_MEM_B']." ".$list_option['RAM_MINI']." MB ".$l->g(582)." ".$list_option['RAM_MAX']." MB",'NB_LIMIT_MEM_B');
		
		query_with_condition("where h.type='Hard Drive' and h.free >=".$list_option['DD_MAX'],$lbl_field['NB_HARD_DISK_H']." ".$list_option['DD_MAX']." MB",'NB_HARD_DISK_H',"drives");
		query_with_condition("where h.type='Hard Drive' and h.free <=".$list_option['DD_MINI'],	$lbl_field['NB_HARD_DISK_M']." ".$list_option['DD_MINI']." MB",'NB_HARD_DISK_M',"drives");
		query_with_condition("where h.type='Hard Drive' and h.free>".$list_option['DD_MINI']." and h.free <".$list_option['DD_MAX'],$lbl_field['NB_HARD_DISK_B']." ".$list_option['DD_MINI']." MB ".$l->g(582)." ".$list_option['DD_MAX']." MB",'NB_HARD_DISK_B',"drives");
		
	}
	
	if ($protectedPost['onglet'] == "CONFIG"){
		require_once('require/function_config_generale.php');
		debut_tab(array('CELLSPACING'=>'5',
						'WIDTH'=>'70%',
						'BORDER'=>'0',
						'ALIGN'=>'Center',
						'CELLPADDING'=>'0',
						'BGCOLOR'=>'#C7D9F5',
						'BORDERCOLOR'=>'#9894B5'));
		if ($list_champs_cat != ""){
			$list_champs_cat['']="";
			ksort($list_champs_cat);
			ligne('DELETE_OPTION',$l->g(801),'select',array('SELECT_VALUE'=>$list_champs_cat,'RELOAD'=>$form_name));
		}
		if ($list_no_show_cat != ""){
			$list_no_show_cat['']="";
			ksort($list_no_show_cat);
	 		ligne('USE_OPTION',$l->g(802),'select',array('VALUE'=>$protectedPost['USE_OPTION'],'SELECT_VALUE'=>$list_no_show_cat,'RELOAD'=>$form_name));
		}
	 	ligne('AGIN_MACH',$l->g(803),'input',array('VALUE'=>$list_option['AGIN_MACH'],'END'=>$l->g(496),'SIZE'=>2,'MAXLENGHT'=>3,'JAVASCRIPT'=>$numeric));
	 	ligne('PROC_MINI',$l->g(804),'input',array('VALUE'=>$list_option['PROC_MINI'],'END'=>'MHz','SIZE'=>2,'MAXLENGHT'=>4,'JAVASCRIPT'=>$numeric));
	 	ligne('PROC_MAX',$l->g(805),'input',array('VALUE'=>$list_option['PROC_MAX'],'END'=>'MHz','SIZE'=>2,'MAXLENGHT'=>4,'JAVASCRIPT'=>$numeric));
	 	ligne('RAM_MINI',$l->g(806),'input',array('VALUE'=>$list_option['RAM_MINI'],'END'=>'MB','SIZE'=>2,'MAXLENGHT'=>4,'JAVASCRIPT'=>$numeric));
	 	ligne('RAM_MAX',$l->g(807),'input',array('VALUE'=>$list_option['RAM_MAX'],'END'=>'MB','SIZE'=>2,'MAXLENGHT'=>4,'JAVASCRIPT'=>$numeric));
	 	ligne('DD_MAX',$l->g(816),'input',array('VALUE'=>$list_option['DD_MAX'],'END'=>'MB','SIZE'=>4,'MAXLENGHT'=>8,'JAVASCRIPT'=>$numeric));
	 	ligne('DD_MINI',$l->g(817),'input',array('VALUE'=>$list_option['DD_MINI'],'END'=>'MB','SIZE'=>4,'MAXLENGHT'=>8,'JAVASCRIPT'=>$numeric));
	 	
	 	
	 	if ($item_frequency -> ivalue != -1){
	 	$text="<br><font color=orange><i>".$l->g(916)." ".$item_frequency -> ivalue." ".$l->g(496)." (".$l->g(917).")</i></font>";
	 	ligne('LAST_DIFF',$l->g(918),'input',array('VALUE'=>$list_option['LAST_DIFF'],'END'=>$l->g(496).$text,'SIZE'=>2,'MAXLENGHT'=>3,'JAVASCRIPT'=>$numeric));
	 	}
		echo "<tr><td align=center colspan=100><input type='submit' name='Valid' value='".$l->g(103)."' align=center></td></tr>";
		echo "</table>";
	}
	
	if ($protectedPost['onglet'] == "MSG"){
		require_once('require/function_config_generale.php');
		$entete[]=$l->g(583);
		$entete[]=$l->g(449);
		$entete[]=$l->g(392);
		//print_r($entete);
		$sql_msg="select h.name hname,c.name cname,c.ivalue,c.tvalue from config c,hardware h
				 where h.id=c.ivalue
					and c.name like 'GUI_REPORT_MSG%'";
		$result_msg = mysql_query( $sql_msg, $_SESSION['OCS']["readServer"]);
		$i=0;
		while($item_msg = mysql_fetch_object($result_msg)){
			$data_msg[$i]['ivalue']=$item_msg ->hname;
			$data_msg[$i]['tvalue']=stripslashes($item_msg ->tvalue);
			$data_msg[$i]['sup']="<img src='image/supp.png' OnClick='confirme(\"\",\"".$item_msg ->cname."\",\"".$form_name."\",\"supp\",\"".$l->g(919)."\")'>";
			$i++;
			}
		$width=60;
		$height=300;
		tab_entete_fixe($entete,$data_msg,'',$width,$height);
		if ($protectedPost['add_text']){
			debut_tab(array('CELLSPACING'=>'5',
						'WIDTH'=>'50%',
						'BORDER'=>'0',
						'ALIGN'=>'Center',
						'CELLPADDING'=>'0',
						'BGCOLOR'=>'#C7D9F5',
						'BORDERCOLOR'=>'#9894B5'));
			$sql_group_list="select ID,NAME from hardware where deviceid = '_SYSTEMGROUP_'";
			$result_group_list = mysql_query( $sql_group_list, $_SESSION['OCS']["readServer"]);
			$list_group['']='';
			while($item_group_list = mysql_fetch_object($result_group_list)){
				$list_group[$item_group_list ->ID]=$item_group_list ->NAME;
			}
			ligne('GROUP',$l->g(577),'select',array('SELECT_VALUE'=>$list_group));
			ligne('MSG',$l->g(449),'input',array('SIZE'=>50,'MAXLENGHT'=>250));
			echo "<tr><td align=center colspan=100><input type='submit' name='Val' value='".$l->g(13)."' align=center>&nbsp<input type='submit' name='ann' value='".$l->g(113)."' align=center></td></tr>";
			echo "</table>";
			
		}else
		echo "<br><input type='submit' name='add_text' value='".$l->g(617)."'>";
		echo "<input type='hidden' id='supp' name='supp' value=''>";	
		
		
		
	}
	
	
	echo "<table>";
	if (isset($data)){
		 foreach ($data as $key=>$value){
		 	echo "<tr height=30px bgcolor='#F2F2F2' BORDERCOLOR='#9894B5'>
				<td align='center' width='300px'><font size=2>".$value['lbl']."</font></td>
				<td align='center' width='150px'><B>".$value['data']."</B></td></tr>";
		 	
		 }
	}
	 echo "</table></table>";
	echo "<input type='hidden' id='detail' name='detail' value='".$protectedPost['detail']."'>";
	echo "<input type='hidden' id='tablename' name='tablename' value='".$protectedPost['tablename']."'>";
	echo "<input type='hidden' id='old_onglet' name='old_onglet' value='".$protectedPost['onglet']."'>";
	//echo "<input type='hidden' id='detail_more' name='detail_more' value=''>";
	if ($protectedPost['detail'] != "" 
		and isset($protectedPost['detail']) 
				and $protectedPost['onglet'] == $protectedPost['old_onglet'] 
							and $protectedPost['onglet'] != "MSG" and $protectedPost['onglet'] != "CONFIG"){

		if ($protectedPost['tablename'] != "ELSE"){	
		$limit=nb_page($form_name);
					echo "</table><table cellspacing='5' width='80%' BORDER='0' ALIGN = 'Center' CELLPADDING='0' BGCOLOR='#C7D9F5' BORDERCOLOR='#9894B5'><tr><td align =center>";
			
		if ($protectedPost['sens'] == "ASC")
			$sens="DESC";
		else
			$sens="ASC";
		$deb="<a OnClick='tri(\"".$col."\",\"".$sens."\",\"".$form_name."\")' >";
		$fin="</a>";
		$entete[]="<a OnClick='tri(\"NAME\",\"".$sens."\",\"".$form_name."\")' >NAME</a>";
		$entete[]="<a OnClick='tri(\"c\",\"".$sens."\",\"".$form_name."\")' >QTE</a>";
		
		query_on_table($protectedPost['detail'],$lbl_field[$protectedPost['detail']],$l->g(808),$protectedPost['tablename']);
		
		$width=60;
		$height=300;
		tab_entete_fixe($entete,$data_detail[$protectedPost['detail']],$titre[$protectedPost['detail']]." (<a href='index.php?".PAG_INDEX."=".$pages_refs['ms_soft_csv']."&no_header=1'>".$l->g(183)."</a>)",$width,$height);
		show_page($data['nb_'.$protectedPost['detail']]['count'],$form_name);
		}else{
			if ($protectedPost['detail'] == "NB_NOTIFIED" 
					or  $protectedPost['detail'] == "NB_ERR" 
					or  $protectedPost['detail'] == "NB_HARD_DISK_H"
					or  $protectedPost['detail'] == "NB_HARD_DISK_M"
					or $protectedPost['detail'] == "NB_HARD_DISK_B")
			$table_hard="h1.";
			else
			$table_hard="h.";
			$FIELDS["ID"]=$table_hard."ID";
			$FIELDS["WORKGROUP"]=$table_hard."WORKGROUP";
			$FIELDS["NAME"]=$table_hard."NAME";
			$FIELDS["IPADDR"]=$table_hard."IPADDR";
			$FIELDS["DESCRIPTION"]=$table_hard."DESCRIPTION";
//			$FIELDS["WINOWNER"]=$table_hard."WINOWNER";
//			$FIELDS["USERAGENT"]=$table_hard."USERAGENT";
			if ($protectedPost['detail'] == "NB_CONTACT" or $protectedPost['detail'] == "NB_INV" or $protectedPost['detail'] == "NB_CONTACT")
				$FIELDS["LASTDATE"]=$table_hard."LASTDATE";
			elseif ($protectedPost['detail'] == "NB_LIMIT_FREQ_H" or $protectedPost['detail'] == "NB_LIMIT_FREQ_M"	or $protectedPost['detail'] == "NB_LIMIT_FREQ_B")
				$FIELDS["PROCESSORS"]=$table_hard."PROCESSORS";
			elseif ($protectedPost['detail'] == "NB_LIMIT_MEM_H" or $protectedPost['detail'] == "NB_LIMIT_MEM_M" or $protectedPost['detail'] == "NB_LIMIT_MEM_B")
			$FIELDS["MEMORY"]=$table_hard."MEMORY";		
			elseif ($protectedPost['detail'] == "NB_HARD_DISK_H" or $protectedPost['detail'] == "NB_HARD_DISK_M" or $protectedPost['detail'] == "NB_HARD_DISK_B"){
				$FIELDS["LETTER"]="LETTER";
				$FIELDS["FREE"]="FREE";
				$FIELDS["TOTAL"]="TOTAL";
				$FIELDS["CAPACITY"]="round(100-(FREE*100/TOTAL)) AS CAPACITY";
			}
			elseif ($protectedPost['detail'] == "NB_LAST_INV"){
				$FIELDS["LASTDATE"]="LASTDATE";
				$FIELDS["LASTCOME"]="LASTCOME";				
			}
			
			$FIELDS_LINK["NAME"]=$table_hard."NAME";
			if ($protectedPost['tri2'] == "" or !isset($FIELDS[$protectedPost['tri2']]))
				$protectedPost['tri2']=1;
			$limit=nb_page($form_name);
			echo "</table><table cellspacing='5' width='80%' BORDER='0' ALIGN = 'Center' CELLPADDING='0' BGCOLOR='#C7D9F5' BORDERCOLOR='#9894B5'><tr><td align =center>";
			
			$trans = array("count(*) c" => implode(",", $FIELDS));	
			$sql= strtr($_SESSION['OCS']['SQL'][$protectedPost['detail']], $trans);
			$_SESSION['OCS']["forcedRequest"]=$sql;
			$sql.= " order by ".$protectedPost['tri2']." ".$protectedPost['sens'];
			$sql.=" limit ".$limit["BEGIN"].",".$limit["END"];
			$resCount = mysql_query($_SESSION['OCS']['SQL'][$protectedPost['detail']], $_SESSION['OCS']["readServer"]) 
				or die(mysql_error($_SESSION['OCS']["readServer"]));
			$valCount = mysql_fetch_array($resCount);
			$result = mysql_query( $sql, $_SESSION['OCS']["readServer"]);
			$i=0;
			while($colname = mysql_fetch_field($result)){
					if ($colname->name != "ID" ){
						$col=$colname->name;
						if ($protectedPost['sens'] == "ASC")
							$sens="DESC";
						else
							$sens="ASC";
						$deb="<a OnClick='tri(\"".$col."\",\"".$sens."\",\"".$form_name."\")' >";
						$fin="</a>";
						$entete[$i++]=$deb.$col.$fin;
					}
			}
			$i=0;
			unset($data);
			while($item = mysql_fetch_object($result)){
				$deb="<a href='index.php?".PAG_INDEX."=".$pages_refs['ms_computer']."&head=1&systemid=".$item ->ID."' target='_blank'>";
				$fin="</a>";
				$j=0;
				foreach ($FIELDS as $key=>$value){					
					if ($key != 'ID' and $key != 'CAPACITY'){
						if ($FIELDS_LINK[$key])
							$data[$i][$entete[$j]]=$deb.$item ->$key.$fin;
						else
							$data[$i][$entete[$j]]=$item ->$key;
				
					}	
					if ($key == 'CAPACITY')
					$data[$i][$entete[$j]]="<center>".percent_bar($item->$key)."</center>";	
					$j++;	
				}
				$i++;
			}
			$titre=$l->g(768)." ".$valCount['c']." (<a href='index.php?".PAG_INDEX."=".$pages_refs['ms_soft_csv']."&no_header=1'>".$l->g(183)."</a>)";
			$width=100;
			$height=300;
			//print_r($data);
			tab_entete_fixe($entete,$data,$titre,$width,$height);
			show_page($valCount['c'],$form_name);
			
			
		}
		echo "<input type='hidden' id='tri2' name='tri2' value='".$protectedPost['tri2']."'>";
		echo "<input type='hidden' id='sens' name='sens' value='".$protectedPost['sens']."'>";
	}
	echo "</table></form>";
}
//show messages
if ($_SESSION['OCS']['RESTRICTION']['GUI'] == "YES"){
	$info_msg=look_config_default_values('GUI_REPORT_MSG%','LIKE');
	$list_id_groups=implode(',',$info_msg['ivalue']);
	
	if ($list_id_groups != ""){
		$sql_my_msg="select distinct g_c.group_id groups 
					from accountinfo a ,groups_cache g_c
					where g_c.HARDWARE_ID=a.HARDWARE_ID
						and	g_c.GROUP_ID in (".$list_id_groups.")";
		if (isset($_SESSION['OCS']['mesmachines']) and $_SESSION['OCS']['mesmachines'] != "")
			$sql_my_msg.= " and ".$_SESSION['OCS']['mesmachines'];
		$result_my_msg = mysql_query( $sql_my_msg, $_SESSION['OCS']["readServer"]);
		//echo "<table align=center><tr><td align=center>";
		while($item_my_msg = mysql_fetch_object($result_my_msg)){
			foreach ($info_msg['ivalue'] as $key=>$value){
				if ($value == $item_my_msg ->groups){
					$msg_group[$key]=$info_msg['tvalue'][$key];
				}				
			}
		}	
		
		if (isset($msg_group) and $msg_group !=''){
			msg_warning(implode('<br>',$msg_group));
		}
	}
}
//end messages
?>


