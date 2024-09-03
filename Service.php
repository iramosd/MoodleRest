<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';

class Service
{
    private string $url;
    private string $token;
    private MoodleRest $moodleRest;

    public function __construct($url, $token)
    {
        $this->url = $url;
        $this->token = $token;
        $this->moodleRest = new MoodleRest($this->url, $this->token);
    }

    public function getCourse(String $shortname): array
    {
        $courses = $this->moodleRest->request('core_course_get_courses_by_field', ['field' => 'shortname', 'value' => $shortname]);
        $courses = json_decode(json_encode($courses));
        return $courses->courses;
    }

    public function isEnrolled(int $courseId, string $email): bool
    {
        $listEnrolledStudents = $this->moodleRest->request('core_enrol_get_enrolled_users', ['courseid' => $courseId]);
        $isEnrolled = false;

        foreach ($listEnrolledStudents as $student){
            if($student['email'] === trim($email)) $isEnrolled = true;
        }

        return $isEnrolled;
    }

    public function createStudent( array $attendanceStudent): array
    {
        $student = $this->moodleRest->request('core_user_create_users',
            ['users' => array([
                'firstname' => $attendanceStudent['nombre'],
                'lastname' => $attendanceStudent['nombre'],
                'username' => $attendanceStudent['email'],
                'email' => $attendanceStudent['email'],
                'password' => 'vla12345',
                'phone1' => $attendanceStudent['celular'],
            ])
            ]);

        return $student;
    }

    public function enrollStudent(int $userId, int $courseId)
    {
        //TODO verify if roleid = 5 belongs to student
        $enrollment = $this->moodleRest->request('enrol_manual_enrol_users',
            ['enrolments' => array([
                'roleid' => 5,
                'userid' => $userId,
                'courseid' => $courseId,
            ])
            ]);
    }

    public function getCategoryByName(string $name): array
    {
        return $this->moodleRest->request('core_course_get_categories',
            ['criteria' => array([
                'key' => 'name',
                'value' => $name
            ])
            ]
        );
    }

    public function createCourse( array $attendanceCourse): array
    {
        $categories = $this->getCategoryByName($attendanceCourse['categoryname']);

        if(count($categories) === 0) return [];

        return $this->moodleRest->request('core_course_create_courses',
            ['courses' => array([
                'fullname' => $attendanceCourse['fullname'],
                'shortname' => $attendanceCourse['shortname'],
                'categoryid' => $categories[0]['id'],
            ])
            ]);
    }
}