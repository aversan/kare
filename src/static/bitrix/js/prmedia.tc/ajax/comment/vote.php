<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/prmedia.tc/ajax/.prolog.php';

use \Bitrix\Main\Web\Json;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$user = \Prmedia\Tc\User::getInstance();

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

// get comment id
$id = intval($post['id']);
if ($id <= 0)
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: id.'
	));
	return;
}

// get vote type
$type = trim($post['type']);
if (empty($type) || !in_array($type, array('+', '-')))
{
	echo Json::encode(array(
		'status' => 'error',
		'message' => 'Error: missing or incorrect parameter: type.'
	));
	return;
}

// add vote
$addFields = array(
	'USER_ID' => $user->getId(),
	'COMMENT_ID' => $id,
	'TYPE' => $type
);
$addResult = \Prmedia\Tc\Entity\CommentVoteTable::add($addFields);
if (!$addResult->isSuccess())
{
	$errorsMapping = array();
	$errors = $addResult->getErrors();
	foreach ($errors as $error)
	{
		$errorsMapping[] = array(
			'code' => strtolower($error->getCode()),
			'message' => $error->getMessage()
		);
	}
	echo Json::encode(array(
		'status' => 'error',
		'errors' => $errorsMapping
	));
	return;
}

// calculate new values
$params = array(
	'filter' => array(
		'ID' => $id
	),
	'select' => array('VOTE+', 'VOTE-')
);
$commentFields = \Prmedia\Tc\Entity\CommentTable::getList($params)->fetch();

echo Json::encode(array(
	'status' => 'ok',
	'id' => $id,
	'+' => $commentFields['VOTE+'],
	'-' => $commentFields['VOTE-']
));
