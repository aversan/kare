<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	global $USER;
	if ($USER->IsAuthorized())
	{
		$rsUser = CUser::GetByID($USER->GetParam('USER_ID')); 
		$arUser = $rsUser->Fetch(); 		
		if (strlen(trim($arUser["NAME"]))) $arResult["NAME"] = trim($arUser["NAME"]);
		if (!$arResult["EMAIL"] && strlen(trim($arUser["NAME"]))) $arResult["EMAIL"] = trim($arUser["EMAIL"]);
		if (!$arResult["EMAIL"] && trim($_COOKIE["KARE_SUBSCRIBE_EMAIL"])) $arResult["EMAIL"] = trim($_COOKIE["KARE_SUBSCRIBE_EMAIL"]);
		
	}
	else
	{
		if (trim($_COOKIE["KARE_SUBSCRIBE_NAME"])) $arResult["NAME"] = trim($_COOKIE["KARE_SUBSCRIBE_NAME"]);
		if (!$arResult["EMAIL"] && trim($_COOKIE["KARE_SUBSCRIBE_EMAIL"])) $arResult["EMAIL"] = trim($_COOKIE["KARE_SUBSCRIBE_EMAIL"]);
	}
?>