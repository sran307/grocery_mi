<div role="tabpanel" class="tab-pane fade" id="Section7">
                            <h3> Feed Back</h3>
                            <?php
                                $u_log = session()->get('user');
                            ?>
                            @if($u_log)
                                @if($u_log->user_type == 4 ||$u_log->user_type==5 )
                                    {{ Form::open(array('url' => 'send_feedback','class'=>'gj_fuser_form','files' => true)) }}
                                        @if($u_log)
                                            {{ Form::hidden('user_id', $u_log->id, array('class' => 'form-control gj_fuser_id')) }}
                                        @endif

                                        <div class="gj_box dark gj_inside_box">
                                            <header>
                                                <h5 class="gj_heading"> User Feed Back  </h5>
                                            </header>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{ Form::label('subject', 'Subject') }}
                                                    <span class="error">* 
                                                        @if ($errors->has('subject'))
                                                            {{ $errors->first('subject') }}
                                                        @endif
                                                    </span>

                                                    {{ Form::text('subject', ($user->subject ? $user->subject : Input::old('subject')), array('class' => 'form-control gj_subject','placeholder' => 'Enter Subject in English')) }}
                                                </div>

                                                <div class="form-group">
                                                    {{ Form::label('message', 'Message') }}
                                                    <span class="error">* 
                                                        @if ($errors->has('message'))
                                                            {{ $errors->first('message') }}
                                                        @endif
                                                    </span>

                                                    {{ Form::textarea('message', ($user->message ? $user->message : Input::old('message')), array('class' => 'form-control gj_message', 'rows' => '5','placeholder' => 'Enter Message in English')) }}
                                                </div>
                                            </div>
                                        </div>

                                        {{ Form::submit('Send', array('class' => 'btn btn-primary')) }}

                                    {{ Form::close() }}
                                @else
                                    <p class="gj_no_data">Sorry!, You can not send the Feed Back!</p>
                                @endif
                            @else
                                <p class="gj_no_data">Sorry!, You can not send the Feed Back!</p>
                            @endif
                        </div>