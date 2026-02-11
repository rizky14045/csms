<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CategoryAssesment;
use App\Http\Controllers\Controller;
use App\Http\Validation\CategoryAssesmentValidation;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Services\CategoryAssesment\CategoryAssesmentService;

class CategoryAssesmentController extends Controller
{
    protected $categoryAssesmentService;

    public function __construct(CategoryAssesmentService $categoryAssesmentService)
    {
        $this->categoryAssesmentService = $categoryAssesmentService;

        $this->middleware('can:view.category.assesment')->only(['index']);
        $this->middleware('can:create.category.assesment')->only(['create', 'store']);
        $this->middleware('can:edit.category.assesment')->only(['edit', 'update']);
        $this->middleware('can:delete.category.assesment')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->categoryAssesmentService->getAllCategoryAssesment(100, true, ['questions','questions.levels']);
        $data['categories'] = getPaginate($result);
        $data['request'] = request();
        return view('admin.category-assesment.index',$data);
    }

    public function create(){
        return view('admin.category-assesment.create');
    }

    public function store(Request $request){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), CategoryAssesmentValidation::rulesForCreate(), CategoryAssesmentValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $this->categoryAssesmentService->createCategoryAssesment($request->all());

            Alert::success('Tambah Berhasil', 'Kategori berhasil dibuat!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Kategori gagal dibuat!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function edit(CategoryAssesment $category_assesment ){
        $data['category'] = $category_assesment;
        return view('admin.category-assesment.edit',$data);
    }

    public function update(Request $request, CategoryAssesment $category_assesment){

        try {
           // Validation rules
            $validator = $this->validator($request->all(), CategoryAssesmentValidation::rulesForUpdate(), CategoryAssesmentValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->categoryAssesmentService->updateCategoryAssesment($category_assesment, $request->all());

            Alert::success('Update Berhasil', 'Kategori berhasil diubah!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Kategori gagal diubah!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function destroy(CategoryAssesment $category_assesment){
        try {
            $this->categoryAssesmentService->deleteCategoryAssesment($category_assesment);
            
            Alert::success('Delete Berhasil', 'Kategori berhasil dihapus!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Kategori gagal dihapus!');
            return redirect()->route('admin.category-assesment.index');
        }
    }
}
