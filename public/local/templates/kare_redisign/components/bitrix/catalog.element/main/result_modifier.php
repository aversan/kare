<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/**
 * @var array $arParams
 * @var array $arResult
 */
global $APPLICATION;


if (\Bitrix\Main\Loader::includeModule('sale')) {
	$CSaleBasket = new CSaleBasket();
	$basketItem = $CSaleBasket->GetList(
		[
			'ID' => 'ASC',
		],
		[
			'PRODUCT_ID' => $arResult['ID'],
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
	)->Fetch();
	$arResult['DELAY'] = false;
	$arResult['ADDED'] = false;
	if ($basketItem) {
		if ($basketItem['DELAY'] == 'Y') {
			$arResult['DELAY'] = true;
		} else {
			$arResult['ADDED'] = true;
		}
	}
}


$db_res = CCatalogStore::GetList(
	array(
		'SORT' => 'ASC'
	),
	array(
		"ACTIVE" => "Y",
		'SITE_ID' => SITE_ID
	),
	false,
	false,
	array(
		'ID'
	)
);

$arStores = array();
while ($res = $db_res->GetNext()) {
	$arStores[] = $res['ID'];
}
$arResult["STORES_COUNT"] = count($arStores);

$objStoreProducts = CCatalogStoreProduct::GetList(
	[
		'SORT' => 'ASC',
	],
	[
		'PRODUCT_ID' => $arResult['ID'],
		'STORE_ID' => $arStores,
	]
);

while ($store = $objStoreProducts->Fetch()) {
	if ($store['AMOUNT'] > 0) {
		$arResult['STORES'][] = $store;
	}
}

$CIBlockElement = new CIBlockElement();
if ($arParams["SHOW_KIT_PARTS"] == "Y") {
	$CCatalogProductSet = new CCatalogProductSet();
	$arSetItems = array();

	$arSets = $CCatalogProductSet->getAllSetsByProduct($arResult["ID"], 1);

	if (is_array($arSets) && !empty($arSets)) {
		foreach ($arSets as $key => $set) {
			foreach ($set["ITEMS"] as $i => $val) {
				$arSetItems[] = $val["ITEM_ID"];
			}
		}
	}

	$arResultPrices = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);

	$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "DETAIL_PICTURE");
	foreach ($arResultPrices as &$value) {
		if ($value['CAN_VIEW'] && $value['CAN_BUY']) {
			$arSelect[] = $value["SELECT"];
		}
	}
	if (!empty($arSetItems)) {
		$db_res = $CIBlockElement->GetList(Array("SORT" => "ASC"), Array("ID" => $arSetItems), false, false, $arSelect);
		while ($res = $db_res->GetNext()) {
			$arResult["SET_ITEMS"][] = $res;
		}
	}

	$arConvertParams = array();
	if ('Y' == $arParams['CONVERT_CURRENCY']) {
		if (!CModule::IncludeModule('currency')) {
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		} else {
			$CCurrency = new CCurrency();
			$arResultModules['currency'] = true;
			$arCurrencyInfo = $CCurrency->GetByID($arParams['CURRENCY_ID']);
			if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo))) {
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			} else {
				$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
			}
		}
	}

	$bCatalog = CModule::IncludeModule('catalog');

	if (is_array($arResult["SET_ITEMS"]) && !empty($arResult["SET_ITEMS"])) {
		foreach ($arResult["SET_ITEMS"] as $key => $setItem) {
			if ($arParams["USE_PRICE_COUNT"]) {
				if ($bCatalog) {
					$arResult["SET_ITEMS"][$key]["PRICE_MATRIX"] = CatalogGetPriceTableEx($arResult["SET_ITEMS"][$key]["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);
					foreach ($arResult["SET_ITEMS"][$key]["PRICE_MATRIX"]["COLS"] as $keyColumn => $arColumn)
						$arResult["SET_ITEMS"][$key]["PRICE_MATRIX"]["COLS"][$keyColumn]["NAME_LANG"] = htmlspecialcharsbx($arColumn["NAME_LANG"]);
				}
			} else {
				$arResult["SET_ITEMS"][$key]["PRICES"] = CIBlockPriceTools::GetItemPrices($arParams["IBLOCK_ID"], $arResultPrices, $arResult["SET_ITEMS"][$key], $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
				if (!empty($arResult["SET_ITEMS"][$key]["PRICES"])) {
					foreach ($arResult["SET_ITEMS"][$key]['PRICES'] as &$arOnePrice) {
						if ('Y' == $arOnePrice['MIN_PRICE']) {
							$arResult["SET_ITEMS"][$key]['MIN_PRICE'] = $arOnePrice;
							break;
						}
					}
					unset($arOnePrice);
				}

			}
		}
	}
}

if (intVal($arParams["IBLOCK_STOCK_ID"])) {
	$arSelect = array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT", "DETAIL_PAGE_URL");
	$dbRes = $CIBlockElement->GetList(array(), array("ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "IBLOCK_ID" => $arParams["IBLOCK_STOCK_ID"], "PROPERTY_LINK" => $arResult["ID"]), false, false, $arSelect);
	while ($res = $dbRes->GetNext()) {
		$arResult["STOCK"][] = $res;
	}
}

if (!empty($arResult["PROPERTIES"]["SERVICES"]["VALUE"])) {
	$arSelect = array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT", "DETAIL_PAGE_URL");
	$dbRes = $CIBlockElement->GetList(array(), array("ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "IBLOCK_ID" => $arResult["PROPERTIES"]["SERVICES"]["LINK_IBLOCK_ID"], "ID" => $arResult["PROPERTIES"]["SERVICES"]["VALUE"]), false, false, $arSelect);
	while ($res = $dbRes->GetNext()) {
		$arResult["SERVICES"][] = $res;
	}
}
foreach ($arResult['PRICES'] as $arPrice) {
	if ($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]) {
		$arResult['DISCOUNT_ICO'] = 'Y';
	}
}

if ($arResult['PROPERTIES']['KOLLEKTSIYA']['VALUE']) {
	$i = 0;
	$collection_link = '';

	$res_collection = $CIBlockElement->GetList(array('SORT' => 'ASC'), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], '!ID' => $arResult['ID'], 'PROPERTY_KOLLEKTSIYA' => $arResult['PROPERTIES']['KOLLEKTSIYA']['VALUE'], 'ACTIVE' => 'Y'), false, false, array('ID'));
	while ($ar_collection = $res_collection->GetNext()) {
		$arResult['COLLECTION_PRODUCT_ID'][] = $ar_collection['ID'];
		if ($i > 99) break;
		$i++;
	}

}

$arResult['COLLECTION_LINK'] = $arResult['SECTION']['SECTION_PAGE_URL'] . '?set_filter=Y' . $collection_link;
shuffle($arResult['COLLECTION_BACKGROUND']);
$arResult['COLLECTION_PRODUCT_ID'] = array_slice($arResult['COLLECTION_PRODUCT_ID'], 0, 12);
if ($arResult["DETAIL_PICTURE"]["SRC"]) {
	$APPLICATION->AddHeadString('<link rel="image_src" href="' . $arResult["DETAIL_PICTURE"]["SRC"] . '"  />', true);
}
$product_props = CIBlockPriceTools::GetProductProperties(
	$arParams["IBLOCK_ID"],
	$arResult["ID"],
	$arParams["PRODUCT_PROPERTIES"],
	$arResult["PROPERTIES"]
);
$i = 0;
foreach ($product_props as $key => $prop) {
	if ($prop['VALUES'][$prop['SELECTED']]) {

		$res_code = CIBlockProperty::GetList(array(), array('CODE' => $key));
		if ($ar_code = $res_code->Fetch()) {
			$code_name = $ar_code['NAME'];
		}
		if (count($prop['VALUES']) > 1) {
			foreach ($prop['VALUES'] as $val) {
				$arResult['PROD_PROP'][$i]['NAME'] = $code_name;
				$arResult['PROD_PROP'][$i]['CODE'] = $key;
				$arResult['PROD_PROP'][$i]['VALUE'] = $val;
				$i++;
			}
		} else {
			$arResult['PROD_PROP'][$i]['NAME'] = $code_name;
			$arResult['PROD_PROP'][$i]['CODE'] = $key;
			$arResult['PROD_PROP'][$i]['VALUE'] = $prop['VALUES'][$prop['SELECTED']];
			$i++;
		}
	}
}


/* rename props */
foreach ($arResult["DISPLAY_PROPERTIES"] as $key => $arProp) {
	if ($arProp["XML_ID"] == "sv_Colors_orig") $arResult["DISPLAY_PROPERTIES"][$key]["NAME"] = "Цвет";
	elseif ($arProp["XML_ID"] == "sv_materials_orig") $arResult["DISPLAY_PROPERTIES"][$key]["NAME"] = "Материал";
}


$cp = $this->__component;
if (is_object($cp))
	$cp->SetResultCacheKeys(array('TIMESTAMP_X'));

$arResult['PICTURES'] = [];
if (!empty($arResult['PROPERTIES']['MAIN_PHOTO']['VALUE'])){
	$arResult['PICTURES'][] = $arResult['PROPERTIES']['MAIN_PHOTO']['VALUE'];
}
for ($i = 1; $i <= 15; ++$i) {
	if (!empty($arResult['PROPERTIES']['KARTINKA' . $i]['VALUE'])) {
		$arResult['PICTURES'][] = $arResult['PROPERTIES']['KARTINKA' . $i]['VALUE'];
	}
}
$arResult['BASE_PRICE'] = reset($arResult['PRICES']);
