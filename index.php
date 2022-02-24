<?php

use JetBrains\PhpStorm\ArrayShape;

$contributors = [];
$total_contributors = 0;
$total_projects = 0;

#[ArrayShape(['contributors' => "array", 'projects' => "array"])] function read_file (): array {
    $contributors = [];
    $projects = [];
    $total_contributors = 0;
    $total_projects = 0;

    $pending_type = null;
    $pending_lines = 0;
    $current_item = null;

    $handle = fopen(__DIR__ . '/files/b_better_start_small.in.txt', 'r');
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            if (!$total_contributors && !$total_projects) {
                $total_contributors = explode(' ', $line)[0];
                $total_projects = explode(' ', $line)[1];
            } else {
                if (!$pending_lines) {
                    if (count($contributors) < $total_contributors) {
                        while (count($contributors) < $total_contributors) {
                            $name_and_skills = explode(' ', $line);
                            $contributor = new Contributor($name_and_skills[0]);
                            $current_contributor = $contributor;
                            $contributors[] = $contributor;

                            $pending_lines = $name_and_skills[1];
                            $pending_type = 'contributor';
                            $current_item = $contributor;
                            break;
                        }
                    } else {
                        while (count($projects) < $total_projects) {
                            $project_ = explode(' ', $line);
                            $project = new Project($project_);
                            $projects[] = $project;

                            $pending_lines = $project_[count($project_) - 1];
                            $pending_type = 'project';
                            $current_item = $project;
                            break;
                        }
                    }
                } else {
                    $skills = explode(' ', $line);

                    $current_item->setSkills([
                        'name' => $skills[0],
                        'level' => $skills[1]
                    ]);

                    $pending_lines -= 1;
                }
            }
        }

        fclose($handle);
    } else {
        die("Unable to open file");
    }

    return [
        'contributors' => [
            'count' => $total_contributors,
            'data' => $contributors
        ],
        'projects' => [
            'count' => $total_projects,
            'data' => $projects
        ]
    ];
}

class Contributor
{
    public string $name;
    public array $skills;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setSkills(array $data): Contributor
    {
        $this->skills[] = $data;
        return $this;
    }
}

class Project
{
    public string $name;
    public int $duration;
    public int $score;
    public int $best_before;
    public int $roles;
    public array $skills;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->name = $data[0];
        $this->duration = $data[1];
        $this->score = $data[2];
        $this->best_before = $data[3];
        $this->roles = $data[4];
    }

    public function setSkills(array $data): Project
    {
        $this->skills[] = $data;
        return $this;
    }
}

function get_skill_users (array $contributors) {
    $result = [];

    foreach ($contributors as $person) {
        echo $person->skills[0]['name'] . PHP_EOL;
//        if ($result[$person->skills['name']]) {
//            $result[$person->skills['name']]
//        } else {
//
//        }
    }
}

$projects = read_file()['projects']['data'];
$contributors = read_file()['contributors']['data'];

get_skill_users($contributors);

//foreach ($projects as $project) {
//    echo json_encode($project->skills) . PHP_EOL;
//}
