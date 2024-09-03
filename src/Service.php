<?php

namespace IramosDev\MoodleRestWrapper;

use MoodleRest;
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

    public function getCourse(string $shortname): array
    {
        $courses = $this->moodleRest->request('core_course_get_courses_by_field', ['field' => 'shortname', 'value' => $shortname]);
        $courses = json_decode(json_encode($courses));
        return $courses->courses;
    }

    public function isEnrolled(int $courseId, string $email): bool
    {
        $listEnrolledStudents = $this->moodleRest->request('core_enrol_get_enrolled_users', ['courseid' => $courseId]);
        $isEnrolled = false;

        foreach ($listEnrolledStudents as $student) {
            if ($student['email'] === trim($email)) $isEnrolled = true;
        }

        return $isEnrolled;
    }

    public function createStudent(array $studentData): array
    {
        $student = $this->moodleRest->request('core_user_create_users',
            ['users' => array([
                'firstname' => $studentData['nombre'],
                'lastname' => $studentData['nombre'],
                'username' => $studentData['email'],
                'email' => $studentData['email'],
                'password' => $studentData['password'],
                'phone1' => $studentData['celular'],
            ])
            ]);

        return $student;
    }

    public function enrollStudent(int $userId, int $courseId, int $roleId)
    {
        $enrollment = $this->moodleRest->request('enrol_manual_enrol_users',
            ['enrolments' => array([
                'roleid' => $roleId,
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

    public function createCourse(array $courseData): array
    {
        $categories = $this->getCategoryByName($courseData['categoryname']);

        if (count($categories) === 0) return [];

        return $this->moodleRest->request('core_course_create_courses',
            ['courses' => array([
                'fullname' => $courseData['fullname'],
                'shortname' => $courseData['shortname'],
                'categoryid' => $categories[0]['id'],
            ])
            ]);
    }
}