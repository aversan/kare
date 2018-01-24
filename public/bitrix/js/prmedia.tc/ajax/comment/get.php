<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/prmedia.tc/ajax/.prolog.php';

use \Bitrix\Main\Web\Json;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

// get query data
$list = $request->getQueryList()->toArray();

// get component id
$componentId = $list['component_id'];
if (empty($componentId))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: component_id.'
	));
	return;
}

// comment id
$id = intval($list['id']);
if ($parentId > 0)
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: id.',
		'component_id' => $componentId
	));
	return;
}

$params = array(
	'filter' => array(
		'ID' => $id
	),
	'select' => array('ID', 'TEXT')
);
$dbComment = \Prmedia\Tc\Entity\CommentTable::getList($params);
if ($commentFields = $dbComment->fetch())
{
	$commentFields['TEXT'] = htmlspecialchars_decode($commentFields['TEXT']);
	echo Json::encode(array(
		'status' => 'ok',
		'id' => $id,
		'component_id' => $componentId,
		'fields' => $commentFields
	));
	return;
}

echo Json::encode(array(
	'status' => 'error',
	'message' => 'Error: incorrect parameter: id.',
	'component_id' => $componentId,
));
