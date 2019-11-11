<?php

use app\models\UserModel;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-11-11
 * Time: 10:51
 */
class UserTests extends TestCase
{


    public function testGetUser()
    {
        $id   = 1;
        $user = UserModel::getUser($id);
        var_dump($user);
        $this->assertArrayHasKey('id', $user);
    }

}