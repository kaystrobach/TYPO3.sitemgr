<?php
/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * ViewHelper which allows to add JS Files to a be container
 *
 * = Examples =
 *
 * <code title="Simple">
 *   <sitemgr:Be.Doc.AddCssFile file="{module.cssFile}"/>
 * </code>
 * <output>
 * add js to header with the pagerenderer
 * </output>
 *
 *
 * @author Kay Strobach <typo3@kay-strobach.de>
 * @license http://www.gnu.org/copyleft/gpl.html
 */
class Tx_Sitemgr_ViewHelper_Be_Doc_AddCssFileViewHelper extends Tx_Fluid_ViewHelpers_Be_AbstractBackendViewHelper {
	/**
	 * add additional file
	 *
	 * 25.03.2011 - Thanks to Björn Haverland
	 *              for reporting a typo with $addCssFile
	 *	 	 
	 * @param mixed $file Custom Css file to be loaded
	 * @return void
	 * @see template
	 * @see t3lib_PageRenderer
	 */
	 public function render($file = NULL) {
		$doc = $this->getDocInstance();
		$pageRenderer = $doc->getPageRenderer();

		if ($file !== NULL) {
			if(is_array($file)) {
				foreach($file as $singleFile) {
					$pageRenderer->addCssFile($singleFile);
				}
			} else {
				$pageRenderer->addCssFile($file);
			}
		}
	}
}