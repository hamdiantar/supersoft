<?php


namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetsItemExpense;
use App\Models\AssetsTypeExpense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateExpenseItemRequest
 * @package App\Http\Requests\Admin\Asset
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class UpdateExpenseItemRequest extends FormRequest
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
        $getIgnoredId = AssetsItemExpense::findOrFail($id);
        return $this->appendBranchRule() + [
                'item_ar' => ['required', 'string', 'max:50', Rule::unique('assets_item_expenses', 'item_ar')->ignore($getIgnoredId->id)],
                'item_en' => ['required', 'string', 'max:50', Rule::unique('assets_item_expenses', 'item_ar')->ignore($getIgnoredId->id)],
                'assets_type_expenses_id' => 'required|exists:assets_type_expenses,id',
            ];
    }

    public function attributes(): array
    {
        return [
            'item_ar' => __('Item in Arabic'),
            'item_en' => __('Item in English'),
            'branch_id' => __('Branch'),
            'assets_type_expenses_id' => __('Expense Type')
        ];
    }
}
