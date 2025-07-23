<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationCode extends Model
{
    /** @use HasFactory<\Database\Factories\InvitationCodeFactory> */
    use HasFactory;

    protected $table = 'invitation_code';
    const UPDATED_AT = null;
}
