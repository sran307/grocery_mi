
                            <h3> Change Password </h3>
                            {{ Form::open(array('url' => 'forgot','class'=>'login100-form validate-form gj_ui_fp', 'files' => true)) }}
                                <div class="validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                                    <input class="form-control" type="text" name="email_id" placeholder="Email">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                       
                                    </span>
                                </div>
                                <p class="error gj_l_err"> 
                                    @if ($errors->has('email_id'))
                                        {{ $errors->first('email_id') }}
                                    @endif
                                </p>
                                
                                <div class="container-login100-form-btn">
                                    <button class="login100-form-btn btn btn-success" type="submit">
                                        Submit
                                    </button>
                                </div>
                            {{ Form::close() }}
                       