<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Game;
use App\Level;
use App\Attachment;
use App\Answer;
use App\Help;

class AdminController extends Controller
{
    public function gamesList()
    {
        $games = Game::orderBy('date_start', 'desc')
            ->where('removed', 0)
            ->paginate('15');
        return view('admin.game.list', ['games' => $games]);
    }

    public function createGame(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'type' => ['required'],
            'preview' => ['image', 'nullable'],
            'price' => ['required', 'integer'],
            'area' => ['required', 'integer'],
            'date_start' => ['required', 'date_format:d.m.Y H:i']
        ]);

        $game = new Game();
        $game->fill($request->all());
        $game->user_id = $request->user()->id;
        $game->save();
        $game->setPreview($request->file('preview'));

        return redirect('/admin/games');
    }

    public function login(Request $request)
    {
        $this->validate($request, ['phone' => 'required', 'password' => 'required']);

        if (Auth::attempt(['phone' => $request->input('phone'), 'password' => $request->input('password')])) {
            return redirect('/admin');
        }

        return redirect('/admin/login');
    }

    public function gameDetails($id)
    {
        return view('admin.game.edit', ['game' => Game::findOrFail($id)]);
    }

    public function editGame(Request $request)
    {
        $this->validate($request,[
            'id' => ['required', 'integer', 'exists:games'],
            'preview' => ['image']
        ]);

        $data = $request->only(['name', 'type', 'price', 'area']);

        if ($request->filled('date_start')) $data['date_start'] = $request->input('date_start');

        $game = Game::find($request->input('id'));

        $game->fill($data);
        $game->save();

        if ($img = $request->file('preview')) $game->setPreview($img);

        return redirect()->back();
    }

    public function createLevel(Request $request)
    {
        $this->validate($request, [
            'game_id' => ['integer', 'required', 'exists:games,id'],
            'order' => ['integer', 'required'],
            'required' => ['integer', 'required'],
            'jump' => ['integer', 'nullable']
        ]);

        Level::add($request->all());

        return redirect()->back();
    }

    public function removeGame($id)
    {
        Game::find($id)->remove();
        return redirect()->back();
    }

    public function removeLevel($id)
    {
        Level::find($id)->remove();
        return redirect()->back();
    }

    public function levelDetails($id)
    {
        return view('admin.level.edit', ['level' => Level::findOrFail($id)]);
    }

    public function editLevel(Request $request)
    {
        $this->validate($request,[
            'id' => ['required', 'integer', 'exists:levels'],
            'order' => ['required', 'integer'],
            'required' => ['required', 'integer'],
            'jump' => ['integer', 'nullable']
        ]);

        $data = $request->only(['order', 'required', 'jump']);

        $level = Level::find($request->input('id'));
        $last_idx = $level->order;

        $level->fill($data);

        if ($level->order < $last_idx) {
            Level::where('game_id', $level->game_id)
                ->where([
                    ['order', '>=', $level->order],
                    ['order', '<', $last_idx]
                ])
                ->increment('order');
        }

        if ($level->order > $last_idx) {
            Level::where('game_id', $level->game_id)
                ->where([
                    ['order', '<=', $level->order],
                    ['order', '>', $last_idx]
                ])
                ->decrement('order');
        }

        $level->save();

        return redirect()->back();
    }

    public function createAttach(Request $request)
    {
        $this->validate($request, [
            'level_id' => ['integer', 'required', 'exists:levels,id'],
            /*'order' => ['integer', 'required'],*/
           /* 'type' => ['required'],*/
            'value' => ['required'],
            /*'file' => ['nullable']*/
        ]);

        Attachment::add($request->except(['file']), $request->file('file'));

        return redirect()->back();
    }

    public function removeAttach($id)
    {
        Attachment::find($id)->remove();
        return redirect()->back();
    }

    public function attachDetails($id)
    {
        return view('admin.attach.edit', ['attach' => Attachment::findOrFail($id)]);
    }

    public function editAttach(Request $request)
    {
        $this->validate($request,[
            'id' => ['required', 'integer', 'exists:attachments'],
            'order' => ['integer', 'required'],
            'type' => ['required'],
            'value' => ['nullable'],
            'file' => ['nullable']
        ]);

        $data = $request->only(['order', 'type', 'value']);

        $attach = Attachment::find($request->input('id'));
        $last_idx = $attach->order;

        $attach->fill($data);
        $attach->setFile($request->file('file'));

        if ($attach->order < $last_idx) {
            Attachment::where('level_id', $attach->level_id)
                ->where([
                    ['order', '>=', $attach->order],
                    ['order', '<', $last_idx]
                ])
                ->increment('order');
        }

        if ($attach->order > $last_idx) {
            Attachment::where('level_id', $attach->level_id)
                ->where([
                    ['order', '<=', $attach->order],
                    ['order', '>', $last_idx]
                ])
                ->decrement('order');
        }

        $attach->save();

        return redirect()->back();
    }

    public function createAnswer(Request $request)
    {
        $this->validate($request, [
            'level_id' => ['integer', 'required', 'exists:levels,id'],
            'order' => ['integer', 'required'],
            'code' => ['required'],
        ]);

        Answer::add($request->all());

        return redirect()->back();
    }

    public function removeAnswer($id)
    {
        Answer::find($id)->remove();
        return redirect()->back();
    }

    public function answerDetails($id)
    {
        return view('admin.answer.edit', ['answer' => Answer::findOrFail($id)]);
    }

    public function editAnswer(Request $request)
    {
        $this->validate($request,[
            'id' => ['required', 'integer', 'exists:answers'],
            'order' => ['required', 'integer'],
            'code' => ['required'],
        ]);

        $data = $request->only(['order', 'code']);

        $answer = Answer::find($request->input('id'));
        $last_idx = $answer->order;

        $answer->fill($data);

        if ($answer->order < $last_idx) {
            Answer::where('level_id', $answer->level_id)
                ->where([
                    ['order', '>=', $answer->order],
                    ['order', '<', $last_idx]
                ])
                ->increment('order');
        }

        if ($answer->order > $last_idx) {
            Answer::where('level_id', $answer->level_id)
                ->where([
                    ['order', '<=', $answer->order],
                    ['order', '>', $last_idx]
                ])
                ->decrement('order');
        }

        $answer->save();

        return redirect()->back();
    }

    public function createHelp(Request $request)
    {
        $this->validate($request, [
            'level_id' => ['required', 'integer'],
            'delay' => ['required', 'integer'],
            'text' => ['required']
        ]);

        Help::create($request->only('level_id', 'delay', 'text'));

        return redirect()->back();
    }

    public function removeHelp($id)
    {
        Help::findOrFail($id)->delete();
        return redirect()->back();
    }

    public function helpDetails($id)
    {
        return view('admin.help.edit', ['help' => Help::findOrFail($id)]);
    }

    public function editHelp(Request $request)
    {
        $this->validate($request, [
            'id' => ['required', 'integer'],
            'delay' => ['required', 'integer'],
            'text' => ['required']
        ]);

        $help = Help::findOrFail($request->input('id'));
        $help->fill($request->only('delay', 'text'));
        $help->save();

        return redirect()->back();
    }

    public function gameFinish($id)
    {
        $game = Game::findOrFail($id);
        $game->date_end = now();
        $game->save();

        return redirect()->back();
    }
}
