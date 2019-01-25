<?php
/**
 * Created by PhpStorm.
 * User: piacentini
 * Date: 20/12/18
 * Time: 10:06
 */

namespace App\Policies;


use App\Models\User;
use App\Models\Compromise;

class UserPolicy
{

    public function before(User $user, $ability)
    {
        return $user->cd_role == 5;
    }

    public function create(User $user)
    {
        return ($user->cd_usuario===60296);
    }

    public function permissionStoreCompromise(User $user)
    {
        if (!$user->permissions) {
            return false;
        }

        foreach ($user->permissions as $permission) {
            if ($permission->fileTypeCode->fileMsgCode->sg_msg == 'COMPFIPGTO' and
                $permission->fileTypeCode->fileMsgCode->sg_direcao == 'ENV') {
                return true;
            }
        }
        return false;
    }
}