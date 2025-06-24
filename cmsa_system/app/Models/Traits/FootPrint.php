<?php
namespace App\Models\Traits;

use App\Libs\Common;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait FootPrint {
    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    */
    public function user_created_by(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function user_updated_by(): BelongsTo {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function user_deleted_by(): BelongsTo {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /*
    |--------------------------------------------------------------------------
    | created
    |--------------------------------------------------------------------------
    */

    /**
     * created_html
     */
    protected function createdHtml(): Attribute
    {
        return Attribute::make(
            get: fn () => Common::makeDateNameHtml($this->created_at_label, $this->created_by_label),
        );
    }
    /**
     * created_at_label
     */
    protected function createdAtLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => Common::getDateTimeLabel($this->created_at),
        );
    }
    /**
     * created_by_label
     */
    protected function createdByLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_by ? $this->user_created_by->name : Null,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | updated
    |--------------------------------------------------------------------------
    */

    /**
     * updated_html
     */
    protected function updatedHtml(): Attribute
    {
        return Attribute::make(
            get: fn () => Common::makeDateNameHtml($this->updated_at_label, $this->updated_by_label),
        );
    }
    /**
     * updated_at_label
     */
    protected function updatedAtLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => Common::getDateTimeLabel($this->updated_at),
        );
    }
    /**
     * updated_by_label
     */
    protected function updatedByLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->updated_by ? $this->user_updated_by->name : Null,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | deleted
    |--------------------------------------------------------------------------
    */

    /**
     * deleted_html
     */
    protected function deletedHtml(): Attribute
    {
        return Attribute::make(
            get: fn () => Common::makeDateNameHtml($this->deleted_at_label, $this->deleted_by_label),
        );
    }
    /**
     * deleted_at_label
     */
    protected function deletedAtLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => Common::getDateTimeLabel($this->deleted_at),
        );
    }
    /**
     * deleted_by_label
     */
    protected function deletedByLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->deleted_by ? $this->user_deleted_by->name : Null,
        );
    }
}
