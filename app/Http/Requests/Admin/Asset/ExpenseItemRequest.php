<?php


namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetsTypeExpense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ExpenseItemRequest
 * @package App\Http\Requests\Admin\Asset
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class ExpenseItemRequest extends FormRequest
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
        return $this->appendBranchRule() + [
                'item_ar' => 'required|string|max:50|unique:assets_item_expenses,item_ar',
                'item_en' => 'required|string|max:50|unique:assets_item_expenses,item_en',
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
