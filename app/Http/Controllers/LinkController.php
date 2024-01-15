<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function getResumeLink(Request $request)
    {

        return response()->download(public_path('storage/resume.pdf'), 'resume.pdf');
    }
}
