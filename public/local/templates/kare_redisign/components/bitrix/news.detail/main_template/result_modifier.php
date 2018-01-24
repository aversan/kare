<?php
/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
$CFile = new CFile();
if (!empty($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
	foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $picId) {
		$arResult['PREVIEW_PHOTOS'][$picId] = $CFile->ResizeImageGet(
			$picId,
			[
				'width' => 1000,
				'height' => 10000
			]
		);
		$arResult['DETAIL_PHOTOS'][$picId] = $CFile->GetFileArray($picId);
	}
}
$arResult['READ_MORE_BLOCK'] = [];
if (!empty($arResult['PROPERTIES']['READ_MORE']['VALUE'])) {
	$ids = array_slice($arResult['PROPERTIES']['READ_MORE']['VALUE'], 0, 3);
	$CIBlockElement = new CIBlockElement();
	$objItems = $CIBlockElement->GetList(
		[
			'ID' => 'ASC',
		],
		[
			'IBLOCK_ID' => $arResult['IBLOCK_ID'],
			'ID' => $ids,
		],
		false,
		false,
		[
			'ID',
			'NAME',
			'PREVIEW_PICTURE',
			'DETAIL_PAGE_URL'
		]
	);
	while ($item = $objItems->GetNext(false,false)) {
		$item['PREVIEW_PICTURE'] = $CFile->ResizeImageGet(
			$item['PREVIEW_PICTURE'],
			[
				'width' => 1000,
				'height' => 10000,
			]
		);
		$arResult['READ_MORE_BLOCK'][$item['ID']] = $item;
	}
}

$arResult['CATALOG_IBLOCK_ID'] = \Wlbl\Tools\Iblock\Iblock::getId('aspro_kshop_catalog_s1');
global $newsElementFilter;
$newsElementFilter = [
	'ID' => $arResult['PROPERTIES']['CATALOG_ITEMS']['VALUE']
];
$arResult['FILTER_NAME'] = 'newsElementFilter';