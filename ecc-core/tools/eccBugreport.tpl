<html>

<head>
<title>ECC Bugreport</title>
</head>

<body>

<a name="bugreport_top">
<div align="center">
	<table width="779" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<table width="29%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td bgcolor="#008080">&nbsp;</td>
				</tr>
				<tr>
					<td height="26">
					<p align="left"><font style="font-size: 16pt">ECC BUGREPORT</font></p>
					</td>
				</tr>
				<tr>
					<td>
					<p align="left"><b>Bugreport / Diagnostics v%ecc_bugreport_ver%</b></p>
					</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="19">&nbsp;</td>
		</tr>
		<tr>
			<td bgcolor="#E2E2D2" class="border" height="29">
			<table width="99%" border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td>
					<p align="left"><font size="2">&nbsp;This report is created 
					on: <b>%report_day%-%report_month%-%report_year% / %report_hour%:%report_minute%</b></font><font size="2" color="#808080"> 
					(dd-mm-yyyy / hh:mm)</font></p>
					</td>
					<td>
					<p align="right"><font size="2">&nbsp;ISO timestamp: <b>%timestamp%
					</b></font></p>
					</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td bgcolor="#F3F3F3" class="border">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="content"><b><font size="2"><br>
					CONTENTS:<br>
&nbsp;</font></b><table border="0" width="100%" id="table3">
						<tr>
							<td align="left" width="180"><b><font size="1">
							<a href="#emucontrolcenter_info">1) EMUCONTROLCENTER 
							INFO</a></font></b></td>
							<td><b><font size="1"><a href="#ecc_startup_ini">4) 
							PHP STARTUP INI</a></font></b></td>
							<td width="211"><b><font size="1">
							<a href="#ecc_tools_list">7) ECC TOOLS LIST</a></font></b></td>
							<td width="243"><b><font size="1">
							<a href="#ecc_md5_hash_check">10) MD5 HASH CHECK</a></font></b></td>
						</tr>
						<tr>
							<td align="left" width="180"><b><font size="1">
							<a href="#local_environment">2) LOCAL ENVIRONMENT</a></font></b></td>
							<td><b><font size="1"><a href="#ecc_general_ini">5) 
							ECC GENERAL INI</a></font></b></td>
							<td width="211"><b><font size="1">
							<a href="#ecc_core_components">8) ECC CORE COMPONENTS</a></font></b></td>
							<td width="243">&nbsp;</td>
						</tr>
						<tr>
							<td align="left" width="180"><b><font size="1">
							<a href="#php_error_log">3) PHP ERROR LOG</a></font></b></td>
							<td><b><font size="1"><a href="#ecc_history_ini">6) 
							ECC HISTORY INI</a></font></b></td>
							<td width="211"><b><font size="1">
							<a href="#ecc_php_extension_components">9) ECC PHP EXTENSION 
							COMPONENTS</a></font></b></td>
							<td width="243">&nbsp;</td>
						</tr>
					</table>
					<hr>
					<p><a name="emucontrolcenter_info"><font size="2"><b>1) EMUCONTROLCENTER 
					INFO:</b></font></p>
					<table border="0" width="100%" id="table2">
						<tr>
							<td><font size="2">Version:</font></td>
							<td><font size="2"><b>v%ecc_main_version%</b>&nbsp; 
							build: <b>%ecc_main_build%</b> (%ecc_main_build_date%)&nbsp; 
							upd: <b>%ecc_last_update%</b></font></td>
						</tr>
						<tr>
							<td><font size="2">Startup:</font></td>
							<td><font size="2">ECC Startup <b>v%ecc_startup_ver%</b></font></td>
						</tr>
						<tr>
							<td><font size="2">Core:</font></td>
							<td><font size="2">ECC is using <b>PHP v%ecc_php_ver%</b> 
							and <b>GTK v%ecc_gtk_ver%</b></font></td>
						</tr>
					</table>
					<hr>
					<p></a><a name="local_environment"><font size="2"><b>2) LOCAL 
					ENVIRONMENT:</b></font></p>
					<table border="0" width="100%" id="table1">
						<tr>
							<td><font size="2">Processor(s):</font></td>
							<td><font size="2"><b>%proc_count% processor(s)</b> (%proc_arch%), 
							type: </font><b><a name="local_environment0"><font size="2">
							%proc_id%</font></a></b></td>
						</tr>
						<tr>
							<td><font size="2">OS:</font></td>
							<td><font size="2">ECC is running on <b>%os_version%</b> 
							(%os_type%)</font></td>
						</tr>
					</table>
					</a>
					<p><font size="2" color="#808080">[?] If you don&#39;t see any &#39;local 
					environment&#39; informations, make sure you have run ECC at least 
					once!</font></p>
					<a name="local_environment"><hr></a>
					<p align="justify"><a name="php_error_log"><font size="2">
					<b>3) PHP ERROR LOG:</b><br>
					<br>
					%ecc_php_errorlog%</font></a></p>
					<p align="right"><font size="2" color="#C0C0C0">
					<a href="#bugreport_top">[back to top]</a></font></p>
					<hr align="justify">
					<p align="justify"><a name="ecc_startup_ini"><font size="2">
					<b>4) PHP STARTUP INI:</b><br>
					<br>
					%ecc_php_ini%</font></a></p>
					<p align="right"><font size="2" color="#C0C0C0">
					<a href="#bugreport_top">[back to top]</a></font></p>
					<hr>
					<p><b><font size="2">5) ECC GENERAL INI:</font></b><a name="ecc_general_ini"><font size="2"><br>
					<br>
					%ecc_ini_general%</font></a></p>
					<p align="right"><font size="2" color="#C0C0C0">
					<a href="#bugreport_top">[back to top]</a></font></p>
					<hr>
					<p><b><font size="2">6) ECC HISTORY INI:</font></b><a name="ecc_history_ini"><font size="2"><br>
					<br>
					%ecc_ini_history%</font></a></p>
					<p align="right"><font size="2" color="#C0C0C0">
					<a href="#bugreport_top">[back to top]</a></font></p>
					<hr>
					<p><font size="2"><a name="ecc_tools_list"><b>7) ECC TOOLS LIST:<br>
					</p>
					</b>
					<pre>filename:			size in bytes:	version:


