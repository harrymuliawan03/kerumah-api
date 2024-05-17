<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\UnitCreateRequest;
use App\Http\Requests\UnitRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Http\Resources\UnitResource;
use App\ListIdleProperty;
use App\ListPayment;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function getUnits(UnitRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $units = Unit::where('type', $data['type'])->where('id_parent', $data['id_parent'])->get();

            if ($units->isEmpty()) {
                return ApiResponse::error('Unit not found', 404);
            }

            // return UnitResource::collection($units);
            return response()->json(ApiResponse::success('Units fetched successfully', UnitResource::collection($units)));
        } catch (\Exception $e) {
            // Handle the exception, log it, and return an appropriate response
            return ApiResponse::error('Internal Server Error' . $e->getMessage(), 500);
        }
    }

    public function getUnitById(Request $request)
    {
        // dd($request->id);   
        try {
            $unit = Unit::findOrFail($request->id);
            if (!$unit) {
                return ApiResponse::error('Unit not found', 404);
            }
            // return new UnitResource($unit);
            return response()->json(ApiResponse::success('Units fetched successfully', new UnitResource($unit)));
        } catch (\Exception $e) {
            return ApiResponse::error('Unit not found', 404);
        }
    }

    public function createUnit(UnitCreateRequest $request): JsonResponse
    {
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
                } else {
                    echo "No number found in the name attribute.";
                }
            } else {
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

    function calculateDueDate($start_date, $period)
    {
        // Convert start date to Carbon instance
        $start_date = Carbon::createFromFormat('Y-m-d', $start_date);

        // Add period based on payment frequency
        switch ($period) {
            case 'month':
                $start_date->addMonth();
                break;
            case 'year':
                $start_date->addYear();
                break;
                // You can add more cases for other period types if needed
            default:
                // Handle unsupported period types
                break;
        }

        // Return the calculated due date
        return $start_date->format('Y-m-d');
    }

    public function updateUnit(UnitUpdateRequest $request, $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = $request->validated();
            $unit = Unit::findOrFail($id);

            if (!$unit) {
                return ApiResponse::error('Unit not found', 404);
            }



            if (!empty($data['tanggal_mulai'])) {
                $date1 = Carbon::createFromFormat('Y-m-d', $unit->tanggal_mulai);
                $date2 = Carbon::createFromFormat('Y-m-d', $data['tanggal_mulai']);
                if ($date1->lt($date2)) {
                    $data['tanggal_jatuh_tempo'] = $this->calculateDueDate($data['tanggal_mulai'], $data['periode_pembayaran']);
                    ListPayment::create([
                        'unit_id' => $unit->id,
                        'user_id' => $user->id,
                        'payment_date' => $data['tanggal_mulai'],
                    ]);
                }
            }
            if (!empty($data['status']) && $data['status'] === 'empty') {
                $data['tanggal_jatuh_tempo'] = null;
                $data['tanggal_mulai_kontrakan'] = null;
            }


            if ($unit->status == 'filled') {
                if (isset($data['tanggal_mulai_kontrakan'])) {
                    unset($data['tanggal_mulai_kontrakan']); // Remove the field from data
                }
                if (!empty($data['status']) && $data['status'] == 'empty') {
                    ListIdleProperty::create([
                        'unit_id' => $unit->id,
                        'user_id' => $user->id,
                    ]);
                }
            }

            $unit->update($data);

            return response()->json(ApiResponse::success('Success update unit', new UnitResource($unit)), 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    public function bayarUnit($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $unit = Unit::findOrFail($id);

            if (!$unit) {
                return ApiResponse::error('Unit not found', 404);
            }

            $dueDate = Carbon::createFromFormat('Y-m-d', $unit->tanggal_jatuh_tempo);
            $currentDate = Carbon::now();
            $monthsDifference = $currentDate->diffInMonths($dueDate);
            $isLate = ($monthsDifference >= 1) ? 1 : 0;
            if ($currentDate->gte($dueDate)) {
                ListPayment::create([
                    'unit_id' => $unit->id,
                    'user_id' => $user->id,
                    'payment_date' => $currentDate,
                    'due_date' => $unit->tanggal_jatuh_tempo,
                    'isLate' => $isLate
                ]);
                $unit->tanggal_jatuh_tempo = $this->calculateDueDate($currentDate->format('Y-m-d'), $unit->periode_pembayaran);
                $unit->save();
            } else {
                return ApiResponse::error('Payment failed', 404);
            }

            return response()->json(ApiResponse::success('Payment Successfully !', new UnitResource($unit)), 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }


    public function deleteUnit(Request $request): JsonResponse
    {
        try {
            $unit = Unit::findOrFail($request->id);

            if (!$unit) {
                return ApiResponse::error('Unit not found', 400);
            }

            $unit->delete();

            return response()->json(ApiResponse::success('Unit deleted successfully'), 200);
        } catch (QueryException $e) {
            // Handle any database errors
            return ApiResponse::error('Failed to delete Perumahan', 500);
        }
    }
}
