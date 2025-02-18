<?php

namespace App\Http\Controllers\Admin;

use App\Models\Assesment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SignCategoryAssesment;

class AssesmentController extends Controller
{
    public function index(){
        $data['assesments'] = Assesment::where('send_status',3)->paginate(25);
        return view('admin.assesment.index',$data);
    }

    public function create(){
        return view('admin.assesment.create');
    }

    public function edit(){
        return view('admin.assesment.edit');
    }
    public function show($assesmentId){
        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesmentId)->get();
        return view('admin.assesment.show',$data);
    }
    public function report($assesmentId){

        $assesment = Assesment::find($assesmentId);
        $categories = SignCategoryAssesment::with('questions', 'questions.levels')
        ->where('assesment_id', $assesmentId)
        ->get()
        ->map(function ($category) {
            // Hitung rata-rata untuk setiap kategori berdasarkan level dalam pertanyaan
            $category->average = number_format($category->questions->avg('evaluation_unit'),2);
    
            return $category;
        });

        $chartData = [
            'labels' => $categories->pluck('category_name'), // Ambil category_name sebagai label
            'data' => $categories->pluck('average'), // Ambil nilai rata-rata untuk data
        ];
        $chartJson = json_encode($chartData);
        $data['assesment'] = $assesment;
        $data['categories'] = $categories;
        $data['chartJson'] = $chartJson;
        return view('admin.assesment.report',$data);
    }
}
