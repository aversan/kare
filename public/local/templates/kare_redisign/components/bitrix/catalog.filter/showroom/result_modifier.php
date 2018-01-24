<?php
/**
 * @var $arResult
 * @var $arParams
*/
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) {
	die();
}

global $APPLICATION;

foreach ($arResult['arrProp'] as $key => $prop) {
	if ($prop['CODE'] == 'CITY') {
		$arResult['PROPERTY_KEY'] = 'PROPERTY_' . $key;
		break;
	}
}

$arParams['PAGE'] = empty($arParams['PAGE']) ? $APPLICATION->GetCurPage() : $arParams['PAGE'];

$inputName = $arResult['ITEMS'][$arResult['PROPERTY_KEY']]['INPUT_NAME'];
$inputValue = $arResult['ITEMS'][$arResult['PROPERTY_KEY']]['INPUT_VALUE'];
foreach ($arResult['ITEMS'][$arResult['PROPERTY_KEY']]['LIST'] as $key => $cityName) {
	if (empty($key)) {
		$value = $arParams['PAGE'];
	} else {
		$value = $arParams['PAGE'] . '?' . $inputName . '=' . $key . '&set_filter=Y';
	}

	$arResult['CUSTOM_LIST'][] = [
		'NAME' => $cityName,
		'VALUE' => $value,
		'SELECTED' => ($inputValue == $key),
	];
}
