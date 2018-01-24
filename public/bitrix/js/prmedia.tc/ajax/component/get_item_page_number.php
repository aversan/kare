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

// comment id
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

// eval comment page number
$position = 1;
$params = array(
	'order' => array(
		'PATH' => 'ASC'
	),
	'filter' => array(
		'ENTITY_TYPE' => $componentParams['ENTITY_TYPE'],
		'ENTITY_ID' => $componentParams['ENTITY_ID']
	),
	'select' => array('ID')
);
$dbComment = \Prmedia\Tc\Entity\CommentTable::getList($params);
while ($commentFields = $dbComment->fetch())
{
	if (intval($commentFields['ID']) === $id)
	{
		break;
	}
	$position++;
}
echo Json::encode(array(
	'status' => 'ok',
	'component_id' => $componentId,
	'page' => ceil($position / $componentParams['PAGENAV_SIZE']),
	'page_count' => ceil($dbComment->getSelectedRowsCount() / $componentParams['PAGENAV_SIZE'])
));
