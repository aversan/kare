<?php
/** @var array $arParams */
/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

function formatPrice($price, $currencyCode)
{
	if (!$price && $currencyCode) {
		$currency = CCurrencyLang::GetCurrencyFormat($currencyCode, LANGUAGE_ID);
		return substr($currency["FORMAT_STRING"], strrpos($currency["FORMAT_STRING"], ' '));
	} elseif ($price && !$currencyCode) {
		return number_format($price, 0, '', ' ');
	} elseif ($price && $currencyCode) {
		$currency = CCurrencyLang::GetCurrencyFormat($currencyCode, LANGUAGE_ID);
		return number_format($price, 0, '', ' ')
			.substr($currency["FORMAT_STRING"], strrpos($currency["FORMAT_STRING"], ' '));
	}
}

$orderIds = [];
foreach ($arResult["ORDERS"] as $key => &$order) {
	$date = explode(' ', $order['ORDER']['DATE_INSERT_FORMAT']);
	$order['DATE'] = $date[0];

	$order['BASKET_ITEMS_COUNT'] = count($order['BASKET_ITEMS']);

	$basketSum = 0;
	foreach ($order['BASKET_ITEMS'] as &$item) {
		$item['FORMATTED_PRICE'] = formatPrice($item["PRICE"], $item["CURRENCY"]);
		$item['FORMATTED_TOTAL_PRICE'] = formatPrice($item["PRICE"] * $item["QUANTITY"], $item["CURRENCY"]);
		$basketSum += $item['PRICE'] * $item["QUANTITY"];
	}
	$order['ORDER']['FORMATTED_BASKET_PRICE'] = CurrencyFormat($basketSum, $order['ORDER']["CURRENCY"]);

	$orderIds[$order['ORDER']['ID']] = $key;
	$order['ORDER']['FORMATTED_PRICE_DELIVERY'] = ($order['ORDER']['STATUS_ID'] == 'N') ? 'Сумма доставки рассчитывается менеджером' : CurrencyFormat($order['ORDER']['PRICE_DELIVERY'], $order['ORDER']["CURRENCY"]);
}

if (!empty($orderIds)) {
	$propOrderDetails = 'OrderDetails';
	$CSaleOrderPropsValue = new CSaleOrderPropsValue();
	$CFile = new CFile();
	$objProp = $CSaleOrderPropsValue->GetList(
		[
			'ID' => 'ASC',
		],
		[
			'ORDER_ID' => array_keys($orderIds),
			'CODE' => [$propOrderDetails],
		]
	);
	while ($property = $objProp->Fetch()) {
		$arResult['ORDERS'][$orderIds[$property['ORDER_ID']]]['ORDER'][$propOrderDetails] = (!empty($property['VALUE'])) ? $CFile->GetFileArray($property['VALUE']) : false;
	}
}