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
```terminal
$ composer require iramosdev/moodle-rest-wrapper
```

### Usages:

#### Create a new instance for MoodleRestService:
```php
use IramosDev\MoodleRestWrapper\Service as MoodleRestService;

$moodleRestService = new MoodleRestService('https://www.moodle-site.com/api/endpoint', 'Moodle_token');
```

#### Create a new student:
```php
 $newStudent = $moodleRestService->createStudent([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'john.doe',
            'email' => 'jdoe@mail.com',
            'password' => 'user_password',
            'phone1' => '+1 (714) 990-7103',
        ]);
```

#### Retrieve course data:
```php
$course = $moodleRestService->getCourse('course_name');
```

#### Enroll new student:
```php
$moodleRestService->enrollStudent($newStudent[0]['id'], $course[0]?->id, 5);
```

#### Check if student is currently enroll in a specific course:
```php
$moodleRestService->isEnrolled(5, 'jdoe@mail.com')
```
