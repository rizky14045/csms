<?php

namespace App\Http\Controllers\User;

use App\Models\Attribute;
use App\Models\FormAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Validation\AttributeValidation;
use App\Services\Attribute\AttributeService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;

        $this->middleware('can:view.attribute.unit')->only(['index']);
        $this->middleware('can:create.attribute.unit')->only(['create', 'store']);
        $this->middleware('can:edit.attribute.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.attribute.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }
    
    protected function alloc()
    {
        return app(\App\Services\Attribute\AttributeAllocationService::class);
    }

    protected function blockUl()
    {
        if (\App\Services\Unit\UnitScope::isUl(auth()->user())) {
            abort(403, 'Attribute UL hanya dapat dilihat, pengaturan dilakukan oleh unit induk.');
        }
    }

    /** Ambil attribute yang boleh diakses user; untuk induk yang punya UL, baris UL dialihkan ke baris induknya. */
    protected function resolveOwned(Attribute $attribute)
    {
        $user = auth()->user();

        if (\App\Services\Unit\UnitScope::isGroup($user)) {
            if ($attribute->parent_attribute_id) {
                $attribute = Attribute::findOrFail($attribute->parent_attribute_id);
            }
            if (!\App\Services\Unit\UnitScope::canAccess($attribute, $user)) {
                abort(404);
            }

            return $attribute;
        }

        $result = $this->attributeService->getAttributeById($attribute->id, $user->id);
        if (!getStatus($result)) {
            abort(404);
        }

        return $attribute;
    }

    protected function allocationView(array $data, ?Attribute $attribute = null)
    {
        $user = auth()->user();
        $data['canAllocate'] = $this->alloc()->canAllocate($user);
        $data['groupUnits'] = \App\Services\Unit\UnitScope::assignableUnits($user);
        $data['allocations'] = ($attribute && $data['canAllocate']) ? $this->alloc()->allocationsFor($attribute->load('children')) : [];
        $data['contractTotal'] = ($attribute && $data['canAllocate']) ? $this->alloc()->totalFor($attribute) : null;

        return $data;
    }

    public function index(){
        $user = auth()->user();
        $result = $this->attributeService->getAllAttribute(25, true, null, $user->id, $user->unit_id);
        $data['attributes'] = getPaginate($result);
        $data['request'] = request();
        $data['canAllocate'] = $this->alloc()->canAllocate($user);
        $data['isUl'] = \App\Services\Unit\UnitScope::isUl($user);
        $data['groupUnits'] = \App\Services\Unit\UnitScope::assignableUnits($user);
        $data['childrenByParent'] = $data['canAllocate']
            ? Attribute::whereIn('parent_attribute_id', collect($data['attributes']->items())->pluck('id'))->get()->groupBy('parent_attribute_id')
            : collect();

        return view('user.attribute.index', $data);
    }

    public function create(){
        $this->blockUl();
        return view('user.attribute.create', $this->allocationView([]));
    }

    public function store(Request $request){

        $this->blockUl();

        try {
            $validator = $this->validator($request->all(), AttributeValidation::rulesForCreateAttributeUnit(), AttributeValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = auth()->user();
            $monthlyId = $request->monthly_id;

            if (!$monthlyId && $this->alloc()->canAllocate($user)) {
                $error = $this->alloc()->validate($user, $request->standard_contract, $request->alloc);
                if ($error) {
                    return redirect()->back()->withErrors(['alloc' => $error])->withInput();
                }

                $this->alloc()->save($user, $request->all());

                Alert::success('Tambah Berhasil', 'Atribut berhasil dibuat dan dibagi ke unit induk / UL!');
                return redirect()->route('user.attribute.index');
            }

            if ($monthlyId) {
                DB::beginTransaction();

                $attribute = Attribute::create([
                    'user_id'          => $user->id,
                    'unit_id'          => $user->unit_id,
                    'name'             => $request->name,
                    'status_ownership' => $request->status_ownership,
                    'unit'             => $request->unit,
                    'standard_contract'=> $request->standard_contract,
                    'type_attribute'   => $request->type_attribute,
                    'created_by'       => $user->id,
                ]);

                FormAttribute::create([
                    'monthly_report_id' => $monthlyId,
                    'attribute_id'      => $attribute->id,
                ]);

                DB::commit();

                Alert::success('Tambah Berhasil', 'Atribut berhasil ditambahkan ke laporan bulanan!');
                return redirect()->route('user.monthly-audit.form-attribute.index', ['monthlyId' => $monthlyId]);
            }

            $request->merge([
                'user_id' => $user->id,
                'unit_id' => $user->unit_id,
            ]);
            $this->attributeService->createAttribute($request->all());

            Alert::success('Tambah Berhasil', 'Atribut berhasil dibuat!');
            return redirect()->route('user.attribute.index');

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Tambah Gagal', 'Atribut gagal dibuat!');
            return redirect()->back();
        }
    }

    public function edit(Attribute $attribute){
        $this->blockUl();
        $attribute = $this->resolveOwned($attribute);

        $data['attribute'] = $attribute;
        return view('user.attribute.edit', $this->allocationView($data, $attribute));
    }

    public function update(Request $request, Attribute $attribute){
        $this->blockUl();

        try {
            // Validation rules
            $validator = $this->validator($request->all(), AttributeValidation::rulesForUpdateAttributeUnit(), AttributeValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $attribute = $this->resolveOwned($attribute);

            if ($this->alloc()->canAllocate(auth()->user())) {
                $error = $this->alloc()->validate(auth()->user(), $request->standard_contract, $request->alloc);
                if ($error) {
                    return redirect()->back()->withErrors(['alloc' => $error])->withInput();
                }

                $this->alloc()->save(auth()->user(), $request->all(), $attribute);
            } else {
                $this->attributeService->updateAttribute($attribute, $request->all());
            }

            Alert::success('Update Berhasil', 'Atribut berhasil diubah!');
            return redirect()->route('user.attribute.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Atribut gagal diubah!');
            return redirect()->route('user.attribute.index');
        }
    }

    public function destroy(Attribute $attribute){
        $this->blockUl();

        try {
            $attribute = $this->resolveOwned($attribute);

            if ($this->alloc()->canAllocate(auth()->user())) {
                $this->alloc()->delete(auth()->user(), $attribute);
            } else {
                $this->attributeService->deleteAttribute($attribute);
            }

            Alert::success('Delete Berhasil', 'Atribut berhasil dihapus!');
            return redirect()->route('user.attribute.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Atribut gagal dihapus!');
            return redirect()->route('user.attribute.index');
        }
    }
}
