<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


if (!function_exists("getColumnName"))
{
	function getColumnName($arHeader)
	{
		return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
	}
}

/*SKU -- */
$basePriceType = CCatalogGroup::GetBaseGroup();
$basePriceTypeName = $basePriceType["NAME"];

$arOffersIblock = CIBlockPriceTools::GetOffersIBlock($arResult["IBLOCK_ID"]);
$OFFERS_IBLOCK_ID = is_array($arOffersIblock)? $arOffersIblock["OFFERS_IBLOCK_ID"]: 0;
if ($OFFERS_IBLOCK_ID > 0)
{
	$dbOfferProperties = CIBlock::GetProperties($OFFERS_IBLOCK_ID, Array(), Array("!XML_ID" => "CML2_LINK"));
	$arIblockOfferProps = array();
	$offerPropsExists = false;
	while($arOfferProperties = $dbOfferProperties->Fetch())
	{
		if (!in_array($arOfferProperties["CODE"],$arParams["OFFERS_PROPERTY_CODE"]))
			continue;
		$arIblockOfferProps[] = array("CODE" => $arOfferProperties["CODE"], "NAME" => $arOfferProperties["NAME"]);
		$arIblockOfferProps2[] = array("CODE" => "SKU_".$arOfferProperties["CODE"], "NAME" => $arOfferProperties["NAME"]);
		$offerPropsExists = true;
	}
}
/* -- SKU */
$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
$arNotify = unserialize($notifyOption);
foreach($arResult["ITEMS"] as $cell=>$arElement)
{
	$arResult["ITEMS"][$cell]["DELETE_COMPARE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_LIST&id=".$arElement["ID"], array("action", "id")));
	if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) //Product has offers
	{
		$arSku = array();
		$minItemPrice = 0;
		$minItemPriceFormat = "";
		$arResult["ITEMS"][$cell]["OFFERS_CATALOG_QUANTITY"] = 0;
		
		foreach($arElement["OFFERS"] as $arOffer)
		{
			$arResult["ITEMS"][$cell]["OFFERS_CATALOG_QUANTITY"]  += $arOffer["CATALOG_QUANTITY"];
			
			foreach($arOffer["PRICES"] as $code=>$arPrice)
			{
				if($arPrice["CAN_ACCESS"])
				{
					if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])
					{
						$minOfferPrice = $arPrice["DISCOUNT_VALUE"];
						$minOfferPriceFormat = $arPrice["PRINT_DISCOUNT_VALUE"];
					}
					else
					{
						$minOfferPrice = $arPrice["VALUE"];
						$minOfferPriceFormat = $arPrice["PRINT_VALUE"];
					}

					if ($minItemPrice > 0 && $minOfferPrice < $minItemPrice)
					{
						$minItemPrice = $minOfferPrice;
						$minItemPriceFormat = $minOfferPriceFormat;
					}
					elseif ($minItemPrice == 0)
					{
						$minItemPrice = $minOfferPrice;
						$minItemPriceFormat = $minOfferPriceFormat;
					}
				}
			}
			/*foreach($arElement["PRICES"] as $key=>$price)
			{
				if ($price["VALUE"] < $minItemPrice)
				{
					$minItemPrice = $price["VALUE"];
					$minItemPriceFormat = $price["PRINT_VALUE"];
				}
			}*/
