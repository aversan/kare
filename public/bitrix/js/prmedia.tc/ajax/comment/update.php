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

// id
$id = intval($post['id']);
if ($id <= 0)
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: id.',
		'component_id' => $componentId
	));
	return;
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

// update comment
$addResult = \Prmedia\Tc\Entity\CommentTable::update($id, $fields);
if ($addResult->isSuccess())
{
	echo Json::encode(array(
		'status' => 'ok',
		'id' => $id,
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
