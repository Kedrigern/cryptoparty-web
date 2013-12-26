<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

interface ITaggable extends IBaseRepository
{
	/**
	 * @return \Nette\Database\Table\Selection
	 */
	function getTagTable();

	/**
	 * @return string
	 */
	function getTagMapTablePrefix();

	/**
	 * @param int $id
	 * @param array $tags
	 **/
	function updateTagRel($id, $tags);

	/**
	 * @param int $id
	 * @return array|int[]
	 */
	function findTagsToItem($id);
}

/**
\Nette\Object::extensionMethod('ITaggable::findTagsToItem', function(\Cryptoparty\ITaggable $that, $id) {
	return $that->getConnection()
		->table('tag_rel_article')
		->select('tag_id AS id')
		->where('article_id = ?', $id)
		->fetchPairs('id', 'id');
});

\Nette\Object::extensionMethod('ITaggable::updateTagRel', function(\Cryptoparty\ITaggable $that, $id, $tags) {
	$current = $that->findTagsToItem($id);
	$remove = array_diff($current, $tags);
	$insert = array_diff($tags, $current);

	if(!empty($remove)) {
		$that->getConnection()
			->table('tag_rel_article')
			->where('article_id = ?', $id)
			->where('tag_id', array_keys($remove))
			->delete();
	}

	if(!empty($insert)) {
		foreach($insert as $i) {
			$that->getConnection()
				->table('tag_rel_article')
				->insert(
				array('tag_id' => $i,
				      'article_id' => $id)
			);
		}
	}
});
 */