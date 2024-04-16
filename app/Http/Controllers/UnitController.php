<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Unit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UnitController extends Controller
{
    public function getUnitsPerumahan(UnitRequest $request): AnonymousResourceCollection {
        try {
            $data = $request->validated();
            $units = Unit::where('type', $data['type'])->where('id_parent', $data['id_parent'])->get();

            if ($units->isEmpty()) {
                return ApiResponse::error('Unit not found', 404);
            }
    
            return UnitResource::collection($units);
        } catch (\Exception $e) {
            // Handle the exception, log it, and return an appropriate response
            return ApiResponse::error('Internal Server Error' . $e->getMessage(), 500);
        }
    }

    public function getUnitPerumahanById(Request $request) {
        dd($request->id);   
        try {
            $unit = Unit::findOrFail($request->id);
            if ($unit) {
                return ApiResponse::error('Unit not found', 404);
            }

            return new UnitResource($unit);
        } catch (\Exception $e) {
            return ApiResponse::error('Unit not found', 404);
        }
    }

    // public function createPerumahan(PerumahanCreateRequest $request): JsonResponse {
    //     $data = $request->validated();

        
    //     if(Perumahan::where('kode_unit', $data['kode_unit'])->count() == 1) {
    //         return ApiResponse::error('kode unit already registered, try another one.', 400);
    //     }

    //     $perumahan = new Perumahan($data);
    //     $perumahan->save();

    //     return response()->json(ApiResponse::success('Success create perumahan', new PerumahanResource($perumahan)), 201);
    // }

    // public function updatePerumahan(PerumahanUpdateRequest $request): JsonResponse {
    //     $data = $request->validated();
    //     $perumahan = Perumahan::where('id', $data['id'])->first();
        
    //     if(!$perumahan) {
    //         return ApiResponse::error('Perumahan not found', 400);
    //     }

    //     $perumahan->update($data);

    //     return response()->json(ApiResponse::success('Success create perumahan', new PerumahanResource($perumahan)), 201);
    // }
    
    // public function deletePerumahan(PerumahanDeleteRequest $request): JsonResponse {
    //     try {
    //         $data = $request->validated();
    //         $perumahan = Perumahan::where('id', $data['id'])->first();
            
    //         if(!$perumahan) {
    //             return ApiResponse::error('Perumahan not found', 400);
    //         }

    //         $perumahan->delete();
    
    //         return response()->json(ApiResponse::success('Perumahan deleted successfully'), 200);
    //     } catch (QueryException $e) {
    //         // Handle any database errors
    //         return ApiResponse::error('Failed to delete Perumahan', 500);
    //     }
    // }
}
