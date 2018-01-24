<?php
/**
 * @var array $arResult
 * @var array $arParams
 * @global \CMain $APPLICATION;
 * @global \CUser $USER;
 */
use Local\Extensions\Order;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

if (!empty($arResult['ERROR'])) {

	$newErrorsArray = [];
	foreach ($arResult['ERROR'] as $key => $str) {
		preg_match('#\"(.+?)\"#', $str, $match);
		$newErrorsArray[end($match)] = $str;
	}
	$arResult['ERROR'] = $newErrorsArray;
}

$user = \Bitrix\Main\UserTable::getList(
	[
		'filter' => [
			'ID' => $USER->GetID(),
		],
		'select' => 	[
			'NAME',
			'LAST_NAME',
			'SECOND_NAME',
			'EMAIL',
			'LOGIN',
		]
	]
)->Fetch();

$newPropsY = [];
$summaryProps = [];
foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $key => $property) {
	switch ($property['CODE']) {
		case 'USER_LOGIN':
			$property['VALUE'] = $user['LOGIN'];
			break;
		case 'EMAIL':
			if (empty($_POST)) {
				$property['VALUE'] = $user['EMAIL'];
			}
			break;
	}
	if (($USER->IsAuthorized()) && ($property['REQUIED'] == 'Y') && ($property['GROUP_NAME'] == 'Личные данные') && empty($_POST)) {
		switch ($property['CODE']) {
			case 'LAST_NAME':
				$property['VALUE'] = $user['LAST_NAME'];
				break;
			case 'NAME':
				$property['VALUE'] = $user['NAME'];
				break;
			case 'SECOND_NAME':
				$property['VALUE'] = $user['SECOND_NAME'];
				break;
		}
	}
	$summaryProps[$property['CODE']]['FIELD'] = $property['FIELD_NAME'];
	$summaryProps[$property['CODE']]['NAME'] = $property['NAME'];
	$newPropsY[$property['GROUP_NAME']][] = $property;
}
$arResult['ORDER_PROP']['USER_PROPS_Y'] = $newPropsY;

$newPropsN = [];
foreach ($arResult['ORDER_PROP']['USER_PROPS_N'] as $key => $property) {
	$summaryProps[$property['CODE']]['FIELD'] = $property['FIELD_NAME'];
	$summaryProps[$property['CODE']]['NAME'] = $property['NAME'];
	$newPropsN[$property['GROUP_NAME']][] = $property;
}
$arResult['ORDER_PROP']['USER_PROPS_N'] = $newPropsN;

$newRelatedProps = [];
foreach ($arResult['ORDER_PROP']['RELATED'] as $property) {
	$summaryProps[$property['CODE']]['FIELD'] = $property['FIELD_NAME'];
	$summaryProps[$property['CODE']]['NAME'] = $property['NAME'];
	$property['PARAM'] = 'ADDRESS';
	$newRelatedProps['ADDRESS'][] = $property;
}
$arResult['ORDER_PROP']['RELATED'] = $newRelatedProps;

$arResult['SMALL_SUMMARY']['NUMBER_ITEMS'] = count($arResult['BASKET_ITEMS']);
$arResult['SMALL_SUMMARY']['DELIVERY'] = 'Сумма доставки рассчитывается менеджером';
$arResult['step2'] = false;
if ($_POST['step2'] == 'Y' && Order::getOrderSession()['flag']) {
	$arResult['step2'] = true;
	$CSaleLocation = new CSaleLocation();
	$arResult['POST_FOR_step2'] = $_POST;
	foreach ($summaryProps as $code => $prop) {
		$arResult['SUMMARY'][$code]['NAME'] = $prop['NAME'];
		if ($code == 'LOCATION') {
			$arResult['SUMMARY'][$code]['VALUE'] = $CSaleLocation->GetByID($_POST[$prop['FIELD']])['CITY_NAME'];
			continue;
		}
		$arResult['SUMMARY'][$code]['VALUE'] = $_POST[$prop['FIELD']];
	}
	$arResult['SUMMARY']['CUSTOMER_FULL_NAME']['NAME'] = 'ФИО получателя';
	$arResult['SUMMARY']['CUSTOMER_FULL_NAME']['VALUE'] = $arResult['SUMMARY']['LAST_NAME']['VALUE'] . ' ' . $arResult['SUMMARY']['NAME']['VALUE'] . ' ' . $arResult['SUMMARY']['SECOND_NAME']['VALUE'];
	$arResult['SUMMARY']['BUYER_FULL_NAME']['NAME'] = 'ФИО покупателя';
	$arResult['SUMMARY']['BUYER_FULL_NAME']['VALUE'] = $arResult['SUMMARY']['LAST_NAME']['VALUE'] . ' ' . $arResult['SUMMARY']['NAME']['VALUE'] . ' ' . $arResult['SUMMARY']['SECOND_NAME']['VALUE'];

	// fixme: broken catalog pictures
	// картинки хранятся очень оригинально +
	// (предположение) раньше многие поля были множественными, из-за чего все плохо
	$itemIds = [];
	foreach ($arResult['BASKET_ITEMS'] as $key => $item) {
		$itemIds[$item['PRODUCT_ID']] = [
			'KEY' => $key,
		];
	}
	unset($item, $items, $key, $type);

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
			'PROPERTY_AVAILABLE',
		]
	);
	$checkArray = [];
	while ($item = $objElements->Fetch()) {
		if (in_array($item['ID'], $checkArray)) {
			continue;
		}
		$arResult['BASKET_ITEMS'][$itemIds[$item['ID']]['KEY']]['AVAILABLE_STATUS'] = (is_null($item['PROPERTY_AVAILABLE_VALUE'])) ? 'Срок отгрузки: ' . $item['PROPERTY_VOZMOZHNAYA_DATA_OTGRUZKI_IZ_MYUNKHENA_VALUE'] : 'В наличии';
		$arResult['BASKET_ITEMS'][$itemIds[$item['ID']]['KEY']]['PHOTO'] = ($item['PROPERTY_MAIN_PHOTO_VALUE'][0] == '/') ? $item['PROPERTY_MAIN_PHOTO_VALUE'] : '/' . $item['PROPERTY_MAIN_PHOTO_VALUE'];
	}
	// end broken catalog pictures
}