%ecc_tools_list%</pre>
					</a>
					<p align="right"><font size="2" color="#C0C0C0">
					<a href="#bugreport_top">[back to top]</a></font></p>
					<b><hr>
					<p><a name="ecc_core_components">8) ECC CORE COMPONENTS:<br>
					</p>
					</a></b><a name="ecc_core_components">
					<pre>filename:			size in bytes:	version:


%ecc_core_list%</pre>
					</a>
					<p align="right"><font size="2" color="#C0C0C0">
					<a href="#bugreport_top">[back to top]</a></font></p>
					<b><hr><br>
					<a name="ecc_php_extension_components">9) ECC PHP EXTENSION 
					COMPONENTS:<br>
					</a></b><a name="ecc_php_extension_components">
					<pre>filename:			size in bytes:	version:
<br>

%ecc_core_ext_list%</a></pre>
					<p align="right"><font size="2" color="#C0C0C0">
					<a href="#bugreport_top">[back to top]</a></font></p>
					<hr>
					<p><a name="ecc_md5_hash_check"><b>10) MD5 HASH CHECK:</b></a></p>
					<table border="0" width="100%" id="table4">
						<tr>
							<td><font size="2">Latest official release:</font></td>
							<td width="566"><font size="2">
							<a name="emucontrolcenter_info2"><b>v%ecc_md5comp_version%</b>&nbsp; 
							build: <b>%ecc_md5comp_build%</b> (%ecc_md5comp_build_date%)&nbsp; 
							upd: <b>%ecc_md5comp_update%</b></a></font></td>
						</tr>
						</a>
						<tr>
							<td><font size="2">Compared with
							<a name="bugreport_top">your current version:</a></font><a name="bugreport_top"></a></td>
							<td width="566"><a name="bugreport_top1">
							<font size="2"></a><a name="emucontrolcenter_info1">
							<b>v%ecc_main_version%</b>&nbsp; build: <b>%ecc_main_build%</b> 
							(%ecc_main_build_date%)&nbsp; upd: <b>%ecc_last_update%</b></a></font></a></td>
						</tr>
					</table>
					<p><font size="2" color="#808080"><a name="bugreport_top0">[?]
					</a></font></a><font color="#808080">
					<a name="bugreport_top0">This data can change quickly because 
					when you install updates</a>, some MD5 hashes are different, 
					this is no problem...</font><a name="bugreport_top"><br>
					</a><a name="ecc_history_ini1"><br>
					%md5_hash_results%</a></p>
					<p align="right"><font size="2" color="#C0C0C0">
					<a href="#bugreport_top">[back to top]</a></font></p>
					</a>
					<p align="right">&nbsp;</a></font><a name="bugreport_top"></p>
					</a></td>
				</tr>
			</table>
			</a></td>
		</tr>
		<tr>
			<td bgcolor="#E2E2D2">
			<p align="center"><font size="2">IP bugsender: %ip_bugsender% / user 
			CID: %ecc_user_cid%</font></p>
			</td>
		</tr>
	</table>
</div>
<div style="font-size: 0.8em; text-align: center; margin-top: 1.0em; margin-bottom: 1.0em;">
	<font size="2" color="#C0C0C0">emuControlCenter Bugreport - (c) 2007 Phoenix 
	Interactive<br>
	(template &#39;lite&#39; v1.15-en)</font></div>
</a>

</body>

</html>
