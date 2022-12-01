<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BuildPcSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use App\BuildPcSubCategory;
use App\BuildPcAttribute;
use App\BuildPcCategory;
use App\BuildPcComponent;
use Input;
use DB;
use View;
use App\CategoryManagementSettings;
use App\SubCategoryManagementSettings;

use Session;
use Redirect;
use URL;

class AdminBuildPcController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index () {
        $loged = session()->get('user');
        if($loged) {
            $page = "Settings";
             $general = BuildPcSettings::first();
                	return View::make('build_pc.build_pc_setting')->with(array('general'=>$general,'page'=>$page));
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }
	public function SelectSubCat (Request $request) {
		$main_cat = 0;
    	$sub_cat_val = 0;
    
		if($request->ajax() && isset($request->main_cat)){
			$main_cat = $request->main_cat;

			if(isset($request->sub_cat)) {
				$sub_cat_val = $request->sub_cat;
			}

			$data = "";
			$ids=$request->ids;
			if($main_cat != 0) {
				$sub_cat = SubCategoryManagementSettings::whereIn('main_cat_name',$main_cat)->get();
				if(($sub_cat) && (sizeof($sub_cat) != 0)){
					if($sub_cat_val != 0) {
					    
	                    foreach ($sub_cat as $key => $value) {
	                    		if($ids!=null && in_array($value->sub_cat_id,$ids)) {
	                        	$data.='<option selected value="'.$value->sub_cat_id.'">'.$value->sub_cat_name.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->sub_cat_id.'">'.$value->sub_cat_name.'</option>';
	                    	}
	                    }
					} else {
					   
	                    
	                    foreach ($sub_cat as $key => $value) {
	                    		if($ids!=null && in_array($value->sub_cat_id,$ids)) {
	                        	$data.='<option selected value="'.$value->sub_cat_id.'">'.$value->sub_cat_name.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->sub_cat_id.'">'.$value->sub_cat_name.'</option>';
	                    	}
	                    }
	                    
					}
                } 			
			}
			echo $data;
		}
		else if($request->type=='fetch_data')
		{
		    //salu
		    $do=BuildPcComponent::find($request->id);
		    $cat=BuildPcCategory::where('component_id',$request->id)->pluck('category_id')->all();
		    $sub=BuildPcSubCategory::where('component_id',$request->id)->pluck('sub_category_id')->all();
		    $attr=BuildPcAttribute::where('component_id',$request->id)->get();
		    $data=['com'=>$do,'cat'=>$cat,'sub'=>$sub,'attr'=>$attr];
		    return $data;
		}
		else if($request->type=='cat')
		{
		    $data = "";
		    $ids=$request->ids;
		    
		    $as=CategoryManagementSettings::where('is_block', 1)->get();
		     foreach ($as as $key => $value) {
	                    	if($ids!=null && in_array($value->id,$ids)) {
	                        	$data.='<option selected value="'.$value->id.'">'.$value->main_cat_name.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->id.'">'.$value->main_cat_name.'</option>';
	                    	}
	                    }
	       echo $data;
		}
    }
    public function create () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'General Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
            	$general = GeneralSettings::first();
            	if($general) {
                	return View::make("settings.general_setting")->with(array('general'=>$general,'page'=>$page));
            	} else {
                	return View::make('settings.general_setting');
            	}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }
    public function delete_custom($id)
    {
            $new=BuildPcComponent::find($id);
        BuildPcCategory::where('component_id',$id)->delete();
        BuildPcSubCategory::where('component_id',$id)->delete();
        BuildPcAttribute::where('component_id',$id)->delete();
        $new->delete();
         Session::flash('message', 'Deleted Successfully'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
    }
    public function store(Request $request) {
        $loged = session()->get('user');
       
        if($loged) {
            
            if(isset($request->btn))
            {
                if($request->id!=null)
                {
                    //salu
                    if($request->custom_id!=null)
                    {
                        $new=BuildPcComponent::find($request->custom_id);
                         $new->build_id=$request->id;
                    $new->component_name=$request->component_name;
                    $new->remarks=$request->component_remark;
                    if($request->custom_id!=null)
                    $new->update();
                    else
                    $new->save();
                        $main_cat_name=$request->main_cat_name;
                    if(count($main_cat_name)>0)
                    {
                        foreach($main_cat_name as $k=>$value)
                        {
                            $ex=BuildPcCategory::where('component_id',$new->id)->where('category_id',$value)->count();
                            if($ex==0)
                            {
                            $as=new BuildPcCategory();
                            $as->component_id=$new->id;
                            $as->category_id=$value;
                            $as->save();
                            }
                            
                        }
                        
                    }
                    $main_sub_cat_name=$request->sub_cat_name;
                    if(isset($main_sub_cat_name) && count($main_sub_cat_name)>0)
                    {
                        foreach($main_sub_cat_name as $k=>$value)
                        {
                            $ex1=BuildPcSubCategory::where('component_id',$new->id)->where('sub_category_id',$value)->count();
                            if($ex1==0)
                            {
                            $as=new BuildPcSubCategory();
                            $as->component_id=$new->id;
                            $as->sub_category_id=$value;
                            $as->save();
                            }
                            
                        }
                        
                    }
                    
                    $main_sub_attribute=$request->attribute_name;
                    $att_value=$request->attribute_value;
                    $att_id=$request->attr_id;
                    if(isset($main_sub_attribute) && count($main_sub_attribute)>0)
                    {
                        foreach($main_sub_attribute as $k=>$value)
                        {
                            if($att_id[$k]!=null)
                             $as= BuildPcAttribute::find($att_id[$k]);

                            else
                            $as=new BuildPcAttribute();
                            $as->component_id=$new->id;
                            $as->att_name=$value;
                            $as->att_value=$att_value[$k];
                             if($att_id[$k]!=null)
                            $as->update();
                            else
                            $as->save();
                            
                        }
                        
                    }
                    }
                    else
                    {
                        $new=new BuildPcComponent();
                         $new->build_id=$request->id;
                    $new->component_name=$request->component_name;
                    $new->remarks=$request->component_remark;
                    if($request->custom_id!=null)
                    $new->update();
                    else
                    $new->save();
                        $main_cat_name=$request->main_cat_name;
                    if(count($main_cat_name)>0)
                    {
                        foreach($main_cat_name as $k=>$value)
                        {
                            $as=new BuildPcCategory();
                            $as->component_id=$new->id;
                            $as->category_id=$value;
                            $as->save();
                            
                        }
                        
                    }
                    $main_sub_cat_name=$request->sub_cat_name;
                    if(isset($main_sub_cat_name) && count($main_sub_cat_name)>0)
                    {
                        foreach($main_sub_cat_name as $k=>$value)
                        {
                            $as=new BuildPcSubCategory();
                            $as->component_id=$new->id;
                            $as->sub_category_id=$value;
                            $as->save();
                            
                        }
                        
                    }
                    
                    $main_sub_attribute=$request->attribute_name;
                    $att_value=$request->attribute_value;
                    if(isset($main_sub_attribute) && count($main_sub_attribute)>0)
                    {
                        foreach($main_sub_attribute as $k=>$value)
                        {
                            $as=new BuildPcAttribute();
                            $as->component_id=$new->id;
                            $as->att_name=$value;
                            $as->att_value=$att_value[$k];
                            $as->save();
                            
                        }
                        
                    }
                    }
                    
                   
                     Session::flash('message', 'Saved Successfully'); 
            Session::flash('alert-class', 'alert-success');
                    
                  return Redirect::back();  
                }
                
            }
            else
            {
                $page = "Settings";
            	$rules = array(
                    'title'          => 'required',
                    'description'   => 'nullable',
                    'product_features'      => 'required',
                   
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                    $general = BuildPcSettings::first();
                    if($general) {
                       return View::make('build_pc.build_pc_setting')->withErrors($validator)->with(array('general'=>$general,'page'=>$page));
                    } else {
                	   return View::make('build_pc.build_pc_setting')->withErrors($validator)->with(array('page'=>$page));
                    }
                } else {
                    $data = Input::all();
                    $id = Input::get('id');
                    $general = '';
                    if($id != '') {
                    	$general = BuildPcSettings::Where('id', $id)->first();
                    } else {
                    	$general = new BuildPcSettings();
                    }

                    if($general) {
        	            $general->title         = $data['title'];
        	            // $general->site_description  = $data['site_description'];
        	            $general->product_features     = $data['product_features'];
        	            $general->description  = $data['description'];
        	            
        	            $general->notes      = $data['notes'];

                        
                        if($general->save()) {
                            Session::flash('message', 'Settings Updated Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                        	return View::make("build_pc.build_pc_setting")->with(array('general'=>$general));
                        } else{
                            return Redirect::back();
                        }
                    } else{
                        return Redirect::back();
                    }
                }
            
        }
        } 
        else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }
}