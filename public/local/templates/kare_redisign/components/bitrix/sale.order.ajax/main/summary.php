<?php
/**
 * @var array $arResult
 * @var array $arParams
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
<div class="bordered-block">
	<div class="two-sides two-sides--title">
		<div>
			<div class="bordered-block__title bordered-block__title--contents">Состав заказа</div>
		</div>
		<div>
			<a href="<?=$arParams['PATH_TO_BASKET']?>">Изменить</a>
		</div>
	</div>
	<div class="cart__table-overflow">
		<table class="preorder__table">
			<thead>
				<tr>
					<th class="cart__num"></th>
					<th class="cart__pic"></th>
					<th class="cart__name">Название</th>
					<th class="cart__count-small">Количество</th>
					<th class="cart__price-small">Цена</th>
					<th class="cart__price-small">Сумма</th>
				</tr>
			</thead>

			<tbody>
				<?foreach ($arResult["BASKET_ITEMS"] as $k => $item):?>
				<tr>
					<td class="cart__num"><?=$k+1?>.</td>
					<td class="cart__pic">
						<a href="<?=$item['DETAIL_PAGE_URL']?>" class="cart__img">
							<img src="<?=$item['PHOTO']?>" alt="<?=$item['NAME']?>">
						</a>
					</td>
					<td class="cart__name">
						<a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a>
						<div class="cart__info">
							<?foreach ($item['PROPS'] as $prop) :?>
								<span><?=$prop['NAME']?> - <?=$prop['VALUE']?></span><br>
							<?endforeach;?>
						</div>
					</td>
					<td class="cart__count-small"><?=$item['QUANTITY']?> <?=$item['MEASURE_TEXT']?></td>
					<td class="cart__price-small"><?=$item['PRICE_FORMATED']?></td>
					<td class="cart__price-small"><?=$item['SUM']?></td>
				</tr>
				<?endforeach;?>
			</tbody>
		</table>
	</div>
</div>

<div class="bordered-block bordered-block--ind">
	<div class="bordered-block__title bordered-block__title--delivery">Получатель</div>
	<div class="cart__info">
		<p><?=$arResult['DELIVERY']['NAME']?></p>
	</div>
	<div class="cart__info">
		<span><?=$arResult['SUMMARY']['LOCATION']['NAME']?></span>
		<p><?=$arResult['SUMMARY']['LOCATION']['VALUE']?></p>
	</div>
	<div class="cart__info">
		<span><?=$arResult['SUMMARY']['CUSTOMER_FULL_NAME']['NAME']?></span>
		<p><?=$arResult['SUMMARY']['CUSTOMER_FULL_NAME']['VALUE']?></p>
	</div>
	<div class="cart__info">
		<span><?=$arResult['SUMMARY']['CustomerPhone']['NAME']?></span>
		<p><?=$arResult['SUMMARY']['CustomerPhone']['VALUE']?></p>
	</div>
	<div class="cart__info">
		<div class="three-cols">
			<div>
				<span><?=$arResult['SUMMARY']['STREET']['NAME']?></span>
				<p><?=$arResult['SUMMARY']['STREET']['VALUE']?></p>
			</div>
			<div>
				<span><?=$arResult['SUMMARY']['HOUSE']['NAME']?></span>
				<p><?=$arResult['SUMMARY']['HOUSE']['VALUE']?></p>
			</div>
			<div>
				<span><?=$arResult['SUMMARY']['FLAT']['NAME']?></span>
				<p><?=$arResult['SUMMARY']['FLAT']['VALUE']?></p>
			</div>
		</div>

	</div>
	<div class="cart__info">
		<span><?=$arResult['SMALL_SUMMARY']['DELIVERY']?></span>
	</div>
</div>

<div class="two-sides two-sides--bord">
<div class="bordered-block bordered-block--ind">
	<div class="bordered-block__title bordered-block__title--delivery">Покупатель</div>
	<div class="cart__info">
		<span><?=$arResult['SUMMARY']['BUYER_FULL_NAME']['NAME']?></span>
		<p><?=$arResult['SUMMARY']['BUYER_FULL_NAME']['VALUE']?></p>
	</div>
	<div class="cart__info">
		<span><?=$arResult['SUMMARY']['USER_LOGIN']['NAME']?></span>
		<p><?=$arResult['SUMMARY']['USER_LOGIN']['VALUE']?></p>
	</div>
	<div class="cart__info">
		<span><?=$arResult['SUMMARY']['EMAIL']['NAME']?></span>
		<p><?=$arResult['SUMMARY']['EMAIL']['VALUE']?></p>
	</div>
</div>
<div class="bordered-block bordered-block--ind">
	<div class="bordered-block__title bordered-block__title--seller">Продавец</div>
	<span class="gray-text gray-text--lh">Общество с ограниченной ответственностью «Вариант - Д»<br>
				ИНН 7448150107<br>
				КПП 744801001<br>
				454138, Челябинская обл., Челябинск г., пр. Победы, д.290, оф. 210, тел.: +7 (351) 218-60-00, 218-60-01, 218-60-02<br>
				р/с40702810304000005782, в банке УРАЛЬСКИЙ ФИЛИАЛ АО "РАЙФФАЙЗЕНБАНК", БИК 046577906<br>
				к/с 30101810100000000906</span>
</div>
</div>