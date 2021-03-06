<?php
/**
 * Element: HR
 * Displays a line
 *
 * @package     NoNumber! Elements
 * @version     2.9.0
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die();

/**
 * HR Element
 */
class nnElementHR
{
	var $_version = '2.9.0';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$document =& JFactory::getDocument();
		$document->addStyleSheet( JURI::root(true).'/plugins/system/nonumberelements/css/style.css?v='.$this->_version );

		return '<div class="panel nn_panel nn_hr'.( $j15 ? ' nn_panel_15' : '' ).'"></div>';
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementHR extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'HR';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			$this->_nnelement = new nnElementHR();
			return;
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldHR extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'HR';

		protected function getLabel()
		{
			$this->_nnelement = new nnElementHR();
			return;
		}

		protected function getInput()
		{
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}