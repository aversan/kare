<?php
/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

foreach ($arResult as &$result) {
	switch ($result['LINK']) {
		case '/personal/personal-data/':
			$result['DATA_TAB'] = 'personal-data';
			break;
		case '/personal/history-of-orders/':
			$result['DATA_TAB'] = 'history';
			break;
		case '/personal/change-password/':
			$result['DATA_TAB'] = 'change-pas';
			break;
	}
}