/*SKU -- */
			$arSkuTmp = array();
			if ($offerPropsExists)
			{
				foreach($arIblockOfferProps as $key2 => $arCode)
				{
					if (!array_key_exists($arCode["CODE"], $arOffer["PROPERTIES"]))
					{
						$arSkuTmp[] = GetMessage("EMPTY_VALUE_SKU");
						continue;
					}
					if (empty($arOffer["PROPERTIES"][$arCode["CODE"]]["VALUE"]))
						$arSkuTmp[] = GetMessage("EMPTY_VALUE_SKU");
					elseif (is_array($arOffer["PROPERTIES"][$arCode["CODE"]]["VALUE"]))
						$arSkuTmp[] = implode("/", $arOffer["PROPERTIES"][$arCode["CODE"]]["VALUE"]);
					else
						$arSkuTmp[] = $arOffer["PROPERTIES"][$arCode["CODE"]]["VALUE"];
				}
			}
			else
			{
				if (in_array("NAME", $arParams["OFFERS_FIELD_CODE"]))
					$arSkuTmp[] = $arOffer["NAME"];
				else
					break;
			}
			$arSkuTmp["ID"] = $arOffer["ID"];
			if (is_array($arOffer["PRICES"][$basePriceTypeName]))
			{
				if ($arOffer["PRICES"][$basePriceTypeName]["DISCOUNT_VALUE"] < $arOffer["PRICES"][$basePriceTypeName]["VALUE"])
				{
					$arSkuTmp["PRICE"] = $arOffer["PRICES"][$basePriceTypeName]["PRINT_VALUE"];
					$arSkuTmp["DISCOUNT_PRICE"] = $arOffer["PRICES"][$basePriceTypeName]["PRINT_DISCOUNT_VALUE"];
				}
				else
				{
					$arSkuTmp["PRICE"] = $arOffer["PRICES"][$basePriceTypeName]["PRINT_VALUE"];
					$arSkuTmp["DISCOUNT_PRICE"] = "";
				}
			}
			if (CModule::IncludeModule('sale'))
			{
				$dbBasketItems = CSaleBasket::GetList(
					array(
						"ID" => "ASC"
					),
					array(
						"PRODUCT_ID" => $arOffer['ID'],
						"FUSER_ID" => CSaleBasket::GetBasketUserID(),
						"LID" => SITE_ID,
						"ORDER_ID" => "NULL",
					),
					false,
					false,
					array()
				);
				$arSkuTmp["CART"] = "";
				if ($arBasket = $dbBasketItems->Fetch())
				{
					if($arBasket["DELAY"] == "Y")
						$arSkuTmp["CART"] = "delay";
					elseif ($arBasket["SUBSCRIBE"] == "Y" &&  $arNotify[SITE_ID]['use'] == 'Y')
						$arSkuTmp["CART"] = "inSubscribe";
					else
						$arSkuTmp["CART"] = "inCart";
				}
			}
			$arSkuTmp["CAN_BUY"] = $arOffer["CAN_BUY"];
			$arSkuTmp["ADD_URL"] = htmlspecialcharsback($arOffer["ADD_URL"]);
			$arSkuTmp["SUBSCRIBE_URL"] = htmlspecialcharsback($arOffer["SUBSCRIBE_URL"]);
			$arSkuTmp["COMPARE"] = "";
			if (isset($_SESSION[$arParams["COMPARE_NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$arOffer["ID"]]))
				$arSkuTmp["COMPARE"] = "inCompare";
			$arSkuTmp["COMPARE_URL"] = htmlspecialcharsback($arOffer["COMPARE_URL"]);
			$arSku[] = $arSkuTmp;
/* -- SKU*/
		}
		if ($minItemPrice > 0)
		{
			$arResult["ITEMS"][$cell]["MIN_PRODUCT_OFFER_PRICE"] = $minItemPrice;
			$arResult["ITEMS"][$cell]["MIN_PRODUCT_OFFER_PRICE_PRINT"] = $minItemPriceFormat;
		}
		if ((!is_array($arIblockOfferProps2) || empty($arIblockOfferProps2)) && is_array($arSku) && !empty($arSku))
			$arIblockOfferProps2[] = array("CODE" => "TITLE", "NAME" => GetMessage("CATALOG_OFFER_NAME"));
		$arResult["ITEMS"][$cell]["SKU_ELEMENTS"] = $arSku;
		$arResult["ITEMS"][$cell]["SKU_PROPERTIES"] = $arIblockOfferProps2;
	}
}
foreach ($arResult["ITEMS"] as $key => $arItem) {

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
	if ($arParams['SEF_FOLDER'] != '/catalog/'){
		$ar_detailPage = explode('/', $arItem['DETAIL_PAGE_URL']);
		$arResult["ITEMS"][$key]['DETAIL_PAGE_URL'] = '/';
		foreach ($ar_detailPage as $keydetail => $detailPage) {
			if ($keydetail > 1) {
				$arResult["ITEMS"][$key]['DETAIL_PAGE_URL'] .= $detailPage.($keydetail != count($ar_detailPage) -1 ? '/' : '');
			}
		}
	}
}
aspro::addBasketProps($arResult["ITEMS"], $arParams['IBLOCK_ID'], $arParams["PRODUCT_PROPERTIES"]);


$arResult["GRID"]["HEADERS"] = array (	//array ( 'id' => 'DELETE', 'name' => '', 'SORT' => 100 ), 
										array ( 'id' => 'NAME', 'name' => '', 'SORT' => 100 ),
										array ( 'id' => 'QUANTITY', 'name' => '', 'SORT' => 200 ),
										array ( 'id' => 'PRICE', 'name' => '', 'SORT' => 600 ),
										array ( 'id' => 'SUMM', 'name' => '', 'SORT' => 800 ),
										array ( 'id' => 'DELAY', 'name' => '', 'SORT' => 1000), 
					);
usort($arResult["GRID"]["HEADERS"], 'CKShop::cmpBySort');




$arGrid = array();
foreach ($arResult["ITEMS"] as $key => $arItem)
{
	$arGrid[$arItem["ID"]] = $arItem;
}	

global $wishListFilter;				
foreach ($wishListFilter["ID"] as $key => $product_id)
{	
	$arProduct = $arGrid[$product_id];
	$arProduct["PRODUCT_ID"] = $product_id;
	$arProduct["QUANTITY"] = ( intval($arParams["ITEMS"][$product_id]) ? intval($arParams["ITEMS"][$product_id]) : 1 );
	$arProduct["MEASURE_TEXT"] = "шт.";
	$arResult["GRID"]["ROWS"][] = $arProduct;
}


foreach ($arResult["GRID"]["ROWS"] as $key => $arItem) 
{
	foreach ($arItem["DISPLAY_PROPERTIES"] as $prop_code => $arProperty)
	{
		$arResult["GRID"]["ROWS"][$key]['PROPS_FORMAT'][$prop_code]['NAME'] = $arProperty['NAME'];
		$arResult["GRID"]["ROWS"][$key]['PROPS_FORMAT'][$prop_code]['VALUE'] = $arProperty['VALUE'];
	}	
}
	


















$this->__component->SetResultCacheKeys(array("IDS"));
$this->__component->SetResultCacheKeys(array("DELETE_COMPARE_URLS"));
?>