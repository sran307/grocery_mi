<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AboutUsCMSSettings;
use App\HeadingModel;
use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class HeadingController extends Controller
{
  //manage heading page
  public function manage_headings()
  {
     $headings=HeadingModel::all();
      return view("settings.headings.headings", compact("headings"));
  }

  //edit heading
  public function edit_heading($id)
  {
      
      $headings=HeadingModel::where("id", $id)->get();
      return view("settings.headings.edit_heading", compact("headings"));
  }

  public function update_heading(Request $request){
      $id=$request["heading_id"];
      $heading=$request["page_name"];
     
      HeadingModel::where("id", $id)->update([
          "heading"=> $heading
      ]);
    
      return redirect()->route("manage_headings")->with([
        Session::flash('message', 'Heading Updated'),
        Session::flash('alert-class', 'danger'),
      ]);
  }
}
