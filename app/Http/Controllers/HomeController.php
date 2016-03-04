<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TextDiff;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDashboard()
    {
        return view('dashboard');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postDashboard(Request $request)
    {
        $labels = trans('lang.form.label');

        $this->validate($request, [
            'text_before' => 'required|min:1',
            'text_after' => 'required|min:1',
        ], [], $labels);

        $textBefore = $request->input('text_before');
        $textAfter = $request->input('text_after');

        $result = TextDiff::factory($textBefore, $textAfter);

        return view('result', compact('result', 'textBefore', 'textAfter'));
    }
}