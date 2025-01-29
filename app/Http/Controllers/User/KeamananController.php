<?php

namespace App\Http\Controllers\User;

use App\Models\Kpi;
use App\Models\Area;
use App\Models\Note;
use App\Models\Level;
use App\Models\KpiArea;
use App\Models\KpiNote;
use App\Models\SubArea;
use App\Models\KpiLevel;
use App\Models\KpiSubArea;
use Illuminate\Http\Request;;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KeamananController extends Controller
{
    public function index(){
        $user = Auth::guard('web')->user();
        $data['kpis'] = Kpi::where('unit_id', $user->id)->latest()->paginate(10);
        return view('user.keamanan.index',$data);
    }

    public function create(){
        return view('user.keamanan.create');
    }

    public function store(Request $request){

        try {
            DB::beginTransaction();
            $user = Auth::guard('web')->user();
            
            $kpi = Kpi::create([
                'unit_id' => $user->id,
                'date' => $request->date,
                'triwulan' => $request->triwulan,
            ]);
            
            $areas = Area::where('type','kpi')->get();

            foreach ($areas as $area) {

                $kpiArea = KpiArea::create([
                    'unit_id' => $user->id,
                    'kpi_id' => $kpi->id,
                    'name' => $area->name,
                    
                ]);
                $subAreas = SubArea::where('area_id', $area->id)->get();
                foreach ($subAreas as $subArea) {

                    $kpiSubArea = KpiSubArea::create([
                        'unit_id' => $user->id,
                        'kpi_id' => $kpi->id,
                        'area_id' => $kpiArea->id,
                        'name' => $subArea->name,
                        'description' => $subArea->description,
                        'reference' => $subArea->reference,    
                    ]);

                    $levels = Level::where('sub_area_id', $subArea->id)->get();
                    foreach ($levels as $level){

                        $kpiLevel = KpiLevel::create([
                            'unit_id' => $user->id,
                            'kpi_id' => $kpi->id,
                            'sub_area_id' => $kpiSubArea->id,
                            'level' => $level->level,
                            'description' => $level->description,       
                        ]);

                        $notes = Note::where('level_id', $level->id)->get();

                        foreach ($notes as $note){
                            $kpiNote = KpiNote::create([
                                'unit_id' => $user->id,
                                'kpi_id' => $kpi->id,
                                'level_id' => $kpiLevel->id,
                                'note' => $note->note,
                            ]);
                        }
                    }
                }
            }
            DB::commit();
            Alert::success('Tambah Berhasil', 'KPI berhasil ditambah!');
            return redirect()->route('user.keamanan.index');
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    public function edit($kpiId){
        $user = Auth::guard('web')->user();
        $kpi = Kpi::where('unit_id',$user->id)->where('id',$kpiId)->first();
        if(!$kpi){
            abort(404);
        }
        $data['kpi'] = $kpi;
        return view('user.keamanan.edit',$data);
    }
    public function update(Request $request ,$kpiId){

        try {
            DB::beginTransaction();
            Kpi::where('id',$kpiId)->update([
                'date' => $request->date,
                'triwulan' => $request->triwulan,
            ]);

            DB::commit();
            Alert::success('Update Berhasil', 'KPI berhasil diubah!');
            return redirect()->route('user.keamanan.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    public function show($kpiId){

        $user = Auth::guard('web')->user();
        $kpi = Kpi::where('unit_id',$user->id)->where('id', $kpiId)->first();
        if (!$kpi) {
            abort(404);
        }
        if($kpi->send_status == true){
            Alert::warning('Warning', 'KPI sudah dikirm!');
            return redirect()->route('user.keamanan.index');
        }
        $data['areas'] = KpiArea::with('subAreas','subAreas.levels','subAreas.levels.notes')->where('kpi_id', $kpi->id)->get();
        return view('user.keamanan.show',$data);
    }

    public function preview($kpiId){

        $kpi = Kpi::where('id', $kpiId)->first();
        if (!$kpi) {
            abort(404);
        }
        if($kpi->send_status != 1){
            Alert::warning('Warning', 'KPI sudah dikirm!');
            return redirect()->route('user.keamanan.index');
        }
        $data['areas'] = KpiArea::with('subAreas','subAreas.levels','subAreas.levels.notes')->where('kpi_id', $kpi->id)->get();
        return view('user.keamanan.preview',$data);
    }

    public function send($kpiId){

        try {

            DB::beginTransaction();
            Kpi::where('id',$kpiId)->update([
                'send_status' => true,
                'send_date' => date('Y-m-d')
            ]);

            DB::commit();
            Alert::success('Berhasil Dikirim', 'KPI berhasil dikirim!');
            return redirect()->route('user.keamanan.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function destroy($kpiId){

        try {
            DB::beginTransaction();
            $user = Auth::guard('web')->user();
            
            $kpi = Kpi::where('unit_id',$user->id)->where('id',$kpiId)->first();

            if(!$kpi){
                abort(404);
            }

            KpiArea::where('kpi_id',$kpi->id)->delete();
            KpiSubArea::where('kpi_id',$kpi->id)->delete();
            KpiLevel::where('kpi_id',$kpi->id)->delete();
            KpiNote::where('kpi_id',$kpi->id)->delete();

            $kpi->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'KPI berhasil dihapus!');
            return redirect()->route('user.keamanan.index');
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function uploadNote(Request $request,$kpiId,$areaId,$noteId){

        try {

            DB::beginTransaction();

            $request->validate([
                'attachment_file_'.$noteId => 'mimes:pdf'
            ],[
                'attachment_file_'.$noteId.'.mimes' => 'File harus beresktensi .pdf!',
            ]);

            $user = Auth::guard('web')->user();

            $note = KpiNote::where('unit_id',$user->id)->where('kpi_id',$kpiId)->where('id',$noteId)->first();
            if(!$note){
                Alert::error('Update Gagal', 'KPI gagal diupdate!');
                return redirect()->back();
            }

            $attachmentFile = null;
            
            if($request->hasFile('attachment_file_'.$noteId))
            {      
                $file= $request->file('attachment_file_'.$noteId);
                $file_name = 'kpi-file-' . time() .'.'. $file->getClientOriginalExtension();
                if ($note->attachment_file) {
                    unlink(public_path('uploads/attachment_file_kpi_file/'.$note->attachment_file));
                }
                $file->move(public_path('uploads/attachment_file_kpi_file/'),$file_name);   
                $attachmentFile = $file_name;
            }
            
            $note->attachment_file = $attachmentFile ?? $note->attachment_file;
            $note->save();
            DB::commit();
            
            Alert::success('Update Berhasil', 'KPI berhasil diupdate!');
            return redirect()->route('user.keamanan.show',['keamananId'=>$note->kpi_id,'areaId'=>$areaId]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
