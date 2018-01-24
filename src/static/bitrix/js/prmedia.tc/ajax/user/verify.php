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

// create verifier
$verifier = null;
try
{
	$verifier = \Prmedia\Tc\User\Verifier\Factory::create($componentParams['ANONIM_VERIFIER_CODE']);
}
catch (\Exception $ex)
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: verification is not allowed. Check component parameters.'
	));
	return;
}

// verify
if (!$verifier->verify($post))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: verification failed. Invalid input params.'
	));
	return;
}

// update user session (verify)
$user = \Prmedia\Tc\User::getInstance();
$user->setVerified(true);

// verified
echo Json::encode(array(
	'status' => 'ok',
	'component_id' => $componentId
));
