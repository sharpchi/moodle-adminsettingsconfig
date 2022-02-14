<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Regular expression config text area validator.
 *
 * @package   local_adminsettingsconfig
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2021 Solent University {@link https://solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_adminsettingsconfig;

/**
 * Used to validate a textarea used for json.
 */
class admin_setting_configregex extends \admin_setting_configtextarea {
    /**
     * Number of rows in the textarea
     *
     * @var int
     */
    private $rows;

    /**
     * Number of columns in the textarea
     *
     * @var int
     */
    private $cols;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $visiblename
     * @param string $description
     * @param mixed $defaultsetting string or array
     * @param mixed $paramtype
     * @param string $cols The number of columns to make the editor
     * @param string $rows The number of rows to make the editor
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, $paramtype=PARAM_RAW, $cols='60', $rows='8') {
        $this->rows = $rows;
        $this->cols = $cols;
        parent::__construct($name, $visiblename, $description, $defaultsetting, $paramtype);
    }

    /**
     * Validate the contents of the textarea as a Regular expression.
     *
     * @param string $data A regular expression string
     * @return mixed bool true for success or string:error on failure
     */
    public function validate($data) {
        $invalidregex = false;
        if (empty($data)) {
            // Assuming the "required" property will throw an error if empty.
            return true;
        }
        $delimited = '/' . $data . '/';
        $invalidregex = (@preg_match($delimited, '') === false);
        if ($invalidregex) {
            $errorcode = preg_last_error();
            $errormessage = get_string('regexerrorcode' . $errorcode, 'local_adminsettingsconfig');
            // PREG_INTERNAL_ERROR: 1
            // PREG_BACKTRACK_LIMIT_ERROR: 2
            // PREG_RECURSION_LIMIT_ERROR: 3
            // PREG_BAD_UTF8_ERROR: 4
            // PREG_BAD_UTF8_OFFSET_ERROR: 5.

            return get_string('validateregexerror', 'local_adminsettingsconfig', [
                'errorcode' => $errorcode,
                'errormessage' => $errormessage
            ]);
        }
        return true;
    }

    /**
     * Returns an XHTML string for the editor
     *
     * @param string $data
     * @param string $query
     * @return string XHTML string for the editor
     */
    public function output_html($data, $query='') {
        global $OUTPUT;
        $default = $this->get_defaultsetting();
        $defaultinfo = $default;

        $context = (object) [
            'cols' => $this->cols,
            'rows' => $this->rows,
            'id' => $this->get_id(),
            'name' => $this->get_full_name(),
            'value' => $data,
            'forceltr' => $this->get_force_ltr(),
        ];
        $element = $OUTPUT->render_from_template('core_admin/setting_configtextarea', $context);

        return \format_admin_setting($this, $this->visiblename, $element, $this->description, true, '', $defaultinfo, $query);
    }

}
