<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div style="display: none" class="bordered-block bordered-block--ind">
	<div class="bordered-block__title bordered-block__title--payway">Способы оплаты</div>
	<? foreach($arResult["PAY_SYSTEM"] as $arPaySystem) { ?>
		<input type="hidden" name="PAY_SYSTEM_ID" id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" value="<?=$arPaySystem["ID"]?>">
	<? } ?>
	<div class="payments">
		<div class="payments__item">
			<div class="payments__title">​Банковские карты</div>
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/mir.svg" width="40" height="11" alt="">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/visa.svg" width="40" height="12" alt="">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/maestro.svg" width="33" height="20" alt="">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/mc.svg" width="33" height="20" alt="">
		</div>
		<div class="payments__item">
			<div class="payments__title">Электронные деньги</div>
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/yandex.svg" width="18" height="24" alt="">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/webmoney.svg" width="20" height="20" alt="">
		</div>
		<div class="payments__item">
			<div class="payments__title">​Интернет-банкинг</div>
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/alfa.svg" width="18" height="26" alt="">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/psb.svg" width="20" height="20" alt="">
		</div>
		<div class="payments__item">
			<div class="payments__title">Наличные</div>
			<img src="<?=SITE_TEMPLATE_PATH?>/images/order/payments/cash-new.svg" width="22" height="20" alt="">
		</div>
	</div>
</div>
<div class="bordered-block bordered-block--ind">
        <div class="bordered-block__title bordered-block__title--fr-bonus">Дружные бонусы</div>
        <p>В результате оформления заказа, вам будут начислены “Дружные бонусы”.
            Вы можете потратить их в одном из магазинов партнеров:</p>
        <div class="pay-block__bonus pay-block__bonus-pic">
            <div class="pay-block__ind">
                <div class="custom-checkbox">
                    <input type="radio" name="ORDER_PROP_19" id="friends_bonus1" class="js-radio-friendly" value="goldfish" checked>
                    <label for="friends_bonus1">
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/gold-fish-logo.jpg" alt="Золотая рыбка">
                    </label>
                </div>
                <div><a href="http://au74.ru/" target="_blank">Ювелирные украшения</a></div>
            </div>
            <div class="pay-block__ind">
                <div class="custom-checkbox">
                    <input type="radio" name="ORDER_PROP_19" id="friends_bonus2" class="js-radio-friendly" value="ican">
                    <label for="friends_bonus2">
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/ican-logo.jpg" alt="Ican">
                    </label>
                </div>
                <div><a href="http://ican-center.ru/" target="_blank">Товары для здоровья и спорта</a></div>
            </div>
        </div>
</div>
