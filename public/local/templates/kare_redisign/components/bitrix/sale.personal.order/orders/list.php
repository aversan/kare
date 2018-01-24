<?php
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}?>

<h1><?=$APPLICATION->GetTitle()?></h1>
<?php $APPLICATION->IncludeComponent(
	"bitrix:menu",
	"left_menu",
	array(
		"ROOT_MENU_TYPE" => "left",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
	),
	false
);?>

<? $_REQUEST['show_all'] = 'Y';

$cache = new CPHPCache();
$cache_time = 0;
$cache_path = SITE_DIR . 'kshop_order_year/';
$cache_id = 'kshop_order_year';

if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_id, $cache_path)) {
	$res = $cache->GetVars();
	$arYear = $res['arYear'];
} else {
	$arYear = array();
	global $USER;
	$CSaleOrder = new CSaleOrder();
	$rsOrder = $CSaleOrder->GetList(
		[
			'DATE_INSERT' => 'DESC'
		],
		[
			'USER_ID' => $USER->GetID()
		]
	);
	while ($arOrder = $rsOrder->GetNext()) {
		$date = explode(' ', $arOrder['DATE_INSERT']);
		$year = explode('.', $date[0]);
		$arYear[$year[2]] = $year[2];
	}
	if (true) {
		$cache->StartDataCache($cache_time, $cache_id, $cache_path);
		$cache->EndDataCache(['arYear' => $arYear]);
	}
}

if (!empty($_REQUEST['filter_date_from'])) {
	$cur_date = explode('.', $_REQUEST['filter_date_from']);
	$cur_date = $cur_date[2];
} else {
	$cur_date = 'all';
}
?>
<div class="tab-content js-tab-content active" data-tab="history">
	<div class="history-ctrl js-history-ctrl">
		<a href="<?= $arParams['SEF_FOLDER'] ?>" <?= $cur_date == 'all' ? 'class="inner-link"' : '' ?>>
			<?= GetMessage('PERSONAL_ORDER_ALL') ?>
		</a>
		<?php
		foreach ($arYear as $year) {
			echo '<a href="' . $arParams["SEF_FOLDER"] . '?filter_date_from=01.01.' . $year . '&filter_date_to=31.12.'
				. $year . '&filter=Y"' . ($cur_date == $year ? ' class="inner-link"' : '') . '>' . $year . '</a>';
		}
		?>
	</div>
	<?php
	if (!$_REQUEST["filter_date_from"]) {
		$_REQUEST["del_filter"] = "Y";
	}
	?>
	<?php
	$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.order.list",
		$templateName,
		array(
			"PATH_TO_PAYMENT" => $arParams["PATH_TO_PAYMENT"],
			"PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
			"PATH_TO_CANCEL" => $arResult["PATH_TO_CANCEL"],
			"PATH_TO_COPY" => $arResult["PATH_TO_LIST"] . '?ID=#ID#',
			"PATH_TO_BASKET" => $arParams["PATH_TO_BASKET"],
			"SAVE_IN_SESSION" => $arParams["SAVE_IN_SESSION"],
			"ORDERS_PER_PAGE" => $arParams["ORDERS_PER_PAGE"],
			"SET_TITLE" => $arParams["SET_TITLE"],
			"ID" => $arResult["VARIABLES"]["ID"],
			"NAV_TEMPLATE" => $arParams["NAV_TEMPLATE"],
			"filter_date_from" => $_REQUEST["filter_date_from"],
			"filter_date_to" => $_REQUEST["filter_date_to"],
			"HISTORIC_STATUSES" => $arParams["HISTORIC_STATUSES"],
		),
		$component
	); ?>
</div>