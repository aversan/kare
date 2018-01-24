<?php
/**
 * @var $arResult array
 * @var $arParams array
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$CFile = new CFile();
$item = [];
list($item['COORDINATES']['lat'], $item['COORDINATES']['long']) = explode(',', $arResult['PROPERTIES']['COORDINATES']['VALUE']);
$item['ADDRESS'] = $arResult['PROPERTIES']['ADDRESS']['VALUE'];
$item['PIC_SRC'] = $arResult['PREVIEW_PICTURE']['SRC'];
$item['NAME'] = $arResult['NAME'];
$arResult['COORDINATES'] = json_encode($item);

foreach ($arResult['PROPERTIES']['PHOTOS']['VALUE'] as $key => $photoId) {
	$photoSmall = $CFile->ResizeImageGet(
		$photoId,
		[
			'width' => 430,
			'height' => 430,
		],
		BX_RESIZE_IMAGE_PROPORTIONAL_ALT
	);
	$photoBig = $CFile->ResizeImageGet(
		$photoId,
		[
			'width' => 1000,
			'height' => 1000,
		],
		BX_RESIZE_IMAGE_PROPORTIONAL_ALT
	);
	$arResult['PHOTOS']['SMALL'][$key] = $photoSmall['src'];
	$arResult['PHOTOS']['BIG'][$key] = $photoBig['src'];
}