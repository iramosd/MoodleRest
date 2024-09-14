# Moodle Rest Wrapper
***
## Description:
Wrapper for llagerlof/MoodleRest, simplify http Request to Moodle Rest

***

## Required:
* PHP 7.4 or greater

## Instructions:

### Installation
* Run:
```
$ composer require iramosdev/moodle-rest-wrapper
```

### Usages:

#### Create a new student:
```
use IramosDev\MoodleRestWrapper\Service as MoodleRestService;

 $newStudent = $this->moodleRestService->createStudent([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'john.doe',
            'email' => 'jdoe@mail.com',
            'password' => 'user_password',
            'phone1' => '+1 (714) 990-7103',
        ]);
```

#### Retrieve course data:
```
use IramosDev\MoodleRestWrapper\Service as MoodleRestService;

$course = $this->moodleRestService->getCourse('course_name')
```

#### Enroll new student:
```
use IramosDev\MoodleRestWrapper\Service as MoodleRestService;

$this->moodleRestService->enrollStudent($newStudent[0]['id'], $course[0]?->id, 5);
```

#### Check if student is currently enroll in a specific course:
```
use IramosDev\MoodleRestWrapper\Service as MoodleRestService;

$this->moodleRestService->isEnrolled(5, 'jdoe@mail.com')
```
