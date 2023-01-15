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
        // get validated input data
        $appointmentDetails = $request->validated();
        $appointmentDetails['user_id'] = auth('api')->id();

        // check google api key
        $api_key = getenv('GOOGLE_API_KEY');
        if (!$api_key) {
            return response()->json(['error' => 'no Google API KEY'], Response::HTTP_BAD_REQUEST);
        }

        $postcode1 = $appointmentDetails['address'];
        $postcode2 = auth('api')->user()->address;
        $commute_mode = 'driving';
        $result = array();
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=$api_key&origins=$postcode1&destinations=$postcode2&mode=$commute_mode&language=en-EN&sensor=false";
        $data = @file_get_contents($url);
        $result = json_decode($data, true);
        // // DEBUGING
        // echo '<pre>';
        // print_r($result);
        // echo '</pre>';
        // // DEBUGING

        /** check calculated data  -  @todo validate received resonse */
        if (
            !empty($result['rows'])
            && $result['rows'][0]['elements'][0]['status'] != 'NOT_FOUND'
        ) {
            // get estimated time & appointment time in seconds
            $val = $result['rows'][0]['elements'][0]['duration']['value'];
            $dateSeconds = strtotime($appointmentDetails['date']);

            // agent will start before the estimated time
            $start = $dateSeconds - $val;

            // agent will return to office after 1 hour of appointment plus estimated time - 1hour = 3600 second
            $end = $dateSeconds + $val + 3600;

            // convert time to string to save it to database
            $appointmentDetails['est_start'] = date('Y-m-d H:i:s', $start);
            $appointmentDetails['est_end'] = date('Y-m-d H:i:s', $end);
        } else {
            /** no estimated value(postal code is not correct or any other reason) -> set start & end same as appointment date
             * @todo fix it with another way
             */
            $appointmentDetails['est_start'] = $appointmentDetails['date'];
            $appointmentDetails['est_end'] = $appointmentDetails['date'];
        }

        // // DEBUGING
        // echo '<pre>';
        // print_r($appointmentDetails);
        // echo '</pre>';
        // die('ss');
        // // DEBUGING

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
