<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$basePriceType = CCatalogGroup::GetBaseGroup();
$basePriceTypeName = $basePriceType["NAME"];

$arOffersIblock = CIBlockPriceTools::GetOffersIBlock($arResult["IBLOCK_ID"]);
$OFFERS_IBLOCK_ID = is_array($arOffersIblock) ? $arOffersIblock["OFFERS_IBLOCK_ID"]: 0;
if($OFFERS_IBLOCK_ID > 0){
	$dbOfferProperties = CIBlock::GetProperties($OFFERS_IBLOCK_ID, array(), array("!XML_ID" => "CML2_LINK"));
	$arIblockOfferProps = array();
	$offerPropsExists = false;
	while($arOfferProperties = $dbOfferProperties->Fetch()){
		if(!in_array($arOfferProperties["CODE"], $arParams["OFFERS_PROPERTY_CODE"]))
			continue;
		$arIblockOfferProps[] = array("CODE" => $arOfferProperties["CODE"], "NAME" => $arOfferProperties["NAME"]);
		$arIblockOfferProps2[] = array("CODE" => "SKU_".$arOfferProperties["CODE"], "NAME" => $arOfferProperties["NAME"]);
		$offerPropsExists = true;
	}
	$arResult["POPUP_MESS"] = array(
		"addToCart" => GetMessage("CATALOG_ADD_TO_CART"),
		"inCart" => GetMessage("CATALOG_IN_CART"),
		"delayCart" => GetMessage("CATALOG_IN_CART_DELAY"),
		"subscribe" =>  GetMessage("CATALOG_SUBSCRIBE"),
		"inSubscribe" =>  GetMessage("CATALOG_IN_SUBSCRIBE"),
		"notAvailable" =>  GetMessage("CATALOG_NOT_AVAILABLE"),
		"addCompare" => GetMessage("CATALOG_COMPARE"),
		"inCompare" => GetMessage("CATALOG_IN_COMPARE"),
		"chooseProp" => GetMessage("CATALOG_CHOOSE"),
	);
}

