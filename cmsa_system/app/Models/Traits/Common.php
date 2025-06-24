<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait Common {
  /**
   * btn_list
   */
  protected function btnList(): Attribute
  {
    return Attribute::make(
      get: function () {
        $is_block = $this->is_block ?? false;
        $class = $is_block ? 'btn-block btn-sm' : 'btn-sm';
        return '<a href="'
          . route($this->route . 'index')
          . '" class="btn btn-default ' . $class . '">'
          . __('common.back_to_list')
          . '</a>';
      },
    );
  }
  /**
   * btn_show
   */
  protected function btnShow(): Attribute
  {
    return Attribute::make(
      get: function () {
        $is_block = $this->is_block ?? false;
        $class = $is_block ? 'btn-block btn-sm' : 'btn-sm';
        return '<a href="'
          . route($this->route . 'show', $this->id)
          . '" class="btn btn-primary ' . $class . '">'
          . __('common.show')
          . '</a>';
      },
    );
  }
  /**
   * btn_edit
   */
  protected function btnEdit(): Attribute
  {
    return Attribute::make(
      get: function () {
        $is_block = $this->is_block ?? false;
        $class = $is_block ? 'btn-block btn-sm' : 'btn-sm';
        return '<a href="'
          . route($this->route . 'edit', $this->id)
          . '" class="btn btn-info ' . $class . '">'
          . __('common.edit')
          . '</a>';
      },
    );
  }
  /**
   * btn_destroy
   */
  protected function btnDestroy(): Attribute
  {
    return Attribute::make(
      get: function () {
        $items = [
          'route' => $this->route . 'destroy',
          'id' => $this->id,
          'is_block' => $this->is_block ?? false,
        ];
        return view('admin.partials.form.btn_destroy', $items)->render();
      },
    );
  }
  /**
   * btn_restore
   */
  protected function btnRestore(): Attribute
  {
    return Attribute::make(
      get: function () {
        $is_block = $this->is_block ?? false;
        $class = $is_block ? 'btn-block btn-sm' : 'btn-sm';
        return '<a href="'
          . route($this->route . 'restore', $this->id)
          . '" class="btn btn-warning ' . $class . '">'
          . __('common.restore')
          . '</a>';
      },
    );
  }
  /**
   * invalid_html
   */
  protected function invalidHtml(): Attribute
  {
    return Attribute::make(
      get: function () {
        if($this->invalid) {
          $html = '<i class="far fa-square text-muted"></i>';
        } else {
          $html = '<i class="fas fa-check-square text-success"></i>';
        }
        return $html;
      },
    );
  }
  /**
   * memo_html
   */
  protected function memoHtml(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->memo ? nl2br($this->memo) : Null,
    );
  }
  /**
   * invalid_label
   */
  protected function invalidLabel(): Attribute
  {
    return Attribute::make(
      get: fn () => __('array.invalid.' . $this->invalid),
    );
  }
  /**
   * tr_class
   */
  protected function trClass(): Attribute
  {
    return Attribute::make(
      get: function () {
        $class = Null;
        if($this->invalid) $class = 'table-secondary';
        return $class;
      },
    );
  }
}
