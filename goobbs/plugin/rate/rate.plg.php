<?php

function rate_crit()
{
	return array('agree', 'funny', 'useful', 'friendly', 'informative');
}

function rate_aftertopic($topic)
{
	if(isGET('id') && isGET('exp') && (isValidEntry('topic', $_GET['id']) || isValidEntry('reply', $_GET['id'])))
	{
		rate_saveRate($_GET['id'], $_GET['exp']);
	}
	return rate_show($topic);
}

function rate_afterReply($reply)
{
	return rate_show($reply);
}

function rate_getRate($id)
{
	$rateFile = 'rate_' .$id;
	if(isValidEntry('plugin', $rateFile))
	{
		return readEntry('plugin', $rateFile);
	}
	return array_fill_keys (rate_crit(), 0);
}

function rate_saveRate($id, $exp)
{
	$rateFile = 'rate_' .$id;
	if(isset($_SESSION[$rateFile]))
		return;
	$rate = rate_getRate($id);
	if(isset($rate[$exp]))
	{
		$rate[$exp]++;
	}
	saveEntry('plugin', $rateFile, $rate);
	$_SESSION[$rateFile] = $rateFile;
}

function rate_show($id)
{
	$rate = rate_getRate($id);
	$out = '<div class="exp" style="font-size: 9px; margin: 9px 0">';
	if(isset($_SESSION['rate_' .$id]))
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
			$out .= '<a href="view.php/topic/' .$_GET['topic']. '/id/' .$id. '/exp/' .$exp. '"><img alt="" src="plugin/rate/img/' .$exp. '.png"></a> x '. $score. ' / ';
		}
	}
	$out .= '</div>';
	return $out;
}

?>