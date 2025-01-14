<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Models\FormAttribute;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class FormAttributeController extends Controller
{
    public function index($monthlyId){
        
        $data['monthlyId'] = $monthlyId;

        $query = FormAttribute::join('attributes', 'attributes.id', 'form_attributes.attribute_id')
        ->select('form_attributes.*', 'attributes.name', 'attributes.status_ownership', 'attributes.unit', 'attributes.standard_contract')->where('monthly_report_id', $monthlyId);
    
        $data['attributes'] = (clone $query)->where('attributes.type_attribute', 'Attribute')->get();
        $data['administrations'] = (clone $query)->where('attributes.type_attribute', 'Administrasi')->get();
        $data['saranas'] = (clone $query)->where('attributes.type_attribute', 'Sarana')->get();

        return view('user.monthly-audit.form-attribute',$data);
    }

    public function saveAttribute(Request $request,$monthlyId){

        try {
            DB::beginTransaction();
            
            $attributes = $request->attribute;
      
            foreach($attributes as $attribute ){
                
                $value = [
                    'condition' => $request->input('condition_'.$attribute),
                    'status_item' =>  $request->input('status_item_'.$attribute),
                    'note' =>  $request->input('note_'.$attribute),
                ];

                FormAttribute::where('monthly_report_id',$monthlyId)->where('id',$attribute)->update($value);
            }
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data Attribute berhasil diupdate!');
            return redirect()->route('user.monthly-audit.form-attribute.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function saveAdministration(Request $request,$monthlyId){

        try {
            DB::beginTransaction();
            
            $administrations = $request->administration;
            foreach($administrations as $administration ){
                
                
                $form = FormAttribute::where('monthly_report_id',$monthlyId)->where('id',$administration)->first();
                $attachmentFile = $form->attachment_file;
                
                if($request->hasFile('attachment_file_'.$administration))
                {    
                    if ($form->attachment_file) {
                        unlink(public_path('uploads/attachment_file_form_attribute/'.$form->attachment_file));
                    }
                    $file= $request->file('attachment_file_'.$administration);
                    $file_name = 'form-attribute-' . time() .'.'. $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/attachment_file_form_attribute/'),$file_name);   
                    $attachmentFile = $file_name;
                }

                $value = [
                    'condition' => $request->input('condition_'.$administration),
                    'status_item' =>  $request->input('status_item_'.$administration),
                    'note' =>  $request->input('note_'.$administration),
                    'attachment_file' => $attachmentFile
                ];

                $form->update($value);

            }
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data Attribute berhasil diupdate!');
            return redirect()->route('user.monthly-audit.form-attribute.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function saveSarana(Request $request,$monthlyId){

        try {
            DB::beginTransaction();
            
            $saranas = $request->sarana;
      
            foreach($saranas as $sarana ){
                
                $value = [
                    'condition' => $request->input('condition_'.$sarana),
                    'status_item' =>  $request->input('status_item_'.$sarana),
                    'note' =>  $request->input('note_'.$sarana),
                ];

                FormAttribute::where('monthly_report_id',$monthlyId)->where('id',$sarana)->update($value);
            }
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data Attribute berhasil diupdate!');
            return redirect()->route('user.monthly-audit.form-attribute.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
}
