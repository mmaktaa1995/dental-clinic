<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentService
{
    /**
     * Get upcoming appointments for a user
     *
     * @param integer                    $userId
     * @param string|\Carbon\Carbon|null $startDate
     * @param string|\Carbon\Carbon|null $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingAppointments(int $userId, $startDate = null, $endDate = null): Collection
    {
        $startDate = $startDate ?: now();
        $query = Appointment::where('user_id', $userId)
            ->whereDate('date', '>=', $startDate)
            ->with('patient');

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        return $query->get();
    }

    public function createAppointment(array $data): Appointment
    {
        $this->validateNoAppointmentConflict($data['user_id'], $data['date']);

        return Appointment::create($data);
    }

    public function updateAppointment(Appointment $appointment, array $data): void
    {
        if (isset($data['date']) && $appointment->date?->ne($data['date'])) {
            $userId = $data['user_id'] ?? $appointment->user_id;
            $this->validateNoAppointmentConflict($userId, $data['date'], $appointment->id);
        }

        $appointment->update($data);
    }

    public function deleteAppointment(Appointment $appointment): void
    {
        $appointment->delete();
    }

    /**
     * Validate that there is no appointment conflict for the given user and date/time
     *
     * @param integer               $userId
     * @param string|\Carbon\Carbon $dateTime
     * @param integer|null          $excludeId
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validateNoAppointmentConflict(int $userId, $dateTime, ?int $excludeId = null): void
    {
        $dateTime = $dateTime instanceof \Carbon\Carbon
            ? $dateTime->format('Y-m-d H:i:s')
            : $dateTime;

        $query = Appointment::where('user_id', $userId)
            ->where('date', $dateTime);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        /**
         * @var \Illuminate\Database\Eloquent\Builder $query
         */
        if ($query->exists()) {
            throw new \InvalidArgumentException(__('app.appointments_conflict'));
        }
    }
}
