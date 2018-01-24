<?php
/**
 * @var $arResult array
 * @var $arParams array
 * @var CBitrixComponentTemplate $this
*/
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$CFile = new CFile();

foreach ($arResult['ITEMS'] as &$item) {
	$this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	$item['EDIT_ID'] = $this->GetEditAreaId($item['ID']);
	$item['PREVIEW_PICTURE'] = $CFile->ResizeImageGet(
		$item['PREVIEW_PICTURE'],
		[
			'width' => 100000,
			'height' => 400,
		],
		BX_RESIZE_IMAGE_PROPORTIONAL_ALT
	);
	$item['FILE_ARRAY'] = $CFile->GetFileArray($item['PROPERTIES']['FILE']['VALUE']);
}


$arResult['PAGES_COUNT'] = $arResult['NAV_RESULT']->NavPageCount;
$arResult['PAGE_NUMBER'] = $arResult['NAV_RESULT']->NavNum;
$arResult['NEXT_PAGE_NUM'] = $arResult['NAV_RESULT']->NavPageNomer;
$url = $APPLICATION->GetCurPage();
$arResult['AJAX_URI'] = $url;