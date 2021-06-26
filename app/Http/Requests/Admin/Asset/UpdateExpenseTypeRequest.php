<?php


namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetsTypeExpense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateExpenseTypeRequest
 * @package App\Http\Requests\Admin\Asset
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class UpdateExpenseTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function appendBranchRule(): ?array
    {
        return authIsSuperAdmin() ? ['branch_id' => 'required|exists:branches,id'] : [];
    }

    public function rules(): array
    {
        $id = request()->segment(4);
        $getIgnoredId = AssetsTypeExpense::findOrFail($id);
        return $this->appendBranchRule() + [
                'name_ar' => ['required', 'string', 'max:50', Rule::unique('assets_types', 'name_ar')->ignore($getIgnoredId->id)],
                'name_en' => ['required', 'string', 'max:50', Rule::unique('assets_types', 'name_en')->ignore($getIgnoredId->id)],
            ];
    }
}
