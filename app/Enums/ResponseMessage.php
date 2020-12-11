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
        'GET_USER_INFO_ERROR' => 'Get User Info Error',
        'ADD_SUCCESS' => 'Add User Success',
        'UPDATE_SUCCESS' => 'Update User Success',
        'DELETE_SUCCESS' => 'Delete User Success'
    ];

    const SUBJECT = [
        'USER_ACTIVATING' => 'User is activating',
        'ASSIGN_SUCCESS' => 'Assign User to Subject Successfully',
        'ASSIGN_ERROR' => "Don't have any course activated corressponding with subject",
        'GET_LIST_ERROR' => 'Get List Subject Error',
        'GET_LIST_USER_ERROR' => 'Get List User Error',
        'GET_LIST_COURSE_ERROR' => 'Get List Course Error',
        'ADD_SUCCESS' => 'Add Subject Success',
        'ADD_ERROR' => 'Add Subject Error',
        'SHOW_ERROR' => 'Show List Subject Error',
        'UPDATE_ERROR' => 'Update Subject Error',
        'UPDATE_SUCCESS' => 'Update Subject Success',
        'DELETE_ERROR' => 'Delete Subject Error',
        'DELETE_SUCCESS' => 'Delete Subject Success',
        'COUNT_TASK_ERROR' => 'Count Task Error',
        'LIST_COURSE_ERROR' => 'List Course Error',
        'UPDATE_ACTIVE_ERROR' => 'Update Active Error',
        'UPDATE_ACTIVE_SUCCESS' => 'Update Active Success',
    ];

    const COURSE = [
        'GET_LIST_ERROR' => 'Get List User Error',
        'GET_LIST_USER_ERROR' => 'Get List User Error',
        'ASSIGN_SUCCESS' => 'Assign user to course success',
        'ASSIGN_ERROR' => 'User has another course',
        'FINISH_COURSE' => 'User has finished the course',
        'ADD_ERROR' => 'Add Course Error',
        'UPDATE_ERROR' => 'Update Course Error',
        'DELETE_ERROR' => 'Delete Course Error',
        'DELETE_SUCCESS' => 'Delete Course Success',
        'SHOW_ERROR' => 'Show List Error',
        'GET_CATEGORY_ERROR' => 'Get Category Error',
        'GET_LIST_CATEGORY_BY_COURSE_ID' => 'Get List Category By Course Id'
    ];

    const TASK = [
        'USER_ACTIVATING' => 'User is activating',
        'ASSIGN_SUCCESS' => 'Assign User to Task Successfully',
        'ASSIGN_ERROR' => "Don't have any subject activated corressponding with task",
        'GET_LIST_ERROR' => 'Get List Task Error',
        'ADD_ERROR' => 'Add Task Error',
        'ADD_SUCCESS' => 'Add Task Success',
        'SHOW_ERROR' => 'Show List Task Error',
        'GET_LIST_USER_ERROR' => 'Get List User Error',
        'UPDATE_ERROR' => 'Update Task Error',
        'UPDATE_SUCCESS' => 'Update Task Success',
        'DELETE_ERROR' => 'Delete Task Error',
        'DELETE_SUCCESS' => 'Delete Task Success',
        'GET_USER_TASK_ERROR' => 'Get User Task Error',
        'UPDATE_COMMENT_ERROR' => 'Update Comment Error',
        'UPDATE_COMMENT_SUCCESS' => 'Update Commnet Succcess',
        'GET_USER_BY_TASK_ID_ERROR' => 'Get User By Task Id Error',
        'GET_SUBJECT_BY_TASK_ID_ERROR' => 'Get Subject By Task Id Error'
    ];

    const CATEGORY = [
        'GET_LIST_ERROR' => 'Get List Category Error',
        'DELETE_ERROR' => 'Delete Category Error',
        'DELETE_SUCCESS' => 'Delete Category Success',
        'UPDATE_SUCCESS' => 'Update Category Success',
        'ADD_SUCCESS' => 'Add Category Success'
    ];
}
