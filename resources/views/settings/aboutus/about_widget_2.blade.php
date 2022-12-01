@extends('layouts.master')
@section('title', 'Manage About First Widget')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage CMS Page  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage About Page First Widget  </h5>
                </header>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_cms_page sr_about_widget_1">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Contents</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_cms_page_bdy">
                               @foreach($data as $value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->contents}}</td>
                                    <td><a href="{{ route('edit_widget_2', ['id' => $value->id]) }}" data-tooltip="Edit">
                                                        <i class="fa fa-edit fa-2x"></i>
                                        </a>
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    $(document).ready(function () {
        $('p.alert').delay(1000).slideUp(300);
    });
</script>
@endsection
