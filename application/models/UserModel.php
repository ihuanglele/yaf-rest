<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-11-11
 * Time: 10:47
 */

namespace app\models;


use fw\AbstractModel;

class UserModel extends AbstractModel
{

    public static function getUser($id)
    {
        return [
            'id'   => $id,
            'name' => 'rest',
        ];
    }

}