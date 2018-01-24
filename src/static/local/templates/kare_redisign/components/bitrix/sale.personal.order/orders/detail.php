<?php
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

if ($_GET['payment']) {
	$templateName = 'payment';
	$APPLICATION->SetTitle('Оплата');?>
	<h1><?=$APPLICATION->GetTitle()?></h1>
<? }
$CSaleOrder = new CSaleOrder();
$order = $CSaleOrder->GetList(
	false,
	[
		'ACCOUNT_NUMBER' => $arResult['VARIABLES']['ID'],
	],
	false,
	false,
	[
		'ID',
		'CANCELED',
		'STATUS_ID'
	]
)->Fetch();
if (
		(
				($order['CANCELED'] == 'Y')
				|| ($order['PAYED'] == 'Y')
				|| in_array($order['STATUS_ID'], ['F', 'E', 'G', 'D', 'H'])
		)
		&& ($templateName == 'payment')
	) {
	LocalRedirect($arResult['PATH_TO_LIST']);
}

$arDetParams = [
		"PATH_TO_LIST" => $arResult["PATH_TO_LIST"],
		"PATH_TO_CANCEL" => $arResult["PATH_TO_CANCEL"],
		"PATH_TO_PAYMENT" => $arParams["PATH_TO_PAYMENT"],
		"SET_TITLE" => "Y",
		"ID" => $arResult["VARIABLES"]["ID"],
		"ACTIVE_DATE_FORMAT" => $arParams["ACTIVE_DATE_FORMAT"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"CUSTOM_SELECT_PROPS" => $arParams["CUSTOM_SELECT_PROPS"]
	];
foreach ($arParams as $key => $val) {
	if (strpos($key, "PROP_") !== false) {
		$arDetParams[$key] = $val;
	}
}

$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order.detail",
	$templateName,
	$arDetParams,
	$component
);
