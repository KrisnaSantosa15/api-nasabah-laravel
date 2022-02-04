<?php

function calculatePoints($description, $amount)
{
	if ($description == 'Bayar Listrik') {
		$points = 0;
		if ($amount >= 50000 && $amount <= 100000) {
			$amount -= 50000;
			$remainingAmount = $amount - ($amount % 2000);
			$point = $remainingAmount / 2000;
			$points += $point;
		} else if ($amount > 100000) {
			$amount -= 100000;
			$remainingAmount = $amount - ($amount % 2000);
			$point = ($remainingAmount / 2000) * 2;
			$points += $point;
		};
	} elseif ($description == 'Beli Pulsa') {
		$points = 0;
		if ($amount >= 10000 && $amount <= 30000) {
			$amount -= 10000;
			$remainingAmount = $amount - ($amount % 2000);
			$point = $remainingAmount / 1000;
			$points += $point;
		} else if ($amount > 30000) {
			$amount -= 30000;
			$remainingAmount = $amount - ($amount % 1000);
			$point = ($remainingAmount / 1000) * 2;
			$points += $point;
		};
	} else {
		return null;
	}
	return $points;
}
