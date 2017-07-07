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
        <div class="col-md-8">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="text-right">
                <form class="form-inline" method="get">
                  <label for="">Search: </label>
                    <div class="input-group">
                      <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                      <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">
                          <i class="fa fa-search"></i>
                        </button>
                      </span>
                    </div>
                </form>
              </div>
              <div class="list-group">
                @foreach($users as $_user)
                  <a class="list-group-item clearfix">
                    <img class="img-circle img-responsive" src="https://www.gravatar.com/avatar/{{ md5($_user->email) }}?d=retro" alt="">
                    <span class="user-info">
                      {{ $_user->name }}<br>
                      <small>Score: {{ $_user->score }}</small>
                    </span>
                    <form class="" action="/new-game" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" name="user_id" value="{{ $_user->id }}">
                      <button type="submit" class="btn btn-primary pull-right">Play</button>
                    </form>
                  </a>
                @endforeach
              </div>
              {{ $users->links() }}
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
