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

// delete comment
// @todo check user rights
\Prmedia\Tc\Entity\CommentTable::delete($id);

echo Json::encode(array(
	'status' => 'ok',
	'id' => $id
));
