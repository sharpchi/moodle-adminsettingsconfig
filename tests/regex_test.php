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
 * Regex unit tests
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
 * Regex setting test
 * @covers \local_adminsettingsconfig\admin_setting_configregex
 */
class regex_test extends advanced_testcase {

    /**
     * Test regex validation
     *
     * @dataProvider regex_provider
     * @param string $setting A regular expression string
     * @param string $expectederrormessage Expected error message. Empty string for no error.
     * @return void
     */
    public function test_regex($setting, $expectederrormessage) {
        $this->resetAfterTest();
        $adminsetting = new admin_setting_configregex('abc_cde/regex', 'some desc', '', '');
        $errormessage = $adminsetting->write_setting($setting);
        $this->assertSame($errormessage, $expectederrormessage);
        if ($expectederrormessage == '') {
            $this->assertSame($setting, get_config('abc_cde', 'regex'));
            $this->assertSame($setting, $adminsetting->get_setting());
        } else {
            $this->assertFalse(get_config('abc_cde', 'regex'));
            $this->assertNull($adminsetting->get_setting());
        }
    }

    /**
     * A data provider for test_regex
     *
     * @return array List of data with setting and expectederrormessage set.
     */
    public static function regex_provider(): array {
        return [
            'string' => [
                'setting' => 'string',
                'expectederrormessage' => '',
            ],
            'wellformed' => [
                'setting' => '.*([a-z][0-9]+)\w',
                'expectederrormessage' => '',
            ],
            'openbrackets' => [
                'setting' => 'abc(',
                'expectederrormessage' => 'Invalid Regular Expression. Error message: Internal error (1)',
            ],
        ];
    }
}

