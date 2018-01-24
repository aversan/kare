<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
/**
 * @var array $arParams
 * @var array $arResult
 */
global $APPLICATION;
$arParams["ADD_ELEMENT_CHAIN"] = (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : "N");

CModule::IncludeModule("iblock");
$CIBlockSection = new CIBlockSection();
$CIBlockElement = new CIBlockElement();
$arPageParams = $arSection = $section = array();
if ($arResult["VARIABLES"]["SECTION_ID"] > 0) {
	$db_list = $CIBlockSection->GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"]), true, array("ID", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID"));
	$section = $db_list->GetNext();
} elseif (strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0) {
	$db_list = $CIBlockSection->GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"]), true, array("ID", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID"));
	$section = $db_list->GetNext();
}
if ($arResult["VARIABLES"]["ELEMENT_ID"] > 0) {
	$resElement = $CIBlockElement->GetList(array(), array("ID" => $arResult["VARIABLES"]["ELEMENT_ID"]), false, false, array("ID", "IBLOCK_SECTION_ID"));
	$arElement = $resElement->Fetch();
	if ($arElement["IBLOCK_SECTION_ID"] && !$section) {
		$db_list = $CIBlockSection->GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arElement["IBLOCK_SECTION_ID"]), true, array("ID", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID"));
		$section = $db_list->GetNext();
	}
} elseif (strlen(trim($arResult["VARIABLES"]["ELEMENT_CODE"])) > 0) {
	$resElement = $CIBlockElement->GetList(array(), array("=CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]), false, false, array("ID", "IBLOCK_SECTION_ID"));
	$arElement = $resElement->Fetch();
	if ($arElement["IBLOCK_SECTION_ID"] && !$section) {
		$db_list = $CIBlockSection->GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arElement["IBLOCK_SECTION_ID"]), true, array("ID", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID"));
		$section = $db_list->GetNext();
	}
}

$CUserFieldEnum = new CUserFieldEnum();
if ($section) {
	$arSection["ID"] = $section["ID"];
	$arSection["IBLOCK_SECTION_ID"] = $section["IBLOCK_SECTION_ID"];
	if ($section[$arParams["SECTION_DISPLAY_PROPERTY"]]) {
		$arDisplayRes = $CUserFieldEnum->GetList(array(), array("ID" => $section[$arParams["SECTION_DISPLAY_PROPERTY"]]));
		if ($arDisplay = $arDisplayRes->GetNext()) {
			$arSection["DISPLAY"] = $arDisplay["XML_ID"];
		}
	}
	$arSection["SEO_DESCRIPTION"] = $section[$arParams["SECTION_PREVIEW_PROPERTY"]];
	$arPageParams["title"] = $section[$arParams["LIST_BROWSER_TITLE"]];
	$arPageParams["keywords"] = $section[$arParams["LIST_META_KEYWORDS"]];
	$arPageParams["description"] = $section[$arParams["LIST_META_DESCRIPTION"]];
}

if ($arPageParams) {
	foreach ($arPageParams as $code => $value) {
		if ($value) {
			$APPLICATION->SetPageProperty($code, $value);
		}
	}
}

global $KShopSectionID;
$KShopSectionID = $arSection["ID"];

//add microdata to price
global $priceMicrodata;
$priceMicrodata = true;
?>
<div class="catalog-detail">
	<? /*<div class="compare_small" id="compare_small"></div>*/ ?>
	<? $APPLICATION->IncludeComponent(
		"bitrix:catalog.element",
		"main",
		Array(
			"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
			"IBLOCK_REVIEWS_TYPE" => $arParams["IBLOCK_REVIEWS_TYPE"],
			"IBLOCK_REVIEWS_ID" => $arParams["IBLOCK_REVIEWS_ID"],
			"SEF_MODE_BRAND_SECTIONS" => $arParams["SEF_MODE_BRAND_SECTIONS"],
			"SEF_MODE_BRAND_ELEMENT" => $arParams["SEF_MODE_BRAND_ELEMENT"],
			"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
			"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
			"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SET_TITLE" => $arParams["SET_TITLE"],
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
			"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
			"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
			"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],
			"USE_ALSO_BUY" => $arParams["USE_ALSO_BUY"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"SKU_DISPLAY_LOCATION" => $arParams["SKU_DISPLAY_LOCATION"],
			"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
			"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
			"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
			"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
			"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
			"USE_STORE" => $arParams["USE_STORE"],
			"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
			"USE_STORE_SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
			"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
			"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
			"STORE_PATH" => $arParams["STORE_PATH"],
			"MAIN_TITLE" => $arParams["MAIN_TITLE"],
			"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"IBLOCK_STOCK_ID" => $arParams["IBLOCK_STOCK_ID"],
			"SEF_MODE_STOCK_SECTIONS" => $arParams["SEF_MODE_STOCK_SECTIONS"],
			"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
			"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"USE_ELEMENT_COUNTER" => $arParams["USE_ELEMENT_COUNTER"],
			"USE_RATING" => $arParams["USE_RATING"],
			"USE_REVIEW" => $arParams["USE_REVIEW"],
			"FORUM_ID" => $arParams["FORUM_ID"],
			"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
			"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
			"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
			"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
			"SHOW_BRAND_PICTURE" => $arParams["SHOW_BRAND_PICTURE"],
			"PROPERTIES_DISPLAY_LOCATION" => $arParams["PROPERTIES_DISPLAY_LOCATION"],
			"PROPERTIES_DISPLAY_TYPE" => $arParams["PROPERTIES_DISPLAY_TYPE"],
			"SHOW_ADDITIONAL_TAB" => $arParams["SHOW_ADDITIONAL_TAB"],
			"SHOW_ASK_BLOCK" => $arParams["SHOW_ASK_BLOCK"],
			"ASK_FORM_ID" => $arParams["ASK_FORM_ID"],
			"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
			"SHOW_HINTS" => $arParams["SHOW_HINTS"],
			"SHOW_KIT_PARTS" => $arParams["SHOW_KIT_PARTS"],
			"SHOW_KIT_PARTS_PRICES" => $arParams["SHOW_KIT_PARTS_PRICES"],
			'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'],
			'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
			'SHOW_404' => $arParams['SHOW_404'],
		),
		$component
	); ?>
</div>

<?
$arViewedID = array();
$CSaleViewedProduct = new CSaleViewedProduct();
$CSaleBasket = new CSaleBasket();
if (CModule::IncludeModule('sale')) {
	$resViewed = $CSaleViewedProduct->GetList(array("DATE_VISIT" => "DESC"), array("LID" => SITE_ID, "FUSER_ID" => $CSaleBasket->GetBasketUserID()), false, false, array("ID", "PRODUCT_ID"));
	while ($arItem = $resViewed->Fetch()) {
		if ($arItem["PRODUCT_ID"] != $arElement["ID"]) {
			$arViewedID[] = $arItem["PRODUCT_ID"];
		}
		if (count($arViewedID) >= 16) {
			break;
		}
	}
}
if ($arViewedID):
	?>
	<? global $viewedFilter; ?>
	<? $viewedFilter = array('ID' => $arViewedID); ?>
	<div class="product-slider product-slider_seen js-product-gallery">
		<div class="product-slider__inner">
			<? $APPLICATION->IncludeComponent(
				"bitrix:catalog.top",
				"kare_slider",
				Array(
					"SHOW_ALL_WO_SECTION" => "Y",
					"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"SECTION_ID" => "",
					"SECTION_CODE" => "",
					"ELEMENT_SORT_FIELD" => "ID",//"FILTER",
					"ELEMENT_SORT_ORDER" => "ASC",
					"FILTER_NAME" => 'viewedFilter',
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGE_ELEMENT_COUNT" => 3,
					"LINE_ELEMENT_COUNT" => 1,
					"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
					"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
					"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
					"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
					"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
					"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
					"BASKET_URL" => $arParams["BASKET_URL"],
					"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
					"AJAX_MODE" => $arParams["AJAX_MODE"],
					"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
					"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
					"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => "Y",
					"META_KEYWORDS" => "",
					"META_DESCRIPTION" => "",
					"BROWSER_TITLE" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					"DISPLAY_COMPARE" => "N",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => $arParams["SET_STATUS_404"],
					"CACHE_FILTER" => "Y",
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
					"OFFERS_CART_PROPERTIES" => array(),
					"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
					"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
					"PAGER_TITLE" => $arParams["PAGER_TITLE"],
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => "",
					"PAGER_DESC_NUMBERING" => "",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
					"PAGER_SHOW_ALL" => "",
					"AJAX_OPTION_ADDITIONAL" => "",
					"ADD_CHAIN_ITEM" => "N",
					"SHOW_QUANTITY" => "N",
					"SHOW_QUANTITY_COUNT" => "",
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"USE_STORE" => "",
					"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
					"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
					"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
					"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
					"DISPLAY_WISH_BUTTONS" => $arParams['DISPLAY_WISH_BUTTONS'],
					"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
					"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
					"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
					"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
					"SHOW_HINTS" => $arParams["SHOW_HINTS"],
					"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
					"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
					"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
					"VIEWED_COUNT" => "3",
					"VIEWED_NAME" => "Y",
					"VIEWED_IMAGE" => "Y",
					"VIEWED_PRICE" => "Y",
					"VIEWED_CURRENCY" => "default",
					"VIEWED_CANBUY" => "Y",
					'TITLE_SECTION' => 'Просмотренные товары',
					'LINK_ALL' => 'N',
					'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'],
					'SHOW_404' => $arParams['SHOW_404'],
				), $component
			); ?>
		</div>
	</div>
<? endif; ?>
