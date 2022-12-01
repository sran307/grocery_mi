<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NewsLetter;
use App\Contacts;
use App\User;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class NewsLetterController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'News Letter')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Messages";
                $news_letters = NewsLetter::all();
            	return View::make("message.news_letters.manage_news_letters")->with(array('news_letters'=>$news_letters, 'page'=>$page));
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

    public function SendNewsLetters () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Send News Letters')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Messages";
            	$subcribers = NewsLetter::Where('is_block', 1)->get();
                $contacts = Contacts::Where('is_block', 1)->get();
                return View::make("message.news_letters.send_news_letters")->with(array('subcribers'=>$subcribers, 'contacts'=>$contacts, 'page'=>$page));
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

    public function MailedNewsLetters(Request $request) {
    	$error = 1;
    	$err = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Send News Letters')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	if($request->ajax() && isset($request->subject) && isset($request->message) && isset($request->email_to)) {
        	    	$subject = $request->subject;
        	    	$message = $request->message;
        	    	$email_to = $request->email_to;
        	    	$part_subs = 0;
                    $part_enqs = 0;

        	    	if(isset($request->part_subs)) {
        	    		$part_subs = $request->part_subs;
        	    	} else {
        	    		$part_subs = 0;
        	    	}

                    if(isset($request->part_enqs)) {
                        $part_enqs = $request->part_enqs;
                    } else {
                        $part_enqs = 0;
                    }

        	    	$subcriber = 0;

        	    	if($email_to == 1) {
        	    		$subcriber = NewsLetter::Where('is_block', 1)->get();
        	    	} else if($email_to == 2) {
        	    		$subcriber = NewsLetter::Where('is_block', 1)->WhereIn('id', $part_subs)->get();
        	    	} else if($email_to == 3) {
                        $subcriber = Contacts::Where('is_block', 1)->get();
                    } else if($email_to == 4) {
                        $subcriber = Contacts::Where('is_block', 1)->WhereIn('id', $part_enqs)->get();
                    }

            		if (count($subcriber) != 0) {
        				$adm = User::where('user_type', 1)->where('is_block', 1)->first();
                        $admin_email = "teamadsdev2@gmail.com";
                        if($adm) {
                            $admin_email = $adm->email;
                        }

                        $logos = \DB::table('logo_settings')->first();
                        $logo_path = 'images/logo';
                        $logo = "";
                        if($logos) {
                            $logo = asset($logo_path.'/'.$logos->logo_image);
                        } else {
                            $logo = asset('images/logo.png');
                        }

                        $general = \DB::table('general_settings')->first();
                        $site_name = "InterCambiar";
                        if($general){
                            $site_name = $general->site_name;
                        } else {
                            $site_name = "InterCambiar";
                        }

        	    		foreach ($subcriber as $key => $value) {
        	    		    
        	    		    
        	    		    
                            $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $headers.= "MIME-Version: 1.0\r\n";
                            // $headers.= "From: $admin_email" . "\r\n";
                            $headers.= "From: sanwariya@bioessenza.com" . "\r\n";

                            if($email_to == 1) {
                                $to = $value->email;
                            } else if($email_to == 2) {
                                $to = $value->email;
                            } else if($email_to == 3) {
                                $to = $value->contact_email;
                            } else if($email_to == 4) {
                                $to = $value->contact_email;
                            }

                            if(!$subject) {
                            	$subject = "Thanks For Your Subcribe";
                            }

                            // $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                            //         <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                            //         <div style="padding: 20px;background-color: #ff5c00;color: white;">
                            //         	<div>'.$message.'</div>
                            //             <p></p>
                            //             <p><a href="'.route('unsubcribe', ['id' => $value->id]).'">Unsubscribe</a></p>
                            //             <p></p>
                            //             <p>Thanks & Regards,</p>
                            //             <p><a href="'.route('home').'">'.$site_name.'</a></p>
                            //         </div>
                            //     </div>';

                            $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                    <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                    <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                        <div>'.$message.'</div>
                                        <p></p>
                                        <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                        <p style="color: #777;font-size: 10px;">We hope you enjoy receiving news and special offer emails from '.$site_name.'. If you would prefer not receiving our emails, please <a href="'.route('unsubcribe', ['id' => $value->id]).'" style="white-space: nowrap;color: #777;text-decoration: underline;cursor: pointer;font-size: 10px;">click here </a> to unsubscribe.</p>
                                        <p></p>
                                        <p>Thanks & Regards,</p>
                                        <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                    </div>
                                </div>';
                            
                            if(mail($to,$subject,$txt,$headers)){
                                $err = 0;
                            } else {
                                $err = 1;
                            }	    			
        	    		}
        	    	}

        	    	if($err == 0){
                        Session::flash('message', 'Mail Send Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                    } else {
                        Session::flash('message', 'Mail Send Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                    }
                } else {
                	Session::flash('message', 'Mail Send Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $err = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $err = 1;
        }

        echo $err;
    }

	public function delete( Request $request) {	
		$id = 0;
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'News Letter')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$news_letters = NewsLetter::where('id',$id)->first();
        				if($news_letters){
        					if($news_letters->delete()) {
        						Session::flash('message', 'Deleted Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					} else {
        						Session::flash('message', 'Deleted Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        						$error = 1;
        					}
        				}	else {
        					Session::flash('message', 'Deleted Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        					$error = 1;
        				}			
        			} else {
        				Session::flash('message', 'Deleted Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 1;
        }

		echo $error;
	}

	public function DeleteAll( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'News Letter')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$news_letters = NewsLetter::where('id',$value)->first();
        					if($news_letters){
        						if($news_letters->delete()) {
        							Session::flash('message', 'Deleted Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        							$error = 0;
        						} else {
        							Session::flash('message', 'Deleted Failed!'); 
        							Session::flash('alert-class', 'alert-danger');

        						}
        					}	else {
        						Session::flash('message', 'Deleted Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        					}			
        				}
        			} else {
        				Session::flash('message', 'Deleted Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 1;
        }

		echo $error;
	}

	public function StatusNewsLetters ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'News Letter')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$news_letters = '';
        		$msg = '';
            	if($id != '') {
                	$news_letters = NewsLetter::Where('id', $id)->first();
                }

                if($news_letters) {
                	if($news_letters->is_block == 1) {
                    	$news_letters->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$news_letters->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($news_letters->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_news_letters');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_news_letters');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_news_letters');
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

	public function NewsLettersBlock( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'News Letter')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$news_letters = NewsLetter::where('id',$value)->first();
        					if($news_letters){
        						$news_letters->is_block = 0;
        						$news_letters->save();
        						Session::flash('message', 'Blocked Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					}	else {
        						Session::flash('message', 'Blocked Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        					}			
        				}
        			} else {
        				Session::flash('message', 'Blocked Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 1;
        }

		echo $error;
	}

	public function NewsLettersUnblock( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'News Letter')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$news_letters = NewsLetter::where('id',$value)->first();
        					if($news_letters){
        						$news_letters->is_block = 1;
        						$news_letters->save();
        						Session::flash('message', 'Unblocked Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					}	else {
        						Session::flash('message', 'Unblocked Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        					}			
        				}
        			} else {
        				Session::flash('message', 'Unblocked Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 1;
        }

		echo $error;
	}
}
