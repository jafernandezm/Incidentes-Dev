@extends('incidente')



@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('incidente.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('incidente.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection