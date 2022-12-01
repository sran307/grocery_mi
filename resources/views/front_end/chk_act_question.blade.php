@extends('layouts.frontend')
@section('title', 'SignIn')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
<section class="gj_buss_signin_bk">
    <div class="main-content maxil" id="MainContent">
        <div class="container">
            <div class="wraper-inner sn">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="formaccount formlogin">
                            <div id="login">
                                <h1 class="page-title">Activate Your Account</h1>
                                <form method="post" action="{{ route('chk_act_answer') }}" id="chk_question" accept-charset="UTF-8">
                                    <div class="form-group">
                                        <label for="email">Email or Mobile number</label>
                                        <span class="error">* 
                                            @if ($errors->has('email'))
                                                {{ $errors->first('email') }}
                                            @endif
                                        </span>
                                        <input type="text" name="email" class="form-control" id="email" placeholder="Enter Your Email or Mobile Number">
                                    </div>

                                    <div class="form-group">
                                        <label for="question">Select Your Security Question</label>
                                        <span class="error">* 
                                            @if ($errors->has('question'))
                                                {{ $errors->first('question') }}
                                            @endif
                                        </span>

                                        @php ($opt = '<option value=""> Select Your Security Question </option>')
                                        @if(isset($secure) && sizeof($secure) != 0)
                                            @foreach($secure as $skey => $sval)
                                                <?php 
                                                    $opt.= '<option value="'.$sval->id.'"> '.$sval->question.' </option>';    
                                                ?>
                                            @endforeach
                                        @endif
                                        <select name="question" id="question" class="form-control gj_s_question">
                                            <?php echo $opt; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="answer">Security Answer</label>
                                        <span class="error">* 
                                            @if ($errors->has('answer'))
                                                {{ $errors->first('answer') }}
                                            @endif
                                        </span>
                                        <input class="form-control gj_s_answer" type="text" name="answer" autocomplete="new-answer" placeholder="Enter Your Security Answer">
                                        <input type="hidden" name="check" value="activate">
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-sign-in" aria-hidden="true"></i>Activate</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="formaccount formlogin block">
                            <h1 class="page-title">Create Account</h1>
                            <div class="formcontent">
                                <div class="registerdescription">
                                    <p>Sign up for a free account with Ecambiar. Registration is quick and easy. It allows you to be able to order from our shop. To start shopping click register.</p>
                                </div>

                                <div class="submit">
                                    <a class="btn btn-default" href="{{ route('signup') }}">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i><span>Create An Account</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300);

        $('#question').select2(); 
    });
</script>
@endsection