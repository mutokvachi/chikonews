<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Score;

class ScoreController extends Controller
{
    public function show(){
    	$javascript = asset('/js/scoreboard.js');

    	$scores = Score::orderBy('score','desc')->get();

    	
    	return view('scoreboard',compact('javascript', 'scores'));
    }

}
