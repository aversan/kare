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

// page
$page = intval($post['page']);
if ($page <= 0)
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: page.'
	));
	return;
}
$componentParams['PAGENAV_PAGE'] = $page;

// baselink
$baselink = $post['baselink'];
if (!empty($baselink))
{
	$componentParams['PAGENAV_BASE_LINK'] = $baselink;
}

// get pager
$pager = null;
try
{
	$pager = \Prmedia\Tc\Pager\Factory::create($componentParams['PAGENAV_PAGER']);
}
catch (\Exception $ex)
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect component parameter: PAGENAV_PAGER.'
	));
	return;
}

// render component
global $APPLICATION;
$componentParams['PAGENAV_AJAX'] = 'Y';
ob_start();
$APPLICATION->IncludeComponent('prmedia:tc.list', $componentParams['COMPONENT_TEMPLATE'], $componentParams, false, array('HIDE_ICONS' => 'Y'));
$content = ob_get_clean();

echo Json::encode(array(
	'status' => 'ok',
	'component_id' => $componentId,
	'type' => strtolower($pager->getListAjaxType()),
	'content' => $content
));
