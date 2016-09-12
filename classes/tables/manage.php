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

namespace auth_outage\tables;

require_once($CFG->libdir . '/tablelib.php');

/**
 * Manage outages table.
 *
 * @package    auth_outage
 * @author     Daniel Thee Roperto <danielroperto@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class manage extends \flexible_table {
    private static $autoid = 0;

    public function __construct($id = null) {
        global $PAGE;

        $id = (is_null($id) ? self::$autoid++ : $id);
        parent::__construct('auth_outage_manage_' . $id);

        $this->define_columns(['starttime', 'stopsafter', 'warnbefore', 'title', '']);

        $this->define_headers([
                get_string('tableheaderstarttime', 'auth_outage'),
                get_string('tableheaderstopsafter', 'auth_outage'),
                get_string('tableheaderwarnbefore', 'auth_outage'),
                get_string('tableheadertitle', 'auth_outage'),
                '',
            ]
        );

        $this->define_baseurl($PAGE->url);
        $this->set_attribute('class', 'generaltable admintable');
        $this->setup();
    }

    public function set_data(array $outages) {
        global $OUTPUT;
        foreach ($outages as $outage) {
            $buttons = '';

            $url = new \moodle_url('/auth/outage/edit.php', ['id' => $outage->id]);
            $html = \html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('t/edit'), 'alt' => get_string('edit'), 'class' => 'iconsmall'));
            $buttons .= \html_writer::link($url, $html, array('title' => get_string('edit')));

            $url = new \moodle_url('/auth/outage/delete.php', ['id' => $outage->id]);
            $html = \html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('t/delete'), 'alt' => get_string('delete'), 'class' => 'iconsmall'));
            $buttons .= \html_writer::link($url, $html, array('title' => get_string('delete')));

            // Table columns 'name', 'action', 'role', 'parent', 'continue', 'priority', 'data'.
            $values = [
                userdate($outage->starttime, get_string('tablerowstarts', 'auth_outage')),
                $outage->get_duration_string(),
                $outage->get_warning_duration_string(),
                $outage->get_title(),
                $buttons,
            ];

            $this->add_data($values);
        }
    }
}