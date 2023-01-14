<?php

namespace App\Interfaces;

interface AppointmentRepositoryInterface 
{
    public function getAllAppointments();
    public function getAppointmentById($appointmentId);
    public function deleteAppointment($appointmentId);
    public function createAppointment(array $appointmentDetails);
    public function updateAppointment($appointmentId, array $newDetails);
}