<?php

namespace App\Http\Controllers\User;

use App\Models\Area;
use App\Models\Note;
use App\Models\Level;
use App\Models\SubArea;
use App\Models\Marturity;
use Illuminate\Http\Request;
use App\Models\MarturityArea;
use App\Models\MarturityNote;
use App\Models\MarturityLevel;
use App\Models\MarturitySubArea;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MarturityController extends Controller
{
    public function index(){
        $user = Auth::guard('web')->user();
        $data['marturities'] = Marturity::where('unit_id', $user->id)->latest()->paginate(10);
        return view('user.marturity.index',$data);
    }

    public function create(){
        return view('user.marturity.create');
    }

    public function store(Request $request){

        try {
            DB::beginTransaction();
            $user = Auth::guard('web')->user();
            
            $marturity = Marturity::create([
                'unit_id' => $user->id,
                'date' => $request->date,
                'triwulan' => $request->triwulan,
            ]);
            
            $areas = Area::where('type','marturity')->get();

            foreach ($areas as $area) {

                $marturityArea = MarturityArea::create([
                    'unit_id' => $user->id,
                    'marturity_id' => $marturity->id,
                    'name' => $area->name,
                    
                ]);
                $subAreas = SubArea::where('area_id', $area->id)->get();
                foreach ($subAreas as $subArea) {

                    $marturitySubArea = MarturitySubArea::create([
                        'unit_id' => $user->id,
                        'marturity_id' => $marturity->id,
                        'area_id' => $marturityArea->id,
                        'name' => $subArea->name,
                        'description' => $subArea->description,
                        'reference' => $subArea->reference,    
                    ]);

                    $levels = Level::where('sub_area_id', $subArea->id)->get();
                    foreach ($levels as $level){

                        $marturityLevel = MarturityLevel::create([
                            'unit_id' => $user->id,
                            'marturity_id' => $marturity->id,
                            'sub_area_id' => $marturitySubArea->id,
                            'level' => $level->level,
                            'description' => $level->description,       
                        ]);

                        $notes = Note::where('level_id', $level->id)->get();

                        foreach ($notes as $note){
                            $marturityNote = MarturityNote::create([
                                'unit_id' => $user->id,
                                'marturity_id' => $marturity->id,
                                'level_id' => $marturityLevel->id,
                                'note' => $note->note,
                            ]);
                        }
                    }
                }
            }
            DB::commit();
            Alert::success('Tambah Berhasil', 'Marturity berhasil ditambah!');
            return redirect()->route('user.marturity.index');
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    public function edit($marturityId){
        $user = Auth::guard('web')->user();
        $marturity = Marturity::where('unit_id',$user->id)->where('id',$marturityId)->first();
        if(!$marturity){
            abort(404);
        }
        $data['marturity'] = $marturity;
        return view('user.marturity.edit',$data);
    }
    public function update(Request $request ,$marturityId){

        try {
            DB::beginTransaction();
            Marturity::where('id',$marturityId)->update([
                'date' => $request->date,
                'triwulan' => $request->triwulan,
            ]);

            DB::commit();
            Alert::success('Update Berhasil', 'Marturity berhasil diubah!');
            return redirect()->route('user.marturity.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    public function show($marturityId){

        $user = Auth::guard('web')->user();
        $marturity = Marturity::where('unit_id',$user->id)->where('id', $marturityId)->first();
        if (!$marturity) {
            abort(404);
        }
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }
        $data['areas'] = MarturityArea::with('subAreas','subAreas.levels','subAreas.levels.notes')->where('marturity_id', $marturity->id)->get();
        return view('user.marturity.show',$data);
    }

    public function preview($marturityId){

        $marturity = Marturity::where('id', $marturityId)->first();
        if (!$marturity) {
            abort(404);
        }
        if($marturity->send_status != 1){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }
        $data['areas'] = MarturityArea::with('subAreas','subAreas.levels','subAreas.levels.notes')->where('marturity_id', $marturity->id)->get();
        return view('user.marturity.preview',$data);
    }

    public function send($marturityId){

        try {

            DB::beginTransaction();
            Marturity::where('id',$marturityId)->update([
                'send_status' => true,
                'send_date' => date('Y-m-d')
            ]);

            DB::commit();
            Alert::success('Berhasil Dikirim', 'Marturity berhasil dikirim!');
            return redirect()->route('user.marturity.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function destroy($marturityId){

        try {
            DB::beginTransaction();
            $user = Auth::guard('web')->user();
            
            $marturity = Marturity::where('unit_id',$user->id)->where('id',$marturityId)->first();

            if(!$marturity){
                abort(404);
            }

            MarturityArea::where('marturity_id',$marturity->id)->delete();
            MarturitySubArea::where('marturity_id',$marturity->id)->delete();
            MarturityLevel::where('marturity_id',$marturity->id)->delete();
            MarturityNote::where('marturity_id',$marturity->id)->delete();

            $marturity->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Marturity berhasil dihapus!');
            return redirect()->route('user.marturity.index');
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function uploadNote(Request $request,$marturityId,$areaId,$noteId){

        try {

            DB::beginTransaction();

            $request->validate([
                'attachment_file_'.$noteId => 'mimes:pdf'
            ],[
                'attachment_file_'.$noteId.'.mimes' => 'File harus beresktensi .pdf!',
            ]);

            $user = Auth::guard('web')->user();

            $note = MarturityNote::where('unit_id',$user->id)->where('marturity_id',$marturityId)->where('id',$noteId)->first();
            if(!$note){
                Alert::error('Update Gagal', 'Marturity gagal diupdate!');
                return redirect()->back();
            }

            $attachmentFile = null;
            
            if($request->hasFile('attachment_file_'.$noteId))
            {      
                $file= $request->file('attachment_file_'.$noteId);
                $file_name = 'marturity-file-' . time() .'.'. $file->getClientOriginalExtension();
                if ($note->attachment_file) {
                    unlink(public_path('uploads/attachment_file_marturity_file/'.$note->attachment_file));
                }
                $file->move(public_path('uploads/attachment_file_marturity_file/'),$file_name);   
                $attachmentFile = $file_name;
            }
            
            $note->attachment_file = $attachmentFile ?? $note->attachment_file;
            $note->save();
            DB::commit();
            
            Alert::success('Update Berhasil', 'Marturity berhasil diupdate!');
            return redirect()->route('user.marturity.show',['marturityId'=>$note->marturity_id,'areaId'=>$areaId]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
