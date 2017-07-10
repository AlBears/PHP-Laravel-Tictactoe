<?php

namespace App\Http\Controllers;


use App\Turn;
use App\User;
use App\Game;
use App\Events\Play;
use Illuminate\Http\Request;
use App\Events\GameOver;

class GameController extends Controller
{
    public function board(Request $request, $id)
    {
      $user = $request->user();

      $players = Turn::where('game_id', '=', $id)->select('player_id', 'type')->distinct()->get();
      $playerType = $user->id == $players[0]->player_id ? $players[0]->type : $players[1]->type;
      $otherPlayerId = $user->id == $players[0]->player_id ? $players[1]->player_id : $players[0]->player_id;

      $pastTurns = Turn::where('game_id', '=', $id)->whereNotNull('location')->orderBy('id')->get();
      $nextTurn = Turn::where('game_id', '=', $id)->whereNull('location')->orderBy('id')->first();

      $locations = [
        1 => [
          'class' => 'top left',
          'checked' => false,
          'type' => ''
        ],
        2 => [
          'class' => 'top middle',
          'checked' => false,
          'type' => ''
        ],
        3 => [
          'class' => 'top right',
          'checked' => false,
          'type' => ''
        ],
        4 => [
          'class' => 'center left',
          'checked' => false,
          'type' => ''
        ],
        5 => [
          'class' => 'center middle',
          'checked' => false,
          'type' => ''
        ],
        6 => [
          'class' => 'center right',
          'checked' => false,
          'type' => ''
        ],
        7 => [
          'class' => 'bottom left',
          'checked' => false,
          'type' => ''
        ],
        8 => [
          'class' => 'bottom middle',
          'checked' => false,
          'type' => ''
        ],
        9 => [
          'class' => 'bottom right',
          'checked' => false,
          'type' => ''
        ],
      ];

      foreach($pastTurns as $pastTurn) {
        $locations[$pastTurn->location]['checked'] = true;
        $locations[$pastTurn->location]['type'] = $pastTurn->type;
      }

      return view('board', compact('user', 'id', 'nextTurn', 'locations', 'playerType', 'otherPlayerId'));
    }

    public function play(Request $request, $id)
    {
      $user = $request->user();
      $location = $request->get('location');

      $turn = Turn::where('game_id', '=', $id)->whereNull('location')->orderBy('id')->first();
      $turn->location = $location;
      $turn->save();

      event(new Play($id, $turn->type, $location, $user->id));

      return response()->json(["status" => "success", "data" => "Saved"]);
    }

    public function gameOver(Request $request, $id)
    {
      $user = $request->user();
      $location = $request->get('location');
      $result = $request->get('result');
      $score = $user->score;
      if ($result == 'win') {
        $score++;
        User::where('id', $user->id)->update(['score' => $score]);
        Game::where('id', $id)->update(['winner_id' => $user->id]);
      }

      $turn = Turn::where('game_id', '=', $id)->whereNull('location')->orderBy('id')->first();
      $turn->location = $location;
      $turn->save();

      Game::where('id', $id)->update(['end_date' => date('Y-m-d H:i:s')]);

      event(new GameOver($id, $user->id, $result, $location, $turn->type));
      return response()->json(["status" => "success", "data" => "Saved"]);
    }
}
