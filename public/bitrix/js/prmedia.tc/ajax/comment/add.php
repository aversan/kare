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
if (empty($componentParams['ENTITY_TYPE']))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect component parameter: ENTITY_TYPE.',
		'component_id' => $componentId
	));
	return;
}
if (empty($componentParams['ENTITY_ID']))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect component parameter: ENTITY_ID.',
		'component_id' => $componentId
	));
	return;
}

// combine comment fields
$fields = array(
	'ENTITY_TYPE' => $componentParams['ENTITY_TYPE'],
	'ENTITY_ID' => $componentParams['ENTITY_ID']
);

// user_id
$user = \Prmedia\Tc\User::getInstance();
if ($user->isAuthorized())
{
	$fields['USER_ID'] = $user->getId();
}
if ($user->isAnonim())
{
	if ($componentParams['ANONIM_USE'] !== 'Y')
	{
		echo Json::encode(array(
			'status' => 'error',
			'error' => 'Error: unauthorized user can not add comment',
			'component_id' => $componentId
		));
		return;
	}

	if ($componentParams['ANONIM_VERIFIER_USE'] === 'Y' && !$user->isVerified())
	{
		echo Json::encode(array(
			'status' => 'error',
			'error' => 'Error: user is not verified',
			'component_id' => $componentId
		));
		return;
	}

	$currentUserFields = $user->getFields();
	if ($currentUserFields['GENERAL_NAME'] !== $post['general_name'])
	{
		$userFields = array();
		$userFields['GENERAL_NAME'] = trim($post['general_name']);
		if (empty($userFields['GENERAL_NAME']))
		{
			echo Json::encode(array(
				'status' => 'error',
				'errors' => array(
					'general_name' => true
				),
				'component_id' => $componentId
			));
			return;
		}

		$addResult = \Prmedia\Tc\Entity\UserTable::add($userFields);
		$user->setId($addResult->getId());
		$fields['USER_ID'] = $user->getId();
	}
}

// parent_id
$parentId = intval($post['parent_id']);
if ($parentId > 0)
{
	$fields['PARENT_ID'] = $parentId;
}

// text
$text = $post['text'];

// smiles
if (!empty($componentParams['SMILE_GALLERY']))
{
	$gallery = new \Prmedia\Tc\Helper\SmileGallery($componentParams['SMILE_GALLERY']);
	$text = $gallery->replaceHtmlWithTyping($text);
}

// strip tags
$sanitizer = new \CBXSanitizer;
$tags = array(
	'div' => array(),
	'b' => array(),
	'i' => array(),
	'u' => array(),
	'strike' => array(),
	'ul' => array(),
	'ol' => array(),
	'li' => array()
);
$sanitizer->AddTags($tags);
$text = $sanitizer->SanitizeHtml($text);

$fields['TEXT'] = $text;

// add comment
$addResult = \Prmedia\Tc\Entity\CommentTable::add($fields);
if ($addResult->isSuccess())
{
	echo Json::encode(array(
		'status' => 'ok',
		'id' => $addResult->getId(),
		'parent_id' => $parentId,
		'component_id' => $componentId
	));
	return;
}

// get errors
$errorsMapping = array();
$errors = $addResult->getErrors();
foreach ($errors as $error)
{
	$fieldName = strtolower($error->getField()->getName());
	$errorsMapping[$fieldName] = array(
		'code' => strtolower($error->getCode()),
		'message' => $error->getMessage()
	);
}
echo Json::encode(array(
	'status' => 'error',
	'errors' => $errorsMapping,
	'component_id' => $componentId
));
