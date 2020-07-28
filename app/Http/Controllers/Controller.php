<?php

namespace App\Http\Controllers;

use App\Mail\contact_send;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
	public function __construct()
	{
	    ini_set('max_execution_time', 360);
	}



    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
