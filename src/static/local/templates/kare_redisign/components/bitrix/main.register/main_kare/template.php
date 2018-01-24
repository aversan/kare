<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

	<div class="js-registration">
<?
if (count($arResult["ERRORS"][0]) > 0) {
	//print_r($arResult["ERRORS"][0]);
	foreach ($arResult["ERRORS"] as $key => $error)
		if (intval($key) == 0 && $key !== 0)
			$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);

	ShowError(implode("<br />", $arResult["ERRORS"]));
}
?>

<? if (empty($arResult["ERRORS"]) && !empty($_POST["register_submit_button"])) {
	LocalRedirect(SITE_DIR . 'personal/');
} else { ?>

	<h1 class="main-catalog__title">Регистрация</h1>

	<div class="text-content text-content--narrow">
		<p>После регистрации на сайте вам будет доступно отслеживание состояния заказов, личный кабинет и другие новые
			возможности.</p>

		<script>
			$(document).ready(function () {
				$("form#registraion-page-form").validate({
					rules: {
						'REGISTER[EMAIL]': "email"
					}
				});
			})
		</script>

		<form id="registraion-page-form" method="post" action="<?= POST_FORM_ACTION_URI ?>" name="regform" enctype="multipart/form-data" class="<?=($arResult['AJAX'] == 'Y') ? 'js-auth-block' : ''?>">
			<? if ($arResult["BACKURL"] <> ''): ?>
				<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
			<? endif; ?>
			<? foreach ($arResult["SHOW_FIELDS"] as $FIELD): ?>
				<div class="custom-input-block">
					<? switch ($FIELD) {
						case "PASSWORD":
							?>
							<input size="30" type="password" name="REGISTER[<?= $FIELD ?>]" <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? 'required' : '' ?> value="<?= $arResult["VALUES"][$FIELD] ?>" autocomplete="off" class="bx-auth-input custom-input <?= (array_key_exists($FIELD, $arResult["ERRORS"])) ? 'error' : '' ?>" placeholder="Пароль <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? '*' : '' ?>"/>
							<? break;
						case "CONFIRM_PASSWORD":
							?>
							<input size="30" type="password" name="REGISTER[<?= $FIELD ?>]" <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? 'required' : '' ?> value="<?= $arResult["VALUES"][$FIELD] ?>" autocomplete="off" class="custom-input <?= (array_key_exists($FIELD, $arResult["ERRORS"])) ? 'error' : '' ?>" placeholder="Подтверждение пароля <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? '*' : '' ?>"/>
							<? break;
						case "EMAIL":
							?>
							<input size="30" type="email" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>"  <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? 'required' : '' ?> id="emails" class="custom-input <?= (array_key_exists($FIELD, $arResult["ERRORS"])) ? 'error' : '' ?>" placeholder="Email <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? '*' : '' ?>"/>
							<? break; ?>
						<? case "NAME":
							?>
							<?
							// $arName = array();
							// if ($arResult["VALUES"]["LAST_NAME"]) {
							// 	$arName[] = $arResult["VALUES"]["LAST_NAME"];
							// }
							// if ($arResult["VALUES"]["NAME"]) {
							// 	$arName[] = $arResult["VALUES"]["NAME"];
							// }
							// if ($arResult["VALUES"]["SECOND_NAME"]) {
							// 	$arName[] = $arResult["VALUES"]["SECOND_NAME"];
							// }
							?>
							<input size="30" type="text" name="REGISTER[<?= $FIELD ?>]" <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? 'required' : '' ?> value="<?= $arResult["VALUES"]["NAME"]; ?>" class="custom-input <?= (array_key_exists($FIELD, $arResult["ERRORS"])) ? 'error' : '' ?>" placeholder="Ваше имя <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? '*' : '' ?>"/>
							<? break; ?>
							<? case "LAST_NAME":?>
							<input size="30" type="text" name="REGISTER[<?= $FIELD ?>]" <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? 'required' : '' ?> value="<?= $arResult["VALUES"]["LAST_NAME"]; ?>" class="custom-input <?= (array_key_exists($FIELD, $arResult["ERRORS"])) ? 'error' : '' ?>" placeholder="Ваша фамилия <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? '*' : '' ?>"/>
							<? break; ?>
							<? case "SECOND_NAME":?>
							<input size="30" type="text" name="REGISTER[<?= $FIELD ?>]" <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? 'required' : '' ?> value="<?= $arResult["VALUES"]["SECOND_NAME"]; ?>" class="custom-input <?= (array_key_exists($FIELD, $arResult["ERRORS"])) ? 'error' : '' ?>" placeholder="Ваше отчество <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? '*' : '' ?>"/>
							<? break; ?>

						<? case "PERSONAL_PHONE":
							?>
							<input size="30" placeholder="Телефон <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? '*' : '' ?>" type="tel" name="REGISTER[<?= $FIELD ?>]" class="js-phone phone-input custom-input <?= (array_key_exists($FIELD, $arResult["ERRORS"])) ? 'error' : '' ?>" <?= ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") ? 'required' : '' ?> value="<?= $arResult["VALUES"][$FIELD] ?>"/>
							<? break; ?>
						<? default:
							?>
							<? // hide login
							?>
							<input size="30" <?= $FIELD == "LOGIN" ? 'type="hidden" value="1"' : 'type="text"' ?> name="REGISTER[<?= $FIELD ?>]"/>
							<input type="hidden" name="register_submit_button" value="отправить">
							<? break; ?>

						<? } ?>
					<? if ($FIELD != "LOGIN" && array_key_exists($FIELD, $arResult["ERRORS"])): ?>
						<label class="error"><?= GetMessage("REGISTER_FILL_IT") ?></label>
					<? endif; ?>
				</div>
			<? endforeach ?>
			<div class="custom-input-block">
				<div class="checkbox-input checkbox-input-br">
					<input class="checkbox-input__input js-personal-data" type="checkbox" id="recall_personal_data_reg" name="recall_personal_data" checked="" required="">
					<label class="checkbox-input__label" for="recall_personal_data_reg">
						Я даю <a href="/help/dogovor-oferty/" target="_blank">согласие на обработку своих персональных данных</a> *
					</label>
				</div>
			</div>
			<div class="two-sides">
				<div class="reg-button">
					<button class="butn btn-base" type="submit" name="">
						отправить
					</button>
				</div>
				<div><span class="base-text">* Обязательно для заполнения</span></div>
			</div>
		</form>
	</div>
	</div>
<? } ?>