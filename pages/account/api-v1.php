<?php
if(!isset($_SESSION['UserID'])){
	$_SESSION['reffer'] = $page;
	header('Location: /login');
}
?><?php include 'inc/html-top.inc.php'; ?>
<h2>API v1 (Short-Term Support)</h2>
<? if(isset($msg)){echo $msg;} ?>
<? if(isset($_SESSION['apimsg'])){echo "test"; unset($_SESSION['apimsg']);} ?>
<p>Über die API können sie mit <strong>GET</strong>-Anforderung, Informationen über alle Monitore erfahren. Folgende Parameter können an Uptime-Monitor übertragen werden:</p>
<br /><br />
<table id="tbl" class="table-striped">
<colgroup>
	<col class=t25>
	<col class=t75>
	<col>
</colgroup>
<thead>
	<tr>
		<th>Name</th>
		<th>Beschreibung</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td width="30%" valign="top"><strong><a id="apikey" href="#jump-apikey">apikey</a></strong></td>
		<td width="70%" valign="top">Über diesen Parameter übertragen sie Ihren API-Key.</td>
	</tr>
</tbody>
</table>
<br />
<br>
<h3>URL-Parameter</h3>
<p>Hier ist ein Beispiel für eine GET Anfrage auf unsere API:</p>
<code>http://uptime-monitor.net/api/v1/?apikey=&lt;YourKey&gt;</code>
<br /><br />
Ein Beispiel der Ausgabe, die mit Hilfe der API generiert werden kann:<br/><br/>
<pre>
[ {
"id":"11",
"name":"My Monitor Name",
"domain":"example.com",
"port":"80",
"first_start":"1366479142",
"status":"1",
"uptime":100 
} ]
</pre><br/>
<br/><hr/><br/><br/>
<table id="tbl" class="table-striped">
<colgroup>
	<col class=t25>
	<col class=t75>
	<col>
</colgroup>
<thead>
	<tr>
		<th>Name</th>
		<th>Beschreibung</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-id" href="#jump-id">id</a></strong></td>
		<td width="70%" valign="top">Gibt die einmalige ID vom Monitor zurück</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-name" href="#jump-name">name</a></strong></td>
		<td width="70%" valign="top">Gibt den Namen des Monitors zurück</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-domain" href="#jump-domain">domain</a></strong></td>
		<td width="70%" valign="top">Gibt den Domain Name zurück</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-port" href="#jump-port">port</a></strong></td>
		<td width="70%" valign="top">Gibt den Port zurück</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-first_start" href="#jump-first_start">first_start</a></strong></td>
		<td width="70%" valign="top">Gibt das Datum von der ersten Erfassung zurück</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-status" href="#jump-status">status</a></strong></td>
		<td width="70%" valign="top">Gibt den Status zurück (0=Unknow, 1=Online, 2=Offline)</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-uptime" href="#jump-uptime">uptime</a></strong></td>
		<td width="70%" valign="top">Gibt die gesammte Uptimezeit in Prozent zurück</td>
	</tr>
</tbody>
</table>
<br>
<p>Das ist der Output wenn ein Fehler passiert ist:</p>
<pre>{
"error":true,
"error_code":"100"
}</pre><br>
<table id="tbl" class="table-striped">
<colgroup>
	<col class=t25>
	<col class=t75>
	<col>
</colgroup>
<thead>
	<tr>
		<th>Fehlercode</th>
		<th>Beschreibung</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-100" href="#jump-100">100</a></strong></td>
		<td width="70%" valign="top">Der verwendete API-Key existiert nicht.</td>
	</tr>
	<tr>
		<td width="30%" valign="top"><strong><a id="jump-101" href="#jump-101">101</a></strong></td>
		<td width="70%" valign="top">Sie haben noch keinen Monitor im Konto angemeldet.</td>
	</tr>
</tbody>
</table>
<br>
Ein Beispiel wäre die <b>json</b> Decodierung über folgenden PHP-Code:
<br /><br />
<code>
&lt;?php<br />
	header('Content-Type: text/plain; charset=utf-8;');
	<br />
	$file = file_get_contents("http://uptime-monitor.net/api/v1/?apikey=&lt;YourKey&gt;");<br />
	print_r(json_decode($file));<br />
?&gt;
</code>
<?php include 'inc/html-bottom.inc.php'; ?>