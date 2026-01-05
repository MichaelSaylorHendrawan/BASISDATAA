<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'member_id'; // sesuaikan
    public $timestamps = true;
    // jika members di DB lain:
    // protected $connection = 'topgolf';
    protected $fillable = [
      'member_id','branch_id','tier_id','first_name','last_name','email','phone','join_date','status'
    ];
}