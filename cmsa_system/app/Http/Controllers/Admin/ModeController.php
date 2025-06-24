<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\projectParams;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModeController extends Controller
{

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function modeChange(Request $request): JsonResponse
  {
    $mode_num = $request->get('modeNum');
    $pp = new projectParams();
    $pp->putModeNum($mode_num);

    return response()->json($mode_num);
  }
}
