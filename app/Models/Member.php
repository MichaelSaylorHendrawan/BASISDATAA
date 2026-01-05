<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    // jika tabel bernama 'members'
    protected $table = 'members';

    // sesuaikan primary key (contoh screenshot: member_id)
    protected $primaryKey = 'member_id';

    // jika member_id bukan auto-increment (seperti UUID atau angka non-integer), set false
    public $incrementing = false;

    // jika primaryKey bertipe string, ubah 'string'
    protected $keyType = 'string';

    // Jika tabel memiliki created_at/updated_at, biarkan timestamps true
    public $timestamps = true;

    // fillable fields — sesuaikan sesuai kolom tabel Anda
    protected $fillable = [
        'member_id',
        'branch_id',
        'tier_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'join_date',
        'status',
        'created_at',
        'updated_at',
    ];

    // cast/tanggal
    protected $casts = [
        'join_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Jika members ada di database connection lain (mis. topgolf),
    // uncomment dan ganti 'topgolf' dengan key connection Anda di config/database.php
    // protected $connection = 'topgolf';
}