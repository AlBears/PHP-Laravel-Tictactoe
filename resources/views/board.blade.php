@extends('layouts.app')

@section('scripts')
<script language="javascript">
var pusher = new Pusher('54cd370b03cc4e635da5', {cluster: 'eu', encrypted: true})
        .subscribe('game-channel-{{ $id }}-{{ $otherPlayerId }}')
        .bind("App\\Events\\Play", function(data){
          $('#block-'+data.location).removeClass('player-{{ $playerType }}').addClass('player-'+data.type);
          $('#block-'+data.location).attr('checked', true);
          $('input[type=radio]').removeAttr('disabled');
          $('.profile-username').html('You are next!');

});

$(document).ready(function(){
  $('input[type=radio]').on('click', function(){
    $('.profile-username').html('Waiting on player 2...');
    $.ajax({
      url: '/play/{{ $nextTurn->game_id }}',
      method: 'POST',
      data: {
        location: $(this).val(),
        _token: $('input[name=_token]').val()
      },
      success: function(data) {
        console.log('done');
      }
    });
  });
});
</script>
@endsection

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="profile-info">
        <div class="profile-username">
          {{ $user->id == $nextTurn->player_id ? "You are next" : "Waiting on player 2" }}
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tic-tac-toe">
        @foreach($locations as $index => $location)
          <input type="radio"
            class="player-{{ $location["checked"] ? $location['type'] : $playerType }}
            {{ $location["class"] }}" id="block-{{ $index }}"
            value="{{ $index }}"
            {{ $location["checked"] ? "checked" : "" }}
            {{ $user->id != $nextTurn->player_id ? "disabled" : "" }}/>
          <label for="block-{{ $index }}"></label>
        @endforeach
      </div>
    </div>
  </div>
</div>
{{ csrf_field() }}
@endsection
