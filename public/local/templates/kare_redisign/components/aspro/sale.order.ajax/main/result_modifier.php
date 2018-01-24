<?php
/** @var array $arParams */
/** @var array $arResult */
/** @var $APPLICATION CMain */
/** @global CUser $USER */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult['SITE_TEMPLATE_PATH'] = SITE_TEMPLATE_PATH;

$arResult['ITEMS_PRICE'] = FormatCurrency($arResult['ORDER_PRICE'], $arResult['BASKET_ITEMS'][0]['CURRENCY']);
$arResult['FINAL_PRICE'] = FormatCurrency($arResult['ORDER_PRICE'] + $arResult['DELIVERY_PRICE']
	- $arResult['BONUS_COUNT'], $arResult['BASKET_ITEMS'][0]['CURRENCY']);

$arResult['BASKET_ITEMS_COUNT'] = count($arResult['BASKET_ITEMS']);

$arResult['SUBMITFORM_SCRIPT']['CURPAGE'] = $APPLICATION->GetCurPage();
$arResult['SUBMITFORM_SCRIPT']['IS_LOCATION_PRO_ENABLED'] = CSaleLocation::isLocationProEnabled();

$city = \Bitrix\Sale\Location\TypeTable::getRow(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')));
$arResult['SALE_ORDER_AJAX_INIT'] = CUtil::PhpToJSObject(array(
	'source' => $this->__component->getPath() . '/get.php',
	'cityTypeId' => intval($city['ID']),
	'messages' => array(
		'otherLocation' => '--- ' . GetMessage('SOA_OTHER_LOCATION'),
		'moreInfoLocation' => '--- ' . GetMessage('SOA_NOT_SELECTED_ALT'),
		'notFoundPrompt' => '<div class="-bx-popup-special-prompt">' . GetMessage('SOA_LOCATION_NOT_FOUND') . '.<br />'
			. GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
				'#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
				'#ANCHOR_END#' => '</a>'
			)) . '</div>'
	)
));

$rsUser = $USER->GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$CSaleOrderUserProps = new CSaleOrderUserProps();
$fUser = $CSaleOrderUserProps->GetList(
	false,
	[
		'USER_ID' => $USER->GetID(),
	]
);

$arFUser = [];
while ($fUserVals = $fUser->Fetch()) {
	$arFUser[] = $fUserVals;
}

if (count($arFUser) > 1) {
	$olderId = 0;
	foreach ($arFUser as $userProfile) {
		if ($userProfile['ID'] > $olderId) {
			$olderId = $userProfile['ID'];
		}
	}
	$fUserProfileId = $olderId;
} else {
	$fUserProfileId = $arFUser[0]['ID'];
}

$CSaleOrderUserPropsValue = new CSaleOrderUserPropsValue();
$propValues = $CSaleOrderUserPropsValue->GetList(
	[
		'ID' => 'ASC',
	],
	[
		'USER_PROPS_ID' => $fUserProfileId,
	],
	false,
	false,
	[
		'NAME',
		'VALUE',
		'PROP_CODE',
	]
);

while ($userOrderProps = $propValues->Fetch()) {
	if ($userOrderProps['PROP_CODE'] == 'LAST_NAME'
		|| $userOrderProps['PROP_CODE'] == 'EMAIL'
		|| $userOrderProps['PROP_CODE'] == 'USER_LOGIN'
		|| $userOrderProps['PROP_CODE'] == 'FIO_POLUCHATELYA'
		|| $userOrderProps['PROP_CODE'] == 'NAME'
		|| $userOrderProps['PROP_CODE'] == 'SECOND_NAME'
		|| $userOrderProps['PROP_CODE'] == 'SUBSCRIBE'
	) {
		continue;
	}
	if ($userOrderProps['PROP_TYPE'] == 'LOCATION') {
		$userOrderProps['LOCATION_CODE'] = $userOrderProps['VALUE'];
		$arSity = CSaleLocation::GetByID($userOrderProps['LOCATION_CODE']);
		$userOrderProps['VALUE'] = $arSity['CITY_NAME_LANG'];
	}
	$arResult['ORDER_PROP']['LOCATION_ORDER_PROPS_VALUE'][] = $userOrderProps;
}

foreach ($arResult['ORDER_PROP']['LOCATION_ORDER_PROPS_VALUE'] as $key => $value) {
	$arResult['ORDER_PROP']['LOCATION_ORDER_PROPS_VALUE'][$value['PROP_CODE']] = $value;
	unset($arResult['ORDER_PROP']['LOCATION_ORDER_PROPS_VALUE'][$key]);
}

foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as &$personalProp) {
	$value = "";
	if ($_REQUEST["change"] != "y") {
		if ($personalProp['VALUE_FORMATED']) {
			$value = $personalProp['VALUE_FORMATED'];
		} elseif (!is_array($arResult["ORDER_PROP"]["USER_PROFILES"])) {
			if ($personalProp['CODE'] == 'USER_LOGIN') {
				$value = $arUser['LOGIN'];
			} else {
				$value = $arUser[$personalProp['CODE']];
			}
		}
	}
	if (!$value) {
		$value = $_SESSION['CONFIRMED_ORDER'][$personalProp["FIELD_NAME"]];
	}
	$personalProp['USER_VALUE'] = $value;
}

