<?php
/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
if (!(($arResult['CANCELED'] == 'Y') || ($arResult['PAYED'] == 'Y') || ($arResult['STATUS_ID'] == 'C'))) { ?>
	<div class="modal" style="display: none" id="cancel_order">
		<div class="">
			<form action="<?=$arResult['POST_FORM_ACTION_URI']?>" method="post">
				<?=bitrix_sessid_post()?>
				<input type="hidden" name="CANCEL" value="Y">
				<input type="hidden" name="ID" value="<?=$arResult['ID']?>">
				<p>Вы уверены что хотите отменить заказ №<?=$arResult['ID']?>?</p>
				<p><b>Отмена заказа не обратима!</b></p>
				<div class="custom-input-block">
					<textarea placeholder="Укажите, пожалуйста, причину отмены заказа" name="REASON_CANCELED" id="" class="custom-input"></textarea>
				</div>
				<input type="submit" value="Отменить заказ" name="action" id="" class="butn btn-base butn-center">
			</form>
		</div>
	</div>
<? } ?>