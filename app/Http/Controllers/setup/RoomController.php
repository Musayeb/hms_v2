<?php

namespace App\Http\Controllers\setup;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $room=Room::select('users.email','rooms.*')->join('users','users.id','rooms.author')->orderBy('created_at','DESC')->get();
       return view('admin.setup.room',compact('room'));
    }
    public function store(Request $request)
    {
        $validate=$request->validate([
            "room_type"=>'required',
            "room_number"=>'required',
            "room_fees"=>"required",
        ]);
        if($validate){
           $room= new Room;
           $room->room_type=$request->room_type;
           $room->room_number=$request->room_number;
           $room->room_fees=$request->room_fees;
           $room->description=$request->note;
           $room->author=Auth::id();
           $room->save();
           return response()->json(['success'=>'Room registred successfully']);
        }
    }
    public function update(Request $request)
    {
        $validate=$request->validate([
            "room_type"=>'required',
            "room_number"=>'required',
            "room_fees"=>"required",
        ]);
        if($validate){
            $room=Room::find($request->room_id);
            $room->room_type=$request->room_type;
            $room->room_number=$request->room_number;
            $room->room_fees=$request->room_fees;
            $room->description=$request->note;
            $room->save();
            return response()->json(['success'=>'Room updated successfully']);
        }
    }

    public function destroy($id)
    {
        $room=Room::find($id)->delete();
    }
}
