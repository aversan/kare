<?php
/**
 * @var $arResult array
 * @var $arParams array
 * @var CBitrixComponentTemplate $this
*/
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

// номер текущей страницы
$arResult["NAV"]["curPage"] = $curPage = $arResult["NAV_RESULT"]->NavPageNomer;

// всего страниц - номер последней страницы
$arResult["NAV"]["totalPages"] = intval($arResult["NAV_RESULT"]->NavPageCount);

// номер постраничной навигации на странице
$navNum = $arResult["NAV_RESULT"]->NavNum;

$arResult["NAV"]["nextPageUrl"] = $APPLICATION->GetCurPageParam('PAGEN_'.$navNum.'='.(intval($curPage+1)), array('PAGEN_'.$navNum), false);