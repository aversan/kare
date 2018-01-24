<? /**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 */
use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if (!empty($arResult['ITEMS']['AnDelCanBuy'])) {
	$CFile = new CFile();
	$i = 0;
	$listLength = 3;	// количество отображаемых товаров
	$items = [];
	sortByColumn($arResult['ITEMS']['AnDelCanBuy'], ['ID' => SORT_DESC]);
	foreach ($arResult['ITEMS']['AnDelCanBuy'] as $key => &$arItem) {

		if (!empty($arItem['PREVIEW_PICTURE'])) {
			$arFileTmp = $CFile->ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 120, 'height' => 1000], BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arItem['PREVIEW_PICTURE'] = $arFileTmp;
		}

		if ($i < $listLength) {
			$items[$arItem['PRODUCT_ID']] = $arItem;
		} else {
			break;
		}
		$i++;
	}
	$arResult['RESULT_ITEMS'] = $items;

	Loader::includeModule('catalog');
	if (!empty($items)) {

		$objStores = CCatalogStore::GetList(
			[
				'SORT' => 'ASC'
			],
			[
				'ACTIVE' => 'Y',
				'SITE_ID' => SITE_ID
			],
			false,
			false,
			[
				'ID'
			]
		);
		$storeIds = [];
		while ($store = $objStores->Fetch()) {
			$storeIds[] = $store['ID'];
		}
		unset($store, $objStores);

		$itemsProductIds = array_keys($items);
		$objStoreProducts = CCatalogStoreProduct::GetList(
			[
				'SORT' => 'ASC',
			],
			[
				'PRODUCT_ID' => $itemsProductIds,
				'STORE_ID' => $storeIds,
			]
		);

		$storesInfo = [];
		while ($store = $objStoreProducts->Fetch()) {
			if ($store['AMOUNT'] > 0) {
				$arResult['RESULT_ITEMS'][$store['PRODUCT_ID']]['STORES'][] = $store;
				if (in_array($store['PRODUCT_ID'], $itemsProductIds)) {
					$itemsProductIds = array_diff($itemsProductIds, [$store['PRODUCT_ID']]);
				}
			}
		}

		if (!empty($itemsProductIds)) {
			$CIblockElement = new CIBlockElement();
			$objItems = $CIblockElement->GetList(
				[
					'id' => 'asc'
				],
				[
					'ID' => $itemsProductIds
				],
				false,
				false,
				[
					'ID',
					'PROPERTY_VOZMOZHNAYA_DATA_OTGRUZKI_IZ_MYUNKHENA'
				]
			);
			while ($item = $objItems->Fetch()) {
				$arResult['RESULT_ITEMS'][$item['ID']]['AVAILABLE_DATE'] = $item['PROPERTY_VOZMOZHNAYA_DATA_OTGRUZKI_IZ_MYUNKHENA_VALUE'];
			}
		}

	}

	// fixme: broken catalog pictures
// картинки хранятся очень оригинально +
// (предположение) раньше многие поля были множественными, из-за чего все плохо

	if (!empty($items)) {
		$CIBlockElement = new CIBlockElement();
		$objElements = $CIBlockElement->GetList(
			[
				'ID' => 'ASC',
			],
			[
				'ID' => array_keys($items),
			],
			false,
			false,
			[
				'ID',
				'PROPERTY_MAIN_PHOTO',
			]
		);
		$checkArray = [];
		while ($item = $objElements->Fetch()) {
			if (in_array($item['ID'], $checkArray)) {
				continue;
			}
			$arResult['RESULT_ITEMS'][$item['ID']]['PHOTO'] = ($item['PROPERTY_MAIN_PHOTO_VALUE'][0] == '/') ? $item['PROPERTY_MAIN_PHOTO_VALUE'] : '/' . $item['PROPERTY_MAIN_PHOTO_VALUE'];
			$checkArray[] = $item['ID'];
		}
	}
// end broken catalog pictures
}

$countBasketItems = count($arResult['ITEMS']['AnDelCanBuy']);
$countWishItems = count($arResult['ITEMS']['DelDelCanBuy']);
$arResult['JSON'] = htmlentities(json_encode([
	'basket' => $countBasketItems,
	'wish' => $countWishItems,
]));
$arResult['LEAST_ITEMS'] = $countBasketItems - 3;