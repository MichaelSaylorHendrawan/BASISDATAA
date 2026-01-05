<?php
namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::orderBy('member_id', 'desc')->paginate(15);
        return Inertia::render('Members/Index', [
            'members' => $members
        ]);
    }
}