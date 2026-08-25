<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LessonController extends Controller
{
    public function index() {
        
       $lessons = Lesson::get();
       $data = [];
       foreach($lessons as $lesson){
        $sub_count = Subscription::where('lesson_id', $lesson->id)->count(); 
        if($sub_count < $lesson->maximum_capacity){
            array_push($data, $lesson);
        }
       }
       return response()->json(['success'=>true,'data'=>$data],200);
    }

    public function subscribeLesson(Request $request){
        try{
            $lessons = Lesson::where('id',$request->class_id)->first();
            $sub_count = Subscription::where('lesson_id', $lessons->id)->count();
            if($sub_count<=$lessons->maximum_capacity){
                $user_exists = Subscription::where('user_id',$request->user()->id)->where('lesson_id',$lessons->id)->exists();
                if($user_exists){
                    return response()->json(['success'=>true,'message'=>'Ya estas inscrito a esta clase'],201);
                }
                Subscription::create([
                    'user_id' => $request->user()->id,
                    'lesson_id' => $lessons->id,
                    'status' => true,
                    'date_suscription'=> Carbon::now()->format('Y-m-d')
                ]);
                return response()->json(['success' => true,'message'=>'Inscrito a la clase con exito'],201);
            } else{
                return response()->json(['success' => true,'message'=>'La clase ya tiene la capacidad completa'],201);
            }
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function unsubscribeLesson($id, Request $request){
        try{
            if(Subscription::where('lesson_id',$id)->where('user_id',$request->user()->id)->exists()){
                $data = Subscription::where('lesson_id',$id)->where('user_id',$request->user()->id)->update(['status'=>0]);
                return response()->json(['success' => true,'message'=>'Se ha eliminado la suscripcion'],200);
            }else{
                return response()->json(['success' => true,'message'=>'El alumno no esta suscrito a esta clase'],200);
            }
        }catch(Exception $e){
            info($e->getMessage());
            return response()->json(['error'=>'Error al eliminar la inscripcion'],500);
        }
    }

    public function listLesson(Request $request){
        try{
            $data = Subscription::where('user_id',$request->user()->id)->get();
            return response()->json(['success'=>true,'data' => $data],200);
        }catch(Exception $e){
            return response()->json(['success'=>false,'Error al mostrar listado de clases'],500);
        }
    }
}
