<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

function formatPhone($text)
{
	return $text;
	if (strlen($text) == 10) {
		$text = substr_replace($text, '.', 4, 0);
		$text = substr_replace($text, '.', 8, 0);
	} else if (strlen($text) > 9) {
		$text = substr_replace($text, '.', 3, 0);
		$text = substr_replace($text, '.', 8, 0);
	}
	return $text;
}

function formatMoney($mobey)
{
	return ($mobey < 100 && $mobey > (int)$mobey) ? number_format($mobey, 2, ',', '.') : number_format(floor($mobey), 0, ',', '.');
}

function formatCoin($coin)
{
	return ($coin != (int)$coin) ? number_format($coin, 2, ',', '.') : number_format($coin, 0, ',', '.');
}
