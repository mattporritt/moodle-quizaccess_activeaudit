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
 * A javascript module for the setup related to the active audit plugin.
 *
 * @copyright  2020 Matt Porritt <mattp@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


const testclick = (event) => {
    event.preventDefault();
    window.console.log('test link clicked');

    // Pass a message to the content script of the Active Audit plugin.
    let msg = {
            sender: 'CLIENT',
            type: 'ACTION',
            content: 'test message'
    };
    window.postMessage(msg, window.origin);
};

/**
 * Set up the plugin.
 *
 * @method init
 */
export const init = () => {
    window.console.log('we have been started');

    let testlink = document.getElementById('quizaccess-activeaudit-test-link');
    testlink.addEventListener('click', testclick);
};