$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
$arNotify = unserialize($notifyOption);
foreach($arResult["ITEMS"] as $cell => $arElement){
	$arResult["ITEMS"][$cell]["DELETE_COMPARE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_LIST&id=".$arElement["ID"], array("action", "id")));
	if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])){ //Product has offers
		$arSku = array();
		$minItemPrice = 0;
		$minItemPriceFormat = "";
		$arResult["ITEMS"][$cell]["OFFERS_CATALOG_QUANTITY"] = 0;
		foreach($arElement["OFFERS"] as $arOffer){
			$arResult["ITEMS"][$cell]["OFFERS_CATALOG_QUANTITY"]  += $arOffer["CATALOG_QUANTITY"];
			foreach($arOffer["PRICES"] as $code => $arPrice){
				if($arPrice["CAN_ACCESS"]){
					if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]){
						$minOfferPrice = $arPrice["DISCOUNT_VALUE"];
						$minOfferPriceFormat = $arPrice["PRINT_DISCOUNT_VALUE"];
					}
					else{
						$minOfferPrice = $arPrice["VALUE"];
						$minOfferPriceFormat = $arPrice["PRINT_VALUE"];
					}
					
					if($minItemPrice > 0 && $minOfferPrice < $minItemPrice){
						$minItemPrice = $minOfferPrice;
						$minItemPriceFormat = $minOfferPriceFormat;
					}
					elseif($minItemPrice == 0){
						$minItemPrice = $minOfferPrice;
						$minItemPriceFormat = $minOfferPriceFormat;
					}
				}
			}
			$arSkuTmp = array();
			if($offerPropsExists){
				foreach($arIblockOfferProps as $key2 => $arCode){
					if(!array_key_exists($arCode["CODE"], $arOffer["PROPERTIES"])){
						$arSkuTmp[] = GetMessage("EMPTY_VALUE_SKU");
						continue;
					}
					if(empty($arOffer["PROPERTIES"][$arCode["CODE"]]["VALUE"])){
						$arSkuTmp[] = GetMessage("EMPTY_VALUE_SKU");
					}
					elseif(is_array($arOffer["PROPERTIES"][$arCode["CODE"]]["VALUE"])){
						$arSkuTmp[] = implode("/", $arOffer["PROPERTIES"][$arCode["CODE"]]["VALUE"]);
					}
					else{
						$arSkuTmp[] = $arOffer["PROPERTIES"][$arCode["CODE"]]["VALUE"];
					}
				}
			}
			else{
				if(in_array("NAME", $arParams["OFFERS_FIELD_CODE"])){
					$arSkuTmp[] = $arOffer["NAME"];
				}
			}
			$arSkuTmp["ID"] = $arOffer["ID"];
			if(is_array($arOffer["PRICES"][$basePriceTypeName])){
				if($arOffer["PRICES"][$basePriceTypeName]["DISCOUNT_VALUE"] < $arOffer["PRICES"][$basePriceTypeName]["VALUE"]){
					$arSkuTmp["PRICE"] = $arOffer["PRICES"][$basePriceTypeName]["PRINT_VALUE"];
					$arSkuTmp["DISCOUNT_PRICE"] = $arOffer["PRICES"][$basePriceTypeName]["PRINT_DISCOUNT_VALUE"];
				}
				else{
					$arSkuTmp["PRICE"] = $arOffer["PRICES"][$basePriceTypeName]["PRINT_VALUE"];
					$arSkuTmp["DISCOUNT_PRICE"] = "";
				}
			}
			if(CModule::IncludeModule('sale')){
				$dbBasketItems = CSaleBasket::GetList(array("ID" => "ASC"),	array("PRODUCT_ID" => $arOffer['ID'], "FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array());
				$arSkuTmp["CART"] = "";
				if($arBasket = $dbBasketItems->Fetch()){
					if($arBasket["DELAY"] == "Y"){
						$arSkuTmp["CART"] = "delay";
					}
					elseif($arBasket["SUBSCRIBE"] == "Y" &&  $arNotify[SITE_ID]['use'] == 'Y'){
						$arSkuTmp["CART"] = "inSubscribe";
					}
					else{
						$arSkuTmp["CART"] = "inCart";
					}
				}
			}
			$arSkuTmp["CAN_BUY"] = $arOffer["CAN_BUY"];
			$arSkuTmp["ADD_URL"] = htmlspecialcharsback($arOffer["ADD_URL"]);
			$arSkuTmp["SUBSCRIBE_URL"] = htmlspecialcharsback($arOffer["SUBSCRIBE_URL"]);
			$arSkuTmp["COMPARE"] = "";
			if(isset($_SESSION[$arParams["COMPARE_NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$arOffer["ID"]])){
				$arSkuTmp["COMPARE"] = "inCompare";
			}
			$arSkuTmp["COMPARE_URL"] = htmlspecialcharsback($arOffer["COMPARE_URL"]);
			$arSku[] = $arSkuTmp;
		}
		if($minItemPrice > 0){
			$arResult["ITEMS"][$cell]["MIN_PRODUCT_OFFER_PRICE"] = $minItemPrice;
			$arResult["ITEMS"][$cell]["MIN_PRODUCT_OFFER_PRICE_PRINT"] = $minItemPriceFormat;
		}
		if((!is_array($arIblockOfferProps2) || empty($arIblockOfferProps2)) && is_array($arSku) && !empty($arSku)){
			$arIblockOfferProps2[] = array("CODE" => "TITLE", "NAME" => GetMessage("CATALOG_OFFER_NAME"));
		}
		$arResult["ITEMS"][$cell]["SKU_ELEMENTS"] = $arSku;
		$arResult["ITEMS"][$cell]["SKU_PROPERTIES"] = $arIblockOfferProps2;
	}
	else{
		$arPrice = $arElement["PRICES"][$basePriceTypeName];
		if($arPrice["CAN_ACCESS"]){
			if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]){
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_PRICE"] = $arPrice["VALUE"];
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_DISCOUNT_PRICE"] = $arPrice["DISCOUNT_VALUE"];
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_PRICE_PRINT"] = $arPrice["PRINT_VALUE"];
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_DISCOUNT_PRICE_PRINT"] = $arPrice["PRINT_DISCOUNT_VALUE"];
			}
			else{
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_PRICE"] = $arPrice["VALUE"];
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_PRICE_PRINT"] = $arPrice["PRINT_VALUE"];
			}
		}
	}
}

// cache hack to use items list in component_epilog.php
$this->__component->arResult["IDS"] = array();
$this->__component->arResult["DELETE_COMPARE_URLS"] = array();

if(isset($arParams["DETAIL_URL"]) && strlen($arParams["DETAIL_URL"]) > 0){
	$urlTemplate = $arParams["DETAIL_URL"];
}
else{
	$urlTemplate = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "DETAIL_PAGE_URL");
}

//2 Sections subtree
$arSections = array();
$rsSections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "LEFT_MARGIN" => $arResult["LEFT_MARGIN"], "RIGHT_MARGIN" => $arResult["RIGHT_MARGIN"]), false, array("ID", "DEPTH_LEVEL", "SECTION_PAGE_URL"));
while($arSection = $rsSections->Fetch()){
	$arSections[$arSection["ID"]] = $arSection;
}

