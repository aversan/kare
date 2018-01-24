<?php
/** @var array $arParams */
/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

foreach ($arResult["ERRORS"] as $key => $error) {
	if (intval($key) == 0 && $key !== 0) {
		$arResult["ERRORS"][$key]
			= str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);
	}
}

ShowError(implode("<br />", $arResult["ERRORS"]));
