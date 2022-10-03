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
 * JSON unit tests
 *
 * @package   local_adminsettingsconfig
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_adminsettingsconfig;

use advanced_testcase;

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->libdir . '/adminlib.php');

/**
 * JSON setting test
 * @covers \local_adminsettingsconfig\admin_setting_configjson
 */
class json_test extends advanced_testcase {

    /**
     * Test JSON validation
     *
     * @dataProvider json_provider
     * @param string $setting A regular expression string
     * @param string $expectederrormessage Expected error message. Empty string for no error.
     * @return void
     */
    public function test_json($setting, $expectederrormessage) {
        $this->resetAfterTest();
        $adminsetting = new admin_setting_configjson('abc_cde/json', 'some desc', '', '');
        $errormessage = $adminsetting->write_setting($setting);
        $this->assertSame($errormessage, $expectederrormessage);
        if ($expectederrormessage == '') {
            $this->assertSame($setting, get_config('abc_cde', 'json'));
            $this->assertSame($setting, $adminsetting->get_setting());
        } else {
            $this->assertFalse(get_config('abc_cde', 'regex'));
            $this->assertNull($adminsetting->get_setting());
        }
    }

    /**
     * A data provider for test_json
     *
     * @return array List of data with setting and expectederrormessage set.
     */
    public function json_provider() {
        return [
            'string - invalid' => [
                'setting' => 'string',
                'expectederrormessage' => 'Invalid JSON',
            ],
            'wellformed' => [
                'setting' => '{"menu": {
                    "id": "file",
                    "value": "File",
                    "popup": {
                      "menuitem": [
                        {"value": "New", "onclick": "CreateNewDoc()"},
                        {"value": "Open", "onclick": "OpenDoc()"},
                        {"value": "Close", "onclick": "CloseDoc()"}
                      ]
                    }
                  }}',
                'expectederrormessage' => ''
            ],
            'notclosedproperly - invalid' => [
                'setting' => '{"menu": {
                    "id": "file",
                    "value": "File",
                    "popup": {
                      "menuitem": [
                        {"value": "New", "onclick": "CreateNewDoc()"},
                        {"value": "Open", "onclick": "OpenDoc()"},
                        {"value": "Close", "onclick": "CloseDoc()"}
                      ]
                    }
                  }',
                'expectederrormessage' => 'Invalid JSON'
            ]
        ];
    }
}