if ($arParams["ALLOW_AUTO_REGISTER"] == "Y") {
	$arResult["REDIRECT_URL"] = str_replace("/ajax/show_order.php", "/order/", $arResult["REDIRECT_URL"]);
}


//$USER->Authorize(609);
//var_dump($arResult['ORDER_PROP']['USER_PROPS_Y'][6]);
//dump($arResult['ORDER_PROP']);
//dump($arResult['ORDER_PROP']['LOCATION_ORDER_PROPS_VALUE']);

//dump($arResult['DELIVERY']);
//dump($arResult);
//dump($arResult["ORDER_PROP"]["RELATED"]);

//sessid=f3f84476e693803463ec72de6643f24c&PERSON_TYPE=1&PERSON_TYPE_OLD=1&showProps=N&PROFILE_ID=26&ORDER_PROP_3=%2B7+(123)+123-12-31&ORDER_PROP_1=%D0%9C%D0%B0%D0%BA%D1%81%D0%B8%D0%BC+%D0%A2%D0%B5%D0%B9%D0%BA%D0%B8%D0%BD&ORDER_PROP_14=%D1%83%D0%BA%D1%83%D0%B0&ORDER_PROP_15=%D0%B0%D1%83%D0%BA%D0%B0%D1%83%D0%BA%D0%B0&ORDER_PROP_2=mvt%40au74.ru&BUYER_STORE=0&ORDER_PROP_6_val=%D0%A7%D0%B5%D0%BB%D1%8F%D0%B1%D0%B8%D0%BD%D1%81%D0%BA%2C+%D0%A7%D0%B5%D0%BB%D1%8F%D0%B1%D0%B8%D0%BD%D1%81%D0%BA%D0%B0%D1%8F+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C%2C+%D0%A0%D0%BE%D1%81%D1%81%D0%B8%D1%8F&ORDER_PROP_6=2368&DELIVERY_ID=2&ORDER_PROP_4=296500&ORDER_PROP_8=ddgd&ORDER_PROP_9=fdf&ORDER_PROP_10=&ORDER_PROP_11=%D0%9C%D0%B0%D0%BA%D1%81%D0%B8%D0%BC+%D0%A2%D0%B5%D0%B9%D0%BA%D0%B8%D0%BD&ORDER_PROP_22=%D1%83%D0%BA%D1%83%D0%B0&ORDER_PROP_23=%D0%B0%D1%83%D0%BA%D0%B0%D1%83%D0%BA%D0%B0&ORDER_PROP_24=%2B7+(123)+123-12-31&PAY_SYSTEM_ID=5&PAY_SYSTEM_ID=5&PAY_SYSTEM_ID=5&ORDER_PROP_12=Y&preorder=Y&confirmorder=N&profile_change=N&is_ajax_post=Y&json=Y
//ORDER_PROP_3=+7+(123)+123-12-31&ORDER_PROP_1=%D0%9C%D0%B0%D0%BA%D1%81%D0%B8%D0%BC+%D0%A2%D0%B5%D0%B9%D0%BA%D0%B8%D0%BD&ORDER_PROP_14=%D1%83%D1%86%D0%BA%D0%B0%D1%86%D1%83%D0%BA%D0%B0%D1%83&ORDER_PROP_15=%D0%BA%D0%B0%D1%83%D0%B0%D0%BA%D0%B0%D1%83%D0%B0&ORDER_PROP_2=mvt%40au74.ru&preorder=Y&sessid=6eba4eb60947cbb8f50977313dca5436&ORDER_PROP_6_val=%D0%A7%D0%B5%D0%BB%D1%8F%D0%B1%D0%B8%D0%BD%D1%81%D0%BA%2C+%D0%A7%D0%B5%D0%BB%D1%8F%D0%B1%D0%B8%D0%BD%D1%81%D0%BA%D0%B0%D1%8F+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C%2C+%D0%A0%D0%BE%D1%81%D1%81%D0%B8%D1%8F&ORDER_PROP_6=2368&DELIVERY_ID=2&ORDER_PROP_8=%D0%BF%D0%B0%D1%80%D0%B0%D1%80%D0%BF&ORDER_PROP_9=%D0%BE%D1%8B%D1%84%D1%80%D0%B2%D0%BB%D0%BE%D0%B0%D1%80&ORDER_PROP_10=%D1%8B%D0%B2%D1%84&ORDER_PROP_11=%D0%9C%D0%B0%D0%BA%D1%81%D0%B8%D0%BC+%D0%A2%D0%B5%D0%B9%D0%BA%D0%B8%D0%BD+regetrg+gretgertg&confirmorder=N
