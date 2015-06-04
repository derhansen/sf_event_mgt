<?php
namespace DERHANSEN\SfEventMgt\Domain\Model;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Category
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Parent
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Category
	 */
	protected $parent;

	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Sets the parent category
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Category $parent
	 * @return void
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * Returns the parent category
	 *
	 * @return \DERHANSEN\SfEventMgt\Domain\Model\Category
	 */
	public function getParent() {
		return $this->parent;
	}

}