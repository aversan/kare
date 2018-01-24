<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"]))
{ ?>
	<div class="confirm-order">
		<div class="base-text custom-input-block--ind"><?=GetMessage("SOA_TEMPL_ORDER_COMPLETE")?></div>
		<div class="two-sides custom-input-block--ind">
			<div>
				<p class="gray-text">Номер заказа</p>
				<p><?=$arResult["ORDER"]["ACCOUNT_NUMBER"]?></p>
			</div>
			<div>
				<p class="gray-text">Дата оформления</p>
				<p><?=$arResult["ORDER"]["DATE_INSERT"]?></p>
			</div>
		</div>
		<p class="gray-text">На почтовый адрес <a href="mailto:<?=$arResult["ORDER"]['USER_EMAIL']?>"><?=$arResult["ORDER"]['USER_EMAIL']?></a> отправлена копия заказа.</p>
		<div class="gray-text custom-input-block--ind">Отследить состояние заказа можно в вашем <a href="/personal/">личном кабинете.</a></div>
		<div class="two-sides">
			<div><a href="<?=$arParams["PATH_TO_PERSONAL"] . $arResult["ORDER"]["ACCOUNT_NUMBER"] . '/?payment=Y'?>" class="butn btn-base">оплатить заказ</a></div>
			<div><a href="/">Перейти на главную</a></div>
		</div>
	</div>
<? } else { ?>
	<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>
	<?
}
?>
