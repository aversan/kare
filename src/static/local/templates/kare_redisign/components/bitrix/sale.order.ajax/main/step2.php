<?php
/**
 * @var array $arResult
 * @var array $arParams
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
?>
<div style="display:none"">
	<? foreach ($arResult['POST_FOR_step2'] as $key => $value) { ?>
		<input type="hidden" name="<?=$key?>" id="<?=$key?>" value="<?=$value?>" />
	<?}?>
</div>
<? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/summary.php");?>