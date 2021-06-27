<?php


namespace App\Http\Requests\Admin\Asset;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AssetExpenseRequestUpdate
 * @package App\Http\Requests\Admin\Asset
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetExpenseRequestUpdate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                'number' => 'required',
                'dateTime' => 'required',
                'status' => 'required|in:accept,pending,cancel',
                'notes' => 'max:300',
                'total' => 'required',
                'items' => 'required|array',
                'items*price' => 'required',
                'items*asset_expense_item_id' => 'required|exists:assets_item_expenses,id',
            ];
    }
}
