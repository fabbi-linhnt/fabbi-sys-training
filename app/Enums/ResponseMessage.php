<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ResponseMessage extends Enum
{
    const USER = [
        'ADD_ERROR' => 'Add User Error',
        'GET_LIST_ERROR' => 'Get List User Error',
        'UPDATE_ERROR' => 'Update User Error',
        'DELETE_ERROR' => 'Delete User Error',
        'COUNT_SUBJECT_ERROR' => 'Count Subject Error',
        'COUNT_TASK_ERROR' => 'Count Task Error',
        'GET_USER_NAME_ERROR' => 'Get User Name Error',
        'GET_USER_INFO_ERROR' => 'Get User Info Error'
    ];

    const SUBJECT = [
        'USER_ACTIVATING' => 'User is activating',
        'ASSIGN_SUCCESS' => 'Assign User to Subject Successfully',
        'ASSIGN_ERROR' => "Don't have any course activated corressponding with subject"
    ];

    const COURSE = [
        'ASSIGN_SUCCESS' => 'Assign user to course success',
        'ASSIGN_ERROR' => 'User has another course',
        'FINISH_COURSE' => 'User has finished the course'
    ];
}
