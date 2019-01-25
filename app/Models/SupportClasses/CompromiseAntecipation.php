<?php
namespace App\Models\SupportClasses;

use Illuminate\Database\Eloquent\Model;

class CompromiseAntecipation extends Model
{
    protected $table = 's_compfi_ante_compro';
    protected $primaryKey = 'cd_antecipa_compro';
    public $sequence = 'sq_s_compfi_ante_compro';
    const CREATED_AT = null;
    const UPDATED_AT = null;   

}