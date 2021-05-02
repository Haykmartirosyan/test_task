<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request)
    {
        $user = auth()->user();
        $data = [];
        if ($user) {

            if($request->order) {
                $data = $user->data()->orderBy('value', $request->order)->get();

            }else{
                $data = $user->data;
            }
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createData(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();

            if ($user) {
                $user->data()->create($request->all());

                DB::commit();

                return response()->json([
                    'success' => 1
                ]);
            }
            DB::rollBack();
            return response()->json([
                'success' => 0
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            return response()->json([
                'success' => 0
            ]);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateData(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();

            if ($user) {
                $item = $user->data()->where('id', $id)->first();

                if ($item) {
                    $item->update($request->all());
                    DB::commit();

                    return response()->json([
                        'success' => 1
                    ]);
                }
            }
            DB::rollBack();
            return response()->json([
                'success' => 0
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            return response()->json([
                'success' => 0
            ]);
        }


    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteData($id)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();

            if ($user) {
                $item = $user->data()->where('id', $id)->first();

                if ($item) {
                    $item->delete();
                }
                DB::commit();

                return response()->json([
                    'success' => 1
                ]);
            }

            DB::rollBack();
            return response()->json([
                'success' => 0
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json([
                'success' => 0
            ]);
        }

    }
}
