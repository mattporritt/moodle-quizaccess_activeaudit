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
 * Implementaton of the quizaccess_activeaudit plugin.
 *
 * @package   quizaccess_activeaudit
 * @copyright 2020 Matt Porritt <mattp@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');


/**
 * A rule that facilitates active audit functionality.
 *
 * @package   quizaccess_activeaudit
 * @copyright 2020 Matt Porritt <mattp@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_activeaudit extends quiz_access_rule_base {

    /**
     * Add the settings for this rule to the quiz activity edit form.
     *
     * @param mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    public static function add_settings_form_fields(mod_quiz_mod_form $quizform, MoodleQuickForm $mform): void {

        // Enable active audit functionality for the quiz.
        $mform->addElement('advcheckbox', 'enableactiveaudit',
            get_string('enable', 'quizaccess_activeaudit'),
            get_string('enableactiveaudit', 'quizaccess_activeaudit'),
            array(), array(0, 1)
            );
        $mform->addHelpButton('enableactiveaudit', 'enableactiveaudit', 'quizaccess_activeaudit');
    }

    /**
     * Save the settings from the quiz settings form to this plugins database table.
     *
     * @param object $quiz the data from the quiz form, including $quiz->id which is the id of the quiz being saved.
     */
    public static function save_settings($quiz): void {
        global $DB;

        // We are only storing if rule is enabled or not.
        if (empty($quiz->enableactiveaudit)) {
            $DB->delete_records('quizaccess_activeaudit', array('quizid' => $quiz->id));
        } else if (!$DB->record_exists('quizaccess_activeaudit', array('quizid' => $quiz->id))) {
            $record = new stdClass();
            $record->quizid = $quiz->id;
            $record->enabled = true;
            $DB->insert_record('quizaccess_activeaudit', $record);
        }
    }

    /**
     * Remove the rule settings from the database if this rule is disabled for the quiz.
     *
     * @param object $quiz the data from the quiz form, including $quiz->id which is the id of the quiz being saved.
     */
    public static function delete_settings($quiz) {
        global $DB;
        $DB->delete_records('quizaccess_activeaudit', array('quizid' => $quiz->id));
    }

    /**
     * Return the SQL and params needed to load all the settings for this access rule.
     *
     * @param int $quizid the id of the quiz we are loading settings for
     * @return array $sqlarray The SQL fragment and params to return.
     */
    public static function get_settings_sql($quiz) {
        $fields = 'activeaudit.enabled AS enableactiveaudit';
        $joins = 'LEFT JOIN {quizaccess_activeaudit} activeaudit ON activeaudit.quizid = quiz.id';
        $params = array();

        $sqlarray = array($fields, $joins, $params);

        return $sqlarray;
    }

}
