<?php
/**
 * @var array $arResult
 * @var array $arParams
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest()) {
	$arResult['AJAX'] = 'Y';
} else {
	$delayParam = $request->get('section');
}


$arResult['BASKET_TYPES'] = [
	'AnDelCanBuy' => [
		'NAME' => 'Корзина',
		'SHOW_DELAY' => !empty($delayParam) ? false : true,
		'CODE' => 'cart'
	],
	'DelDelCanBuy' => [
		'NAME' => 'Избранное',
		'SHOW_DELAY' => !empty($delayParam) ? true : false,
		'CODE' => 'wishlist'
	]
];
$arHeaders = [];
foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader) {
	$arHeaders[] = $arHeader["id"];
}
$arResult['arHeaders'] = $arHeaders;

// fixme: broken catalog pictures
// картинки хранятся очень оригинально +
// (предположение) раньше многие поля были множественными, из-за чего все плохо
$itemIds = [];
foreach ($arResult['ITEMS'] as $type => $items) {
	foreach ($items as $key => $item) {
		$itemIds[$item['PRODUCT_ID']] = [
			'TYPE' => $type,
			'KEY' => $key,
		];
	}
}
unset($item, $items, $key, $type);

if (!empty($itemIds)) {
	$CIBlockElement = new CIBlockElement();
	$objElements = $CIBlockElement->GetList(
		[
			'ID' => 'ASC',
		],
		[
			'ID' => array_keys($itemIds),
		],
		false,
		false,
		[
			'ID',
			'PROPERTY_VOZMOZHNAYA_DATA_OTGRUZKI_IZ_MYUNKHENA',
			'PROPERTY_VOZMOZHNAYA_DATA_OTGRUZKI_IZ_MYUNKHENA_NAME',
			'PROPERTY_MAIN_PHOTO',
			'PROPERTY_AVAILABLE'
		]
	);
	$checkArray = [];
	while ($item = $objElements->Fetch()) {
		if (in_array($item['ID'], $checkArray)) {
			continue;
		}
		$arResult['ITEMS'][$itemIds[$item['ID']]['TYPE']][$itemIds[$item['ID']]['KEY']]['AVAILABLE_STATUS'] = (is_null($item['PROPERTY_AVAILABLE_VALUE'])) ? 'Срок отгрузки: ' . $item['PROPERTY_VOZMOZHNAYA_DATA_OTGRUZKI_IZ_MYUNKHENA_VALUE'] : 'В наличии';
		$arResult['ITEMS'][$itemIds[$item['ID']]['TYPE']][$itemIds[$item['ID']]['KEY']]['PHOTO'] = ($item['PROPERTY_MAIN_PHOTO_VALUE'][0] == '/') ? $item['PROPERTY_MAIN_PHOTO_VALUE'] : '/' . $item['PROPERTY_MAIN_PHOTO_VALUE'];
	}
}
// end broken catalog pictures

$countBasketItems = count($arResult['ITEMS']['AnDelCanBuy']);
$countWishItems = count($arResult['ITEMS']['DelDelCanBuy']);
$arResult['JSON'] = htmlentities(json_encode([
	'basket' => $countBasketItems,
	'wish' => $countWishItems,
]));