foreach($arResult["ITEMS"] as $key => $arElement_){
	$this->__component->arResult["IDS"][] = $arElement_["ID"];
	$this->__component->arResult["DELETE_COMPARE_URLS"][$arElement_["ID"]] = $arElement_["DELETE_COMPARE_URL"];
	
	if(is_array($arElement_["DETAIL_PICTURE"])){
		$arFilter = '';
		if($arParams["SHARPEN"] != 0){
			$arFilter = array("name" => "sharpen", "precision" => $arParams["SHARPEN"]);
		}
		$arFileTmp = CFile::ResizeImageGet($arElement_["DETAIL_PICTURE"], array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]), BX_RESIZE_IMAGE_PROPORTIONAL, true, $arFilter);
		$arResult["ITEMS"][$key]["PREVIEW_IMG"] = array("SRC" => $arFileTmp["src"], 'WIDTH' => $arFileTmp["width"], 'HEIGHT' => $arFileTmp["height"]);
	}
	
	$section_id = $arElement_["~IBLOCK_SECTION_ID"];

	if(array_key_exists($section_id, $arSections)){
		$urlSection = str_replace(array("#SECTION_ID#", "#SECTION_CODE#"), array($arSections[$section_id]["ID"], $arSections[$section_id]["CODE"]),	$urlTemplate);
		$arResult["ITEMS"][$key]["DETAIL_PAGE_URL"] = CIBlock::ReplaceDetailUrl($urlSection, $arElement_, true, "E");
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
			$arResult["ITEMS"][$key]['PICTURE']['BIG']['src'] = SITE_DIR.$sImageSrc;
			break;
		}
	}
	//endregion
		
	/*if ($arItem["DETAIL_PICTURE"]['ID'] && file_exists($_SERVER["DOCUMENT_ROOT"].$arItem["DETAIL_PICTURE"]["SRC"]) ) {
		$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]['ID'], array( "width" => 189, "height" => 189 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT,true );
		$imgBig = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]['ID'], array( "width" => 229, "height" => 229 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT,true );
		$arResult["ITEMS"][$key]['PICTURE']['SMALL'] = $img;
		$arResult["ITEMS"][$key]['PICTURE']['BIG'] = $imgBig;
	}elseif ($arItem["PREVIEW_PICTURE"]['ID'] && file_exists($_SERVER["DOCUMENT_ROOT"].$arItem["PREVIEW_PICTURE"]["SRC"])) {		
		$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]['ID'], array( "width" => 189, "height" => 189 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT,true );
		$imgBig = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]['ID'], array( "width" => 229, "height" => 229 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT,true );
		$arResult["ITEMS"][$key]['PICTURE']['SMALL'] = $img;
		$arResult["ITEMS"][$key]['PICTURE']['BIG'] = $imgBig;
	}
	else
	{
		$res = CIBlockElement::GetList(Array(), array("ID" => $arItem["ID"], "IBLOCK_ID" => $arItem["IBLOCK_ID"]), false, false, Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*"));
		while($ob = $res->GetNextElement())
		{ 
			$arProps = $ob->GetProperties();					
			for ($i=1; $i<20; $i++)
			{
				if (isset($arProps['KARTINKA'.$i]) && strlen($arProps['KARTINKA'.$i]['VALUE']) && file_exists($_SERVER["DOCUMENT_ROOT"]."/".$arProps['KARTINKA'.$i]["VALUE"]))
				{
					$file = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/".$arProps['KARTINKA'.$i]["VALUE"]);
					$fileID = CFile::SaveFile($file, "iblock");				
					
					$img = CFile::ResizeImageGet($fileID, array( "width" => 189, "height" => 189 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT,true );
					$imgBig = CFile::ResizeImageGet($fileID, array( "width" => 229, "height" => 229 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT,true );
					$arResult["ITEMS"][$key]['PICTURE']['SMALL'] = $img;
					$arResult["ITEMS"][$key]['PICTURE']['BIG'] = $imgBig;
						
					//$img = CFile::ResizeImageGet($fileID, array( "width" => 189, "height" => 189 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT,true );
					//$arResult["ITEMS"][$key]['PICTURE']['SMALL'] = $img;
					break;
				}
			}
		}
	}*/
	
	
	foreach ($arItem['PRICES'] as $arPrice) {
		if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]) {
			$arResult["ITEMS"][$key]['DISCOUNT_ICO'] = 'Y';
		}
	}
}
$rsElement=CIBlockElement::GetList(array(), array("PROPERTY_HIT" => 8, "IBLOCK_ID"=>$arParams['IBLOCK_ID'], "ACTIVE"=>"Y"), false, false, array("ID"));
while ($arElement=$rsElement->Fetch()){
	$arElements[] = $arElement;
}
aspro::addBasketProps($arResult["ITEMS"], $arParams['IBLOCK_ID'], $arParams["PRODUCT_PROPERTIES"]);
$arResult['ALL_COUNT'] = count($arElements);
$this->__component->SetResultCacheKeys(array("IDS"));
$this->__component->SetResultCacheKeys(array("DELETE_COMPARE_URLS"));
?>