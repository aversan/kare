<?
/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$itemIds = [
	'DELAY' => [],
	'ADDED' => [],
];
if (\Bitrix\Main\Loader::includeModule('sale')) {
	$CSaleBasket = new CSaleBasket();
	$objBasket = $CSaleBasket->GetList(
		[
			'ID' => 'ASC',
		],
		[
			"FUSER_ID" => $CSaleBasket->GetBasketUserID(),
			"LID" => SITE_ID,
			"ORDER_ID" => "NULL",
		],
		false,
		false,
		[
			'ID',
			'PRODUCT_ID',
			'DELAY',
		]
	);
	while ($item = $objBasket->Fetch()) {
		switch ($item['DELAY']) {
			case 'Y':
				$itemIds['DELAY'][] = $item['PRODUCT_ID'];
				break;
			case 'N':
				$itemIds['ADDED'][] = $item['PRODUCT_ID'];
		}
	}
}

$CFile = new CFile();

foreach ($arResult["ITEMS"] as $key => &$arItem) {
	//region todo:FIX THIS SHIT
	$arImagesSRC = array();
	if (isset($arItem['PROPERTIES']["MAIN_PHOTO"]["VALUE"]) && strlen($arItem['PROPERTIES']["MAIN_PHOTO"]["VALUE"]) && file_exists($_SERVER["DOCUMENT_ROOT"].SITE_DIR.$arItem['PROPERTIES']["MAIN_PHOTO"]["VALUE"])) {
		$arImagesSRC[] = trim($arItem['PROPERTIES']["MAIN_PHOTO"]["VALUE"]);
	}
	for ($i=1; $i<20; $i++) {
		if (isset($arItem['PROPERTIES']['KARTINKA'.$i]) && strlen($arItem['PROPERTIES']['KARTINKA'.$i]['VALUE']) && file_exists($_SERVER["DOCUMENT_ROOT"].SITE_DIR.$arItem['PROPERTIES']['KARTINKA'.$i]["VALUE"])) {
			$arImagesSRC[] = trim($arItem['PROPERTIES']['KARTINKA'.$i]["VALUE"]);
		}
	}
	foreach($arImagesSRC as $sImageSrc)
	{
		if (strlen($sImageSrc) && file_exists($_SERVER["DOCUMENT_ROOT"].SITE_DIR.$sImageSrc))
		{
			$arResult["ITEMS"][$key]['PICTURE']['SMALL']['src'] = SITE_DIR.$sImageSrc;
			break;
		}
	}
	//endregion

	foreach ($arItem['PRICES'] as $arPrice) {
		if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]) {
			$arResult["ITEMS"][$key]['DISCOUNT_ICO'] = 'Y';
		}
	}

	$arItem['ADDED'] = false;
	$arItem['DELAY'] = false;
	if (in_array($arItem['ID'], $itemIds['ADDED'])) {
		$arItem['ADDED'] = true;
	} elseif (in_array($arItem['ID'], $itemIds['DELAY'])){
		$arItem['DELAY'] = true;
	}
}