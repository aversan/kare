<?php
/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult['TOTAL_TAX_VALUE'] = 0;
foreach ($arResult['TAX_LIST'] as $tax) {
	$arResult['TOTAL_TAX_VALUE'] += $tax['VALUE_MONEY'];
}

$arResult['TOTAL_TAX_VALUE_FORMATED'] = CCurrencyLang::CurrencyFormat(
	round($arResult['TOTAL_TAX_VALUE']),
	$arResult['CURRENCY']
);

$cp = $this->__component;
if (is_object($cp)) {
	$cp->arResult['POST_FORM_ACTION_URI'] = POST_FORM_ACTION_URI;
	$cp->arResult['STATUS_ID'] = $arResult['STATUS_ID'];
	$cp->arResult['PAYED'] = $arResult['PAYED'];
	$cp->arResult['CANCELED'] = $arResult['CANCELED'];
	$cp->SetResultCacheKeys([
		'POST_FORM_ACTION_URI',
		'ID',
		'STATUS_ID',
		'PAYED',
		'CANCELED'
	]);
	$arResult['POST_FORM_ACTION_URI'] = $cp->arResult['POST_FORM_ACTION_URI'];
}

$orderProps = [];
foreach ($arResult['ORDER_PROPS'] as $key => &$value) {
	$orderProps[$value['CODE']] = $value;
}
unset($value);

$arResult['ORDER_PROPS'] = $orderProps;

$basketSum = 0;
foreach ($arResult['BASKET'] as $basket) {
	$basketSum += $basket['PRICE'] * $basket["QUANTITY"];
}
$arResult['FORMATTED_PRICE_DELIVERY'] = ($arResult['STATUS_ID'] == 'N') ? 'Сумма доставки рассчитывается менеджером' : CurrencyFormat($arResult['PRICE_DELIVERY'], $arResult["CURRENCY"]);
$arResult['FORMATTED_BASKET_PRICE'] = CurrencyFormat($basketSum, $arResult["CURRENCY"]);

$arResult['WAIT_PAYMENT_STATUSES'] = ['B', 'C'];
$arResult['AJAX_CHANGE_RESERVE_DATE_URL'] = \Local\Config::get('ajax')['changeOrderReserveDate'];
$CSaleOrderPropsValue = new CSaleOrderPropsValue();
$propDateReserveCode = 'DateRezerv';
$arResult[$propDateReserveCode] = false;
if (in_array($arResult['STATUS_ID'], $arResult['WAIT_PAYMENT_STATUSES']) && ($arResult['CANCELED'] == 'N')) {
	$objProp = $CSaleOrderPropsValue->GetList(
		[
			'ID' => 'ASC',
		],
		[
			'ORDER_ID' => $arResult['ID'],
			'CODE' => $propDateReserveCode,
		]
	);
	$property = $objProp->Fetch();
	$arResult[$propDateReserveCode] = ($property) ? $property : false;
}
$objProp = $CSaleOrderPropsValue->GetList(
	[
		'ID' => 'ASC',
	],
	[
		'ORDER_ID' => $arResult['ID'],
		'CODE' => 'OrderDetails',
	]
);
$property = $objProp->Fetch();
$CFile = new CFile();
$arResult['OrderDetails'] = ($property) ? $CFile->GetFileArray($property['VALUE']) : false;
$arResult['AJAX_DATA'] = htmlentities(json_encode(
	[
		'ID' => $arResult['XML_ID'],
		'DateRezerv' => ($arResult[$propDateReserveCode]) ? $arResult[$propDateReserveCode]['VALUE'] : date('d.m.Y H:i:s')
	]
));

$arResult['DateRezervDisabled'] = false;
if (in_array($arResult['STATUS_ID'], $arResult['WAIT_PAYMENT_STATUSES']) && ($arResult['CANCELED'] == 'N')) {
	$arResult['DateRezervDisabled'] = true;
}