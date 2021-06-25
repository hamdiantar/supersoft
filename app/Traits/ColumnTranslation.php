<?php

namespace App\Traits;

trait ColumnTranslation
{
    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['name_ar'] : $this['name_en'];
    }

    public function getItemAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['item_ar'] : $this['item_en'];
    }

    public function getTypeAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['type_ar'] : $this['type_en'];
    }

    public function getUnitAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['unit_ar'] : $this['unit_en'];
    }

    public function getSymbolAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['symbol_ar'] : $this['symbol_en'];
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['description_ar'] : $this['description_en'];
    }

    public function getRegressionAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['regression_ar'] : $this['regression_en'];
    }

    public function getPolicyAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['policy_ar'] : $this['policy_en'];
    }

    public function getQuestionAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['question_ar'] : $this['question_en'];
    }

    public function getSubjectAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['subject_ar'] : $this['subject_en'];
    }

    public function getContentAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['content_ar'] : $this['content_en'];
    }

    public function getLocationAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['location_ar'] : $this['location_en'];
    }

    public function getTextAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['text_ar'] : $this['text_en'];
    }
}
