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
 * Defines the form to be displayed in the metalink block
 *
 * @package    block_metalink
 * @author      Mark Johnson <mark.johnson@tauntons.ac.uk>
 * @copyright   2010 Tauntons College, UK
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * Form to be displayed in the metalink block.
 *
 * Just displays a filepicker field. Display is overridden to capture output in
 * an output buffer, so it can be displayed in the block.
 *
 */
class block_metalink_form extends moodleform {

    /**
     * Defines the form.  Just adds a filepicker and submit button
     */
    public function definition() {
        $mform = $this->_form;

        $context = context_system::instance();
        // Only let people with permission use the block - everyone else will get an empty string.
        if (has_capability('block/metalink:use', $context)) {
            $mform->addElement('filemanager',
                               'metalink_csvfile',
                               get_string('csvfile', 'block_metalink'),
                               null,
                               array('accepted_types' => 'csv,txt',
                                   'subdirs' => 0,
                                   'maxfiles' => 1));
            $mform->addHelpButton('metalink_csvfile', 'csv', 'block_metalink');
            $mform->addRule('metalink_csvfile',
                            get_string('musthavefile', 'block_metalink'),
                            'required',
                            '',
                            'client');
            $mform->addElement('submit', 'metalink_submit', get_string('upload'));
        }
    }

    /**
     * Generate the HTML for the form, capture it in an output buffer, then return it
     *
     * @return string
     */
    public function display() {
        // Finalize the form definition if not yet done.
        if (!$this->_definition_finalized) {
            $this->_definition_finalized = true;
            $this->definition_after_data();
        }
        ob_start();
        $this->_form->display();
        $form = ob_get_clean();
        return $form;
    }
}
