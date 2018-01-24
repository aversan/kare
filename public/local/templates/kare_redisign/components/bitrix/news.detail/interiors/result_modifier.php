<?php
/**
 * @var $arResult array
 * @var $arParams array
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$CFile = new CFile();

foreach ($arResult['PROPERTIES']['GALLERY']['VALUE'] as $key => $photoId) {
	$photoSmall = $CFile->ResizeImageGet(
		$photoId,
		[
			'width' => 1000,
			'height' => 10000,
		],
		BX_RESIZE_IMAGE_PROPORTIONAL_ALT
	);
	$photoBig = $CFile->GetFileArray($photoId);
	$arResult['GALLERY']['SMALL'][$key] = $photoSmall['src'];
	$arResult['GALLERY']['BIG'][$key] = $photoBig['SRC'];
}

$arResult['CATALOG_IBLOCK_ID'] = \Wlbl\Tools\Iblock\Iblock::getId('aspro_kshop_catalog_s1');
global $interiorsFilter;
$interiorsFilter = [
	'ID' => $arResult['PROPERTIES']['CATALOG_ITEMS']['VALUE']
];
$arResult['FILTER_NAME'] = 'interiorsFilter';