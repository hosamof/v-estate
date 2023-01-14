<?php

namespace App\Repositories;

use App\Interfaces\AppointmentRepositoryInterface;
use App\Models\Appointment;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function getAllAppointments()
    {
        return Appointment::all();
    }

    public function getAppointmentById($appointmentId)
    {
        return Appointment::findOrFail($appointmentId);
    }

    public function deleteAppointment($appointmentId)
    {
        return Appointment::destroy($appointmentId);
    }

    public function createAppointment(array $appointmentDetails)
    {
        return Appointment::create($appointmentDetails);
    }

    public function updateAppointment($appointmentId, array $newDetails)
    {
        return Appointment::whereId($appointmentId)->update($newDetails);
    }
}
