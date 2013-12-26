<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

abstract class Taggable extends BaseRepository implements ITaggable
{
	/**
	 * @inheritdoc
	 */
	function getTagTable()
	{
		return $this
			->getConnection()
			->table($this->getTagMapTablePrefix() . $this->getTableName());
	}

	/**
	 * @inheritdoc
	 */
	public function getTagMapTablePrefix()
	{
		return 'tag_rel_';
	}
	/**
	 * @inheritdoc
	 */
	function findTagsToItem($id)
	{
		return $this->getTagTable()
			->select('tag_id AS id')
			->where($this->getTableName() . '_id = ?', $id)
			->fetchPairs('id', 'id');
	}

	/**
	 * @inheritdoc
	 */
	function updateTagRel($id, $tags)
	{
		$current = $this->findTagsToItem($id);
		$remove = array_diff($current, $tags);
		$insert = array_diff($tags, $current);

		if(!empty($remove)) {
			$this->getTagTable()
				->where( $this->getTableName() . '_id = ?', $id)
				->where('tag_id', array_keys($remove))
				->delete();
		}

		if(!empty($insert)) {
			foreach($insert as $i) {
				$this->getTagTable()
					->insert(
					array('tag_id' => $i,
					      $this->getTableName() . '_id' => $id)
				);
			}
		}
	}
}