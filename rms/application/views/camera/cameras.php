<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="msapplication-tap-highlight" content="no" />
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="-1">
	<meta http-equiv="pragma" content="no-cache">
	<META http-equiv="refresh" content="60">

		<link rel="stylesheet" href="/public/jqm/themes/hmw.min.css" />
		<link rel="stylesheet" href="/public/jqm/themes/jquery.mobile.icons.min.css" />
		<link rel="stylesheet" href="/public/jqm/jquery.mobile-1.4.5.min.css" />
		<link rel="stylesheet" href="/public/jqm/jquery.mobile.structure-1.4.5.min.css" />
		<link rel="stylesheet" href="/public/droid2/vendor/wow/animate.css" />
		<link rel="stylesheet" href="/public/droid2/vendor/waves/waves.min.css" />

		<link rel="stylesheet" href="/public/droid2/css/nativedroid2.css" />
		<!--A remplacer par une bu en local-->
		<link rel="stylesheet" href="/public/fontAwesome/4.6.3/css/font-awesome.min.css" />
		<link rel="stylesheet" href="/public/cameras.css" />
		<script type="text/javascript" src="/public/jquery-1.11.3.min.js"></script>
	</head>
	<body>
		<font face="arial">
		<?
	$bgcolor = '#FFF';
	$ca_amount = "0";
	$ca_last = "-";
	$total_ca = 0;
	$buname = array();
	foreach ($all_bus as $bu) { $buname[$bu->id] = $bu->name; }

	foreach ($ca as $caline) {
		if($caline['id_bu'] == $bu_id) {
			$ca_amount = $caline['amount'];
			$ca_last = $caline['last'];
		}

		$conca_ca[] = "<tr><td>".$buname[$caline['id_bu']]."</td><td>". number_format($caline['amount']/1000, 0, ',', ' ')."€</td><td> ".$caline['last']."</td></tr>";
		$total_ca += $caline['amount'];
	}
?>
	<div style="width:99%; background-color: <?=$bgcolor?>; padding:6px; margin: 0 auto 5px; font: 17px 'Lucida Grande', Lucida, Verdana, sans-serif; font-weight: bold;">
    <table>
      <tr>
        <td></td>
        <td>
          <form action="#" method="POST">
            <select name="bus" class="ui-btn" onchange="this.form.submit()">
              <? foreach ($bus_list as $bu) { ?>
                <option value="<?= $bu->id ?>" <? if($bu_id == $bu->id) echo "selected"; ?>><?= $bu->name ?></option>
              <? } ?>
            </select>
          </form>
        </td>
        <td>
          <a href="/">[BACK]</a> | TO: <?= number_format($ca_amount/1000, 0, ',', ' ') ?>€
        </td>
        <td> | <small>Last ticket: <?= $ca_last ?></small></td>
        <td> | <small>Cash fund: <?= $cash_fund ?>€</small></td>
      </tr>
    </table>
  </div>

	<? if(empty($cameras)) { ?>
		No cam.
		<? } else {
	foreach ($cameras as $camera) { ?>
		<img class="camera" src="/cameras/getStream/<?=$camera['name']?>" alt="<?=$camera['name']?>" />
	<? } } ?>

<p><h4>TO BY BUS</h4></p>
<table border='1' cellspacing='0' cellpadding='10'><tr bgcolor='#ffc300'>
<td>BU</td>
<td>TO</td>
<td>Last ticket</td>
</tr>
<?
foreach ($conca_ca as $line) {
	echo $line;
	}
?>
<tr><td colspan="3">TOTAL: <?=number_format($total_ca/1000, 0, ',', ' ')?>€</td></tr>
</table>
<?
	$p = $planning;
	$i=false;
if($p) {
	?>
	<div class="row">
		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<h4>Who's on today @<?=$info_bu->name?>?</h4>
			</div>
		</div>
	</div>
<table border='1' cellspacing='0' cellpadding='10'><tr bgcolor='#ffc300'>
	<td>Type</td>
	<td>People</td>
	<td>Hours (real)</td>
	</tr>
	<?
	foreach($p AS $key => $val) {

		if($info_bu->name == $key) {

			foreach($val AS $key2) {
				$date_start_ts	= new DateTime($key2['real_starts_at']);
				$shift_start_ts = $date_start_ts->getTimestamp();
				$date_end_ts	= new DateTime($key2['real_ends_at']);
				$shift_end_ts 	= $date_end_ts->getTimestamp();
				$ts		 		= time();
				$bgshift 		= "#d0d8d0";
				if(($ts < $shift_start_ts) OR ($ts > $shift_end_ts)) $bgshift = "";
				?>
				<tr bgcolor="<?=$bgshift?>">
				<td><?=$key2['label_name']?></td>
				<td><?=$key2['firstname']?> <?=$key2['lastname']?></td>
				<td><?=date_format(date_create($key2['starts_at']), 'H:i')?> - <?=date_format(date_create($key2['ends_at']), 'H:i')?> (<?=date_format(date_create($key2['real_starts_at']), 'H:i')?> - <?=date_format(date_create($key2['real_ends_at']), 'H:i')?>)</td>
				</tr>
				<?
			}
		}
	}
	?>
	</table>
<? } ?>

</font>
</body>
</html>
