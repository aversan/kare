<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("inlineform-block".$arParams["WEB_FORM_ID"]);?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();
	?>
<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("inlineform-block".$arParams["WEB_FORM_ID"], "");?>