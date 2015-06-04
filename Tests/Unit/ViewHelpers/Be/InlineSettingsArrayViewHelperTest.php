<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Be;

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
 * Test case for InlineSettingsArrayViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class InlineSettingsArrayViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @return void
	 */
	public function viewHelperAddsAdditionalJsToPage() {
		$settings = array('datePickerUSmode' => 0);

		$mockPageRenderer = $this->getMock('TYPO3\\CMS\\Core\\Page\\PageRenderer',
			array('addInlineSettingArray'), array(), '', FALSE);
		$mockPageRenderer->expects($this->once())->method('addInlineSettingArray')->with(
			$this->equalTo(''),
			$this->equalTo($settings)
		);

		$mockDocTemplate = $this->getMock('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate',
			array('getPageRenderer'), array(), '', FALSE);
		$mockDocTemplate->expects($this->once())->method('getPageRenderer')->will(
			$this->returnValue($mockPageRenderer));

		$viewHelper = $this->getMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Be\\InlineSettingsArrayViewHelper',
			array('getDocInstance'), array(), '', FALSE);
		$viewHelper->expects($this->once())->method('getDocInstance')->will($this->returnValue($mockDocTemplate));

		$viewHelper->render($settings);
	}

}