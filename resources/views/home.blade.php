@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="profile-picture">
                    <img class="img-circle img-responsive" src="https://www.gravatar.com/avatar/{{ md5($user->email) }}?d=retro&s=200" alt="">
                  </div>
                  <div class="profile-info">
                    <div class="profile-username">
                      {{ $user->name }}
                    </div>
                    <div class="profile-score">
                      {{ $user->score }}
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
