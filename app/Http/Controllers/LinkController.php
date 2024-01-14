<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function getResumeLink(Request $request)
    {

        return redirect(asset('storage/resume.pdf'));
    }
}
