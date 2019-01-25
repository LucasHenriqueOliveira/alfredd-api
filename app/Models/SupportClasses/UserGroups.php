<?php

namespace App\Models\SupportClasses;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserGroups extends Pivot
{
    protected $table = "cad_usu_grupo";
    protected $primaryKey = "cd_usu_grupo";

    protected $fillable = [
        "fg_status"
    ];
}