<?php

class Tx_sitemgr_Controller_Abstract_ExtMgmUpdaterAbstract {
	function access($what = 'all') {
		return TRUE;
	}
	function getHeader($text) {
		$buffer.= '<table class="typo3-dblist">';
		$buffer.= '<cols><col width="80%"><col width="20%"></cols>';
		$buffer.= '<tr class="t3-row-header"><td colspan="2">'.$text.'</td></tr>';
		return $buffer;
	}
	function getFooter() {
		return '</table>';
	}
	function getButton($func,$enable=true) {
		global $LANG;
		$params = array('do_update' => 1, 'func' => $func);
		$onClick = "document.location='" . t3lib_div::linkThisScript($params) . "'; return false;";
		
		$button = '<tr class="db_list_normal">';
		$button.= '<td>';
		$button.= '<span class="typo3-dimmed" style="float:right;">['.$func.']</span>';
		$button.= '<b style="float:left;">'.$LANG->getLL('action.'.$func).'</b><br>';
		$button.= '<p>'.$LANG->getLL('desc.'.$func).'</p>';
		$button.= '</td><td>';
		if(method_exists($this, $func) && $enable) {
			$button.= '<input type="submit" value="' . $LANG->getLL('button.DoIt') . '" onclick="' . htmlspecialchars($onClick) . '">';
		} else {
			$button.='<input type="submit" value="' . $LANG->getLL('button.DoIt') . '" onclick="' . htmlspecialchars($onClick) . '" disabled="disabled" title="Method disabled!">';
		}
			
		$button.='</td>';
		$button.='</tr>';
		return $button;
	}

}