<?php

function online_footer()
{
	$online = readEntry('plugin', 'online');
	foreach($online as $ip => $time)
	{
		if(time() - $time > 300)
			unset($online[$ip]);
	}
	$online[$_SERVER['REMOTE_ADDR']] = time();
	saveEntry('plugin', 'online', $online);
	return '<span class="label label-success">online: ' .count($online). '</span>';
}

?>
