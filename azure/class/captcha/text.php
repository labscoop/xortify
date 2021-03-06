<?php
/**
 * CAPTCHA for text mode
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      CAPTCHA
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: text.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsCaptchaText extends XoopsCaptchaMethod
{
    /**
     * XoopsCaptchaText::render()
     *
     * @return
     */
    function render()
    {
        $form = $this->loadText() . '&nbsp;&nbsp; <input type="text" name="' . $this->config['name'] . '" id="' . $this->config['name'] . '" size="' . $this->config['num_chars'] . '" maxlength="' . $this->config['num_chars'] . '" value="" />';
        $form .= '<br />' . _CAPTCHA_RULE_TEXT;
        if (!empty($this->config['maxattempts'])) {
            $form .= '<br />' . sprintf(_CAPTCHA_MAXATTEMPTS, $this->config['maxattempts']);
        }
        return $form;
    }

    /**
     * XoopsCaptchaText::loadText()
     *
     * @return
     */
    function loadText()
    {
        $val_a = rand(0, 9);
        $val_b = rand(0, 9);
        if ($val_a > $val_b) {
            $expression = "{$val_a} - {$val_b} = ?";
            $this->code = $val_a - $val_b;
        } else {
            $expression = "{$val_a} + {$val_b} = ?";
            $this->code = $val_a + $val_b;
        }
        return '<span style="font-style: normal; font-weight: bold; font-size: 100%; font-color: #333; border: 1px solid #333; padding: 1px 5px;">'.$expression.'</span>';
    }
}

?>