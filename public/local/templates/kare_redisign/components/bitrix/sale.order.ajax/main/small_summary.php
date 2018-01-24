<?/**
 * @var array $arResult
 * @var array $arParams
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}?>
<div class="bordered-block total-block">
	<div class="total-block__head two-sides">
		<div>
			Ваш заказ
		</div>
		<div>
			<a href="<?=$arParams['PATH_TO_BASKET']?>">Изменить</a>
		</div>
	</div>
	<div class="two-sides">
		<div>Товаров (<?=$arResult['SMALL_SUMMARY']['NUMBER_ITEMS']?>)</div>
		<div><?=$arResult['ORDER_PRICE_FORMATED']?></div>
	</div>
	<div class="two-sides">
		<div>Доставка</div>
		<div>
			<span class="gray-text"><?=$arResult['SMALL_SUMMARY']['DELIVERY']?></span>
		</div>
	</div>
	<div class="two-sides total-block__sum">
		<div>Итого к оплате:</div>
		<div><?=$arResult['ORDER_TOTAL_PRICE_FORMATED']?></div>
	</div>
</div>