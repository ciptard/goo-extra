<?php

if (!isValidEntry('plugin', 'rate'))
	saveEntry('plugin', 'rate', array());
$rateEntry = readEntry('plugin', 'rate');

$type = 'post';

function rate_crit()
{
	return array('agree', 'funny', 'useful', 'friendly', 'informative');
}

function rate_afterPost($id)
{
	global $type;
	if(isGET('id') && isGET('exp') && (isValidEntry($type, $_GET['id']) || isValidEntry('reply', $_GET['id'])))
	{
		rate_saveRate($_GET['id'], $_GET['exp']);
	}
	return rate_show($id);
}

function rate_afterReply($id)
{
	return rate_show($id);
}

function lock($id)
{
	return 'rate_' .$id;
}

function rate_getRate($id)
{
	global $rateEntry;
	if (isset($rateEntry[$id]))
		return $rateEntry[$id];
	else
		return array_fill_keys (rate_crit(), 0);
}

function rate_saveRate($id, $exp)
{
	global $rateEntry;
	$lock = lock($id);
	if (isset($_SESSION[$lock]))
		return;
	
	$rate = rate_getRate($id);
	if(isset($rate[$exp]))
	{
		$rate[$exp]++;
	}
	$rateEntry[$id] = $rate;
	saveEntry('plugin', 'rate', $rateEntry);
	$_SESSION[$lock] = 1;
}

function rate_show($id)
{
	global $type;
	$rate = rate_getRate($id);
	$out = '<div class="exp" style="font-size: 9px; margin: 9px 0">';
	if(isset($_SESSION[lock($id)]))
	{
		$out .= '<img alt="" src="plugin/rate/img/accept.png"> / ';
		foreach($rate as $exp => $score)
		{
			$out .= '<img alt="" src="plugin/rate/img/' .$exp. '.png"> x '. $score. ' / ';
		}
	}
	else
	{
		foreach($rate as $exp => $score)
		{
			$out .= '<a href="view.php/' .$type. '/' .$_GET[$type]. '/id/' .$id. '/exp/' .$exp. '"><img alt="" src="plugin/rate/img/' .$exp. '.png"></a> x '. $score. ' / ';
		}
	}
	$out .= '</div>';
	return $out;
}

?>