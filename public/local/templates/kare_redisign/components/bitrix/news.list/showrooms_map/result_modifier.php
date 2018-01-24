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

$arResult['SHOWROOMS'] = json_encode($arResult['SHOWROOMS']);