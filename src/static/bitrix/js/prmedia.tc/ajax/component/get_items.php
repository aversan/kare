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

// get post data and fix encoding issues
$post = $request->getPostList()->toArray();
\CUtil::decodeURIComponent($post);

// get component id
$componentId = $post['component_id'];
if (empty($componentId))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: component_id.'
	));
	return;
}

// get component params
$componentParams = \Prmedia\Tc\Hash::getParams($post['hash']);
if (empty($componentParams))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: hash.',
		'component_id' => $componentId
	));
	return;
}

// comment ids
if (empty($post['ids']) || !is_array($post['ids']))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: ids.',
		'component_id' => $componentId
	));
	return;
}

// render selected comments
global $APPLICATION;
$componentParams['COMMENT_IDS'] = $post['ids'];
$items = $APPLICATION->IncludeComponent('prmedia:tc.list', $componentParams['COMPONENT_TEMPLATE'], $componentParams, false, array('HIDE_ICONS' => 'Y'));

echo Json::encode(array(
	'status' => 'ok',
	'component_id' => $componentId,
	'items' => $items
));
