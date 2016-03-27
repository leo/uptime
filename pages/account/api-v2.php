<?php
if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}
?>
<?php include 'inc/html-top.inc.php'; ?>
<h2>API v2 (Long-Term Support)</h2>
<p class="error">Die API ist noch nicht verf&uuml;gbar, da sie noch in Entwicklung ist.</p>
<br>
<br>
<h3>Account</h3>
<p>Diese Parameter werden dazu verwendet, um sich einen API-Schlüssel zu holen.</p>
<table id="tbl" class="table-striped">
<colgroup>
	<col class=t25>
	<col class=t75>
	<col>
</colgroup>
<thead>
	<tr>
		<th>Ressource</th>
		<th>Beschreibung</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET authenticate</a></strong></td>
		<td width="70%" valign="top">Hier wird ein neuer ApiKey generiert</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET account</a></strong></td>
		<td width="70%" valign="top">Erhalte informationen über den Account</td>
	</tr>
</tbody>
</table>
<br>
<br>
<hr>
<br>
<br>
<h3>Monitor</h3>
<p>Diese Parameter werden dazu verwendet, um informationen &uuml;ber die Monitore zu holen.</p>
<table id="tbl" class="table-striped">
<colgroup>
	<col class=t25>
	<col class=t75>
	<col>
</colgroup>
<thead>
	<tr>
		<th>Ressource</th>
		<th>Beschreibung</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET monitor</a></strong></td>
		<td width="70%" valign="top">Zeigt alle Monitore an und gibt deren ID aus.</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET monitor/show</a></strong></td>
		<td width="70%" valign="top">Zeigt alle Informationen &uuml;ber einen bestimmten Monitor.</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET monitor/create</a></strong></td>
		<td width="70%" valign="top">Erstellt einen neuen Monitor.</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET monitor/destroy</a></strong></td>
		<td width="70%" valign="top">L&ouml;scht einen existierenden Monitor.</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET monitor/update</a></strong></td>
		<td width="70%" valign="top">Bearbeitet einen existierenden Monitor.</td>
	</tr>
</tbody>
</table>
<br>
<br>
<hr>
<br>
<p><b>Beispiel:</b> http://uptime-monitor.net/api/v2/monitor/show&apikey=&lt;key&gt;&monitorid=&lt;id&gt;</p>
<br><!--
<hr>
<br>
<br>
<h3>GET authenticate</h3>
<p>Diese Ressourcen k&ouml;nnen sie angeben, um einen API-Key zu erhalten.</p>
<table id="tbl" class="table-striped">
<colgroup>
	<col class=t25>
	<col class=t75>
	<col>
</colgroup>
<thead>
	<tr>
		<th>Ressource</th>
		<th>Beschreibung</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET apikey</a></strong></td>
		<td width="70%" valign="top">&Uuml;bertr&auml;gt den API-Key, ein Benutzername oder Passwort ist damit nicht n&ouml;tig.</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET username</a></strong></td>
		<td width="70%" valign="top">&Uuml;bertr&auml;gt den Benutzername.</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET password</a></strong></td>
		<td width="70%" valign="top">&Uuml;bertr&auml;gt das Passwort.</td>
	</tr>
</tbody>
</table>
<br>
<br>
<hr>
<br>
<br>
<h3>GET monitor</h3>
<p>Diese Parameter werden dazu verwendet, um informationen &uuml;ber die Monitore zu holen.</p>
<table id="tbl" class="table-striped">
<colgroup>
	<col class=t25>
	<col class=t75>
	<col>
</colgroup>
<thead>
	<tr>
		<th>Ressource</th>
		<th>Beschreibung</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">GET apikey</a></strong></td>
		<td width="70%" valign="top">&Uuml;bertr&auml;gt den API-Key.</td>
	</tr>
</tbody>
</table>-->
<?php include 'inc/html-bottom.inc.php'; ?>