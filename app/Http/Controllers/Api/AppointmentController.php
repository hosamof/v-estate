<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Interfaces\AppointmentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AppointmentController extends Controller
{
    private AppointmentRepositoryInterface $appointmentRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->appointmentRepository->getAllAppointments()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAppointmentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $appointmentDetails = $request->validated();
        $appointmentDetails['user_id'] = auth('api')->id();

        return response()->json(
            [
                'data' => $this->appointmentRepository->createAppointment($appointmentDetails)
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'data' => $this->appointmentRepository->getAppointmentById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAppointmentRequest  $request
     * @param  int id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAppointmentRequest $request, int $id): JsonResponse
    {
        $appoitnmentDetails = $request->validated();
        $appointmentDetails['user_id'] = auth('api')->id();

        $updated = $this->appointmentRepository->updateAppointment($id, $appoitnmentDetails);
        return response()->json($updated == 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted =  $this->appointmentRepository->deleteAppointment($id); 

        return response()->json(($deleted == 1), Response::HTTP_ACCEPTED);
    }
}
