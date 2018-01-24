<?php
/** @var array $arParams */
/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$CSaleOrderPropsValue = new CSaleOrderPropsValue();
$CSalePaySystemAction = new CSalePaySystemAction();
$CFile = new CFile();

$arResult['WAIT_PAYMENT_STATUSES'] = ['B', 'C'];
$arResult['AJAX_CHANGE_RESERVE_DATE_URL'] = \Local\Config::get('ajax')['changeOrderReserveDate'];
$propDateReserveCode = 'DateRezerv';

$orderIds = [];
foreach ($arResult["ORDERS"] as $key => $arOrder) {
	if (IntVal($arOrder["ORDER"]["PAY_SYSTEM_ID"]) > 0 && $arOrder["ORDER"]["PAYED"] != "Y") {
		$dbPaySysAction = $CSalePaySystemAction->GetList(
			array(),
			array(
				"PAY_SYSTEM_ID" => $arOrder["ORDER"]["PAY_SYSTEM_ID"],
				"PERSON_TYPE_ID" => $arOrder["ORDER"]["PERSON_TYPE_ID"]
			),
			false,
			false,
			array("NAME", "ACTION_FILE", "NEW_WINDOW", "PARAMS", "ENCODING", "LOGOTIP")
		);

		if ($arPaySysAction = $dbPaySysAction->Fetch()) {
			$arPaySysAction["NAME"] = htmlspecialcharsEx($arPaySysAction["NAME"]);
			if (strlen($arPaySysAction["ACTION_FILE"]) > 0) {
				if ($arPaySysAction["NEW_WINDOW"] != "Y") {
					$CSalePaySystemAction->InitParamArrays(
						$arOrder["ORDER"],
						$arOrder["ORDER"]["ID"],
						$arPaySysAction["PARAMS"]
					);

					$pathToAction = $_SERVER["DOCUMENT_ROOT"].$arPaySysAction["ACTION_FILE"];

					$pathToAction = str_replace("\\", "/", $pathToAction);
					while (substr($pathToAction, strlen($pathToAction) - 1, 1) == "/") {
						$pathToAction = substr($pathToAction, 0, strlen($pathToAction) - 1);
					}

					if (file_exists($pathToAction)) {
						if (is_dir($pathToAction) && file_exists($pathToAction."/payment.php")) {
							$pathToAction .= "/payment.php";
						}

						$arPaySysAction["PATH_TO_ACTION"] = $pathToAction;
					}

					if (strlen($arPaySysAction["ENCODING"]) > 0) {
						define("BX_SALE_ENCODING", $arPaySysAction["ENCODING"]);
						AddEventHandler("main", "OnEndBufferContent", "ChangeEncoding");
						function ChangeEncoding($content)
						{
							global $APPLICATION;
							header("Content-Type: text/html; charset=".BX_SALE_ENCODING);
							$content = $APPLICATION->ConvertCharset($content, SITE_CHARSET, BX_SALE_ENCODING);
							$content = str_replace("charset=".SITE_CHARSET, "charset=".BX_SALE_ENCODING, $content);
						}
					}
				}
			}

			if ($arPaySysAction > 0) {
				$arPaySysAction["LOGOTIP"] = $CFile->GetFileArray($arPaySysAction["LOGOTIP"]);
			}

			$arResult["ORDERS"][$key]["ORDER"]["PAY_SYSTEM"] =  $arPaySysAction;
		}
	}
	$orderIds[$arOrder['ORDER']['ID']] = $key;
}

if (!empty($orderIds)) {
	$objProp = $CSaleOrderPropsValue->GetList(
		[
			'ID' => 'ASC',
		],
		[
			'ORDER_ID' => array_keys($orderIds),
			'CODE' => $propDateReserveCode,
		]
	);
	while ($property = $objProp->Fetch()) {
		if (in_array($arResult['ORDERS'][$orderIds[$property['ORDER_ID']]]['ORDER']['STATUS_ID'], $arResult['WAIT_PAYMENT_STATUSES'])
			&& ($arResult['ORDERS'][$orderIds[$property['ORDER_ID']]]['ORDER']['CANCELED'] == 'N')) {
			$arResult['ORDERS'][$orderIds[$property['ORDER_ID']]]['ORDER'][$propDateReserveCode] = ($property) ? $property : false;
		}
		$arResult['ORDERS'][$orderIds[$property['ORDER_ID']]]['AJAX_DATA'] = json_encode([
			'ID' => $arResult['ORDERS'][$orderIds[$property['ORDER_ID']]]['ORDER']['XML_ID'],
			'DateRezerv' => ($property) ? $property['VALUE'] : date('d.m.Y H:i:s')
		]);
	}
}
