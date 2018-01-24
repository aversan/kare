<?php
/**
 * @var $arResult array
 * @var $arParams array
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$CFile = new CFile();

$arResult['SHOWROOMS'] = [];
foreach ($arResult['ITEMS'] as $key => $item) {
	list($lat, $long) = explode(',', $item['PROPERTIES']['COORDINATES']['VALUE']);
	$arResult['SHOWROOMS'][$key]['COORDINATES']['lat'] = $lat;
	$arResult['SHOWROOMS'][$key]['COORDINATES']['long'] = $long;
	$arResult['SHOWROOMS'][$key]['NAME'] = $item['NAME'];
	$arResult['SHOWROOMS'][$key]['LINK'] = $item['DETAIL_PAGE_URL'];
	$arResult['SHOWROOMS'][$key]['CITY'] = $item['PROPERTIES']['CITY']['VALUE'];
	$arResult['SHOWROOMS'][$key]['ADDRESS'] = $item['PROPERTIES']['ADDRESS']['VALUE'];
	$img = $CFile->ResizeImageGet($item['PREVIEW_PICTURE'], array('width' => 90, 'height' => 90), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
	$arResult['SHOWROOMS'][$key]['PREVIEW_PIC'] = $img['src'];
}

if ($arParams['PARTNERS_IBLOCK_ID']){
	$arSort = array($arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']);
	$arFilter = array('IBLOCK_ID' => $arParams['PARTNERS_IBLOCK_ID'], 'PROPERTY_SHOW_IN_CONTACTS_VALUE' => 'Y');
	$arSelect = array('IBLOCK_ID', 'ID', 'DETAIL_PAGE_URL', 'NAME', 'PROPERTY_CITY', 'PROPERTY_ADDRESS', 'PROPERTY_COORDINATES');
	
	$res = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
	while($ar_fields = $res->GetNext()){
		$arItem = array();
		list($lat, $long) = explode(',', $ar_fields['PROPERTY_COORDINATES_VALUE']);
		$arItem['COORDINATES']['lat'] = $lat;
		$arItem['COORDINATES']['long'] = $long;
		$arItem['NAME'] = $ar_fields['NAME'];
		$arItem['LINK'] = $ar_fields['DETAIL_PAGE_URL'];
		$arItem['CITY'] = $ar_fields['PROPERTY_CITY_VALUE'];
		$arItem['ADDRESS'] = $ar_fields['PROPERTY_ADDRESS_VALUE']['TEXT'];
		$arItem['PARTNER'] = 'Y';
		
		$arResult['SHOWROOMS'][] = $arItem;
		unset($arItem);
	}
}

$arResult['SHOWROOMS'] = json_encode($arResult['SHOWROOMS']);