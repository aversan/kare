<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/prmedia.tc/ajax/.prolog.php';

use \Bitrix\Main\Web\Json;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

// check request type
if (!$request->isPost())
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: request method is not allowed. Use POST.'
	));
	return;
}

// get component id
$componentId = $request->getPost('component_id');
if (empty($componentId))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: component_id.'
	));
	return;
}

// get component params
$componentParams = \Prmedia\Tc\Hash::getParams($request->getPost('hash'));
if (empty($componentParams))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: hash.',
		'component_id' => $componentId
	));
	return;
}

// calculate count
global $APPLICATION;
$componentParams['GET_COUNT'] = 'Y';
$count = $APPLICATION->IncludeComponent('prmedia:tc.list', $componentParams['COMPONENT_TEMPLATE'], $componentParams, false, array('HIDE_ICONS' => 'Y'));

echo Json::encode(array(
	'status' => 'ok',
	'component_id' => $componentId,
	'count' => intval($count)
));
