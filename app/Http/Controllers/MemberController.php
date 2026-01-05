<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(Request $request)
    {
        // Contoh: paginate 15 per halaman. Ubah sesuai kebutuhan.
        $members = Member::orderBy('member_id', 'desc')->paginate(15);

        // Kirim ke Inertia page Members/Index
        return Inertia::render('Members/Index', [
            'members' => $members
        ]);
    }

    // Tambahkan edit/destroy jika diperlukan
}