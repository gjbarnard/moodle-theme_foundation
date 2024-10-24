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
 * Foundation theme.
 *
 * @package    theme_foundation
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\writer;
use theme_foundation\privacy\provider;

/**
 * Privacy unit tests for the Foundation theme.
 *
 * @group theme_foundation
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
final class privacy_provider_test extends \core_privacy\tests\provider_testcase {
    /**
     * Set up.
     */
    protected function set_up() {
        $this->resetAfterTest(true);

        set_config('theme', 'foundation');
    }

    /**
     * Ensure that get_metadata exports valid content.
     * @covers \provider::get_metadata
     */
    public function test_get_metadata(): void {
        $items = new collection('theme_foundation');
        $result = provider::get_metadata($items);
        $this->assertSame($items, $result);
        $this->assertInstanceOf(collection::class, $result);
    }

    /**
     * Ensure that export_user_preferences returns no data if the user has not set a block to be hidden or not.
     * @covers \provider::export_user_preferences
     */
    public function test_export_user_preferences_no_pref(): void {
        $user = \core_user::get_user_by_username('admin');
        provider::export_user_preferences($user->id);

        $writer = writer::with_context(\context_system::instance());

        $this->assertFalse($writer->has_any_data());
    }

    /**
     * Ensure that export_user_preferences returns request data.
     * @covers \provider::export_user_preferences
     */
    public function test_export_user_preferences(): void {
        $this->set_up();
        $this->setAdminUser();

        set_user_preference('block20hidden', '1');

        $user = \core_user::get_user_by_username('admin');
        provider::export_user_preferences($user->id);

        $writer = writer::with_context(\context_system::instance());

        $this->assertTrue($writer->has_any_data());

        $prefs = (array) $writer->get_user_preferences('theme_foundation');

        $this->assertCount(1, $prefs);

        $block = $prefs['block20hidden'];
        $this->assertEquals('1', $block->value);

        $description = get_string('privacy:request:preference:collapseblock', 'theme_foundation', (object) [
            'name' => 'block20hidden',
            'blockid' => '20',
            'value' => '1',
            'decoded' => 'Open',
        ]);
        $this->assertEquals($description, $block->description);
    }
}
