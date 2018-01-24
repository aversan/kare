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

// generate new captcha
global $APPLICATION;
$sid = $APPLICATION->CaptchaGetCode();

// delete prev captcha
$prevSid = trim($post['prev_sid']);
if (!empty($prevSid))
{
	\CCaptcha::Delete($prevSid);
}

echo Json::encode(array(
	'status' => 'ok',
	'component_id' => $componentId,
	'sid' => $sid
));
