<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS form element of text area
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: formtextarea.php 9392 2012-04-27 21:49:29Z mageg $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A textarea
 */
class XoopsFormTextArea extends XoopsFormElement
{
    /**
     * number of columns
     *
     * @var int
     * @access private
     */
    private $_cols;

    /**
     * number of rows
     *
     * @var int
     * @access private
     */
    private $_rows;

     /**
     * placeholder for this element
     *
     * @var string
     * @access private
     */
    private $_placeholder;


    /**
     * Constructor
     *
     * @param string $caption caption
     * @param string $name name
     * @param string $value initial content
     * @param int $rows number of rows
     * @param int $cols number of columns
     * @param string $placeholder placeholder for this element.
     */
    public function __construct($caption, $name, $value = "", $rows = 5, $cols = 50, $placeholder = '')
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_rows = intval($rows);
        $this->_cols = intval($cols);
        $this->setValue($value);
        $this->_placeholder = $placeholder;
    }

    /**
     * get number of rows
     *
     * @return int
     */
    public function getRows()
    {
        return $this->_rows;
    }

    /**
     * Get number of columns
     *
     * @return int
     */
    public function getCols()
    {
        return $this->_cols;
    }

    /**
     * Get placeholder for this element
     *
     * @return string
     */
    public function getPlaceholder()
    {
        if (empty($this->_placeholder)) {
            return '';
        }
        return $this->_placeholder;
    }

    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $name = $this->getName();
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : '');
        $placeholder = ($this->getPlaceholder() != '' ? " placeholder='" . $this->getPlaceholder() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $required = ($this->isRequired() ? ' required' : '');
        return "<textarea name='" . $name . "' title='" . $this->getTitle() . "' id='" . $name . "'" . $class ." rows='" . $this->getRows() . "' cols='" . $this->getCols() . "'" . $placeholder . $extra . $required . ">" . $this->getValue() . "</textarea><input type='hidden' name='xortify_check[]' value='".$this->getName()."'/>";
    }
}