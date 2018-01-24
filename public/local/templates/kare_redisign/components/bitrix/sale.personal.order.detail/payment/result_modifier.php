<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/**
 * @var array $arResult
 * @var array $arParams
*/

use Local\Config;

$CCurrency = new CCurrency();

$CSaleOrderPropsValue = new CSaleOrderPropsValue();
$prop = $CSaleOrderPropsValue->GetList(
	false,
	[
		'ORDER_ID' => $arResult['ID'],
		'CODE' => 'FriendlyBonusDirection'
	]
)->Fetch();

if(isset($_POST['bonusPaymentEnd'])){

    $client = new \SoapClient(
        \Wlbl\Config\Config::get('url_1c'),
        [
            'login' => \Wlbl\Config\Config::get('login_1c'),
            'password' => \Wlbl\Config\Config::get('pass_1c'),
        ]
    );

    $response = $client->KareOrderUpdatePayment(
        [
            'ID' => $arResult['XML_ID'],
            'BonusPaidSum' => 0,
            'TransactionID' => 0,
            'PaidSum' => 0,
            'FriendlyBonusDirection' => ''
        ]
    );

    if ($response) {
        LocalRedirect("/personal/history-of-orders/KARE".$arResult['ID']."/");
    }

}

$arResult['AJAX_BONUS_PAYMENT'] = Config::get('ajax')['bonusPayment'];
$arResult['AJAX_SET_BONUS_DIRECTION'] = Config::get('ajax')['bonusDirection'];
$arResult['SITE_TEMPLATE_PATH'] = SITE_TEMPLATE_PATH;
$baseCurrenecy = $CCurrency->GetBaseCurrency();
$arResult['FINAL_PRICE'] = CurrencyFormat($arResult['PRICE'] - $arResult['SUM_PAID'], $baseCurrenecy);
$arResult['FORMATED_SUM_PAID'] = CurrencyFormat($arResult['SUM_PAID'], $baseCurrenecy);
$arResult['REQUEST_BONUS_INFO'] = json_encode([
	'url' => Config::get('ajax')['getBonusInfo'],
	'data' => [
		'UserXmlID' => $arResult['USER']['XML_ID'],
	]
]);
$arResult['CHECKED_DIRECTION'] = ($prop['VALUE'] == 'goldfish') ? true : false;
$arResult['ENCODED_PROP_ID'] = base_convert($prop['ID'], 10, 9);
$arResult['PAYMENT_LINK'] = $arParams["PATH_TO_PAYMENT"] . '?ORDER_ID=' . urlencode(urlencode($arResult["ACCOUNT_NUMBER"]));
$arResult['PDF_EXISTS'] = false;

if ($arResult['PAY_SYSTEM']['NEW_WINDOW'] == 'Y') {
	if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE'])){
		$arResult['PDF_EXIST'] = true;
		$arResult['PAYMENT_LINK_PDF'] = $arResult['PAYMENT_LINK'] . '&pdf=1&DOWNLOAD=Y';
	}
} else {
	$CSalePaySystemAction = new CSalePaySystemAction();
	$CSalePaySystemAction->InitParamArrays($arResult, $arResult["ID"], $arResult["PAY_SYSTEM"]["PARAMS"]);
	if (strlen($arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"])>0) {
		ob_start();
		include $arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"];
		$arResult['PAY_BUTTON'] = ob_get_contents();
		ob_end_clean();
	}
}
