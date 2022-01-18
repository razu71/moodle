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

namespace mod_survey\output;

use moodle_url;
use renderable;
use renderer_base;
use templatable;
use url_select;

/**
 * Output the rendered elements for the tertiary nav page action
 *
 * @package mod_survey
 * @copyright 2021 Sujith Haridasan <sujith@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class actionbar implements renderable, templatable {
    /**
     * The course id.
     *
     * @var int $id
     */
    private $id;

    /**
     * The action decides the url to navigate to.
     *
     * @var string $action
     */
    private $action;

    /**
     * Current url.
     *
     * @var moodle_url $currenturl
     */
    private $currenturl;

    /**
     * actionbar constructor.
     *
     * @param int $id The course module id.
     * @param string $action The action string.
     * @param moodle_url $currenturl The current URL.
     */
    public function __construct(int $id, string $action, moodle_url $currenturl) {
        $this->id = $id;
        $this->action = $action;
        $this->currenturl = $currenturl;
    }

    /**
     * Create select menu for the reports
     *
     * @return url_select url_select object.
     */
    private function create_select_menu(): url_select {
        $summarylink = new moodle_url('/mod/survey/report.php', ['id' => $this->id, 'action' => 'summary']);
        $scaleslink = new moodle_url('/mod/survey/report.php', ['id' => $this->id, 'action' => 'scales']);
        $questionslink = new moodle_url('/mod/survey/report.php', ['id' => $this->id, 'action' => 'questions']);
        $participantslink = new moodle_url('/mod/survey/report.php', ['id' => $this->id, 'action' => 'students']);

        $menu = [
            $summarylink->out(false) => get_string('summary', 'survey'),
            $scaleslink->out(false) => get_string('scales', 'survey'),
            $questionslink->out(false) => get_string('questions', 'survey'),
            $participantslink->out(false) => get_string('participants'),
        ];

        switch ($this->action) {
            case 'summary':
                $activeurl = $summarylink;
                break;
            case 'scales':
                $activeurl = $scaleslink;
                break;
            case 'questions':
                $activeurl = $questionslink;
                break;
            case 'students':
                $activeurl = $participantslink;
                break;
            default:
                $activeurl = $this->currenturl;
        }

        return new url_select($menu, $activeurl->out(false), null, 'surveyresponseselect');
    }

    /**
     * Data for the template.
     *
     * @param renderer_base $output renderer_base object.
     * @return array data for the template
     */
    public function export_for_template(renderer_base $output): array {
        global $PAGE;

        $selecturl = $this->create_select_menu();
        $data = [
            'urlselect' => $selecturl->export_for_template($output)
        ];

        if (has_capability('mod/survey:download', $PAGE->cm->context)) {
            $downloadlink = (new moodle_url('/mod/survey/report.php', ['id' => $this->id, 'action' => 'download']))->out(false);
            $data['download'] = [
                'link' => $downloadlink,
                'text' => get_string('downloadresults', 'mod_survey'),
            ];
        }
        return $data;
    }
}
