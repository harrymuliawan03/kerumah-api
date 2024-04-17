<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\UnitCreateRequest;
use App\Http\Requests\UnitRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Http\Resources\UnitResource;
use App\Unit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
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
        // dd($request->id);   
        try {
            $unit = Unit::findOrFail($request->id);
            if (!$unit) {
                return ApiResponse::error('Unit not found', 404);
            }

            return new UnitResource($unit);
        } catch (\Exception $e) {
            return ApiResponse::error('Unit not found', 404);
        }
    }

    public function createUnit(UnitCreateRequest $request): JsonResponse {
        try {
            $data = $request->validated();

            $units = Unit::where('id_parent', $data['id_parent'])->get();

            if ($units->isNotEmpty()) {
                $lastUnit = $units->last();
                // $data['name'] = $lastUnit->
                // Now you can use $lastUnit
                // Extracting last number from the name attribute
                preg_match('/(\d+)$/', $lastUnit['name'], $matches);

                if (isset($matches[1])) {
                    $lastNumber = $matches[1];
                    // dd($lastNumber + 1);
                    $data['name'] = $data['kode_unit'] . '-' . ($lastNumber + 1);
                    dd($data['name']);
                } 
                else {
                    echo "No number found in the name attribute.";
                }

            }
            else{

            }
    
            // if (Unit::where('name', $data['name'])->exists()) {
            //     return ApiResponse::error('kode unit already registered, try another one.', 400);
            // }
    
            $unit = new Unit($data);
            $unit->save();
    
            return response()->json(ApiResponse::success('Success create perumahan', new UnitResource($unit)), 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500); // Internal Server Error
        }
    }

    public function updateUnit(UnitUpdateRequest $request): JsonResponse {
        try {
            $data = $request->validated();
            $unit = Unit::findOrFail($data['id']);
            $unit->update($data);
    
            return response()->json(ApiResponse::success('Success create perumahan', new UnitResource($unit)), 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
    
    
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
