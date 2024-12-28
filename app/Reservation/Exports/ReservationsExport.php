<?php

namespace App\Reservation\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;

class ReservationsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $reservationType;
    protected $schedule;

    public function __construct($startDate, $endDate, $reservationType, $schedule)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->reservationType = $reservationType;
        $this->schedule = $schedule;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('reservations as r')
            ->join('reservation_types as rt', 'r.reservation_type_id', '=', 'rt.id')
            ->join('schedules as s', 'r.schedule_id', '=', 's.id')
            ->where('r.is_deleted', '=', false)
            ->when($this->reservationType, function ($query) {
                $query->where('r.reservation_type_id', '=', $this->reservationType);
            })
            ->when($this->schedule, function ($query) {
                $query->where('r.schedule_id', '=', $this->schedule);
            })
            ->when(
                $this->startDate || $this->endDate,
                function (Builder $query): void  {
                    $query->where(function (Builder $query): void  {
                        if ($this->endDate) {
                            $query->whereDate('initial_reservation_date', '>=', $this->startDate);
                        }
                        if ($this->endDate) {
                            $query->whereDate('initial_reservation_date', '<=', $this->endDate);
                        }
                    });
            })
            ->select(
                'r.initial_reservation_date',
                'r.final_reservation_date',
                'rt.description as rt_description',
                's.description as s_description',
                'r.facilities_import',
                'r.people_extra_import',
                'r.hours_extra_import',
                'r.broken_things_import',
                'r.consumptions_import',
                'r.total',
                DB::raw("
                    CASE
                        WHEN r.status = 'IN_USE' THEN 'Cancelado'
                        WHEN r.status = 'COMPLETED' THEN 'Finalizado'
                        WHEN r.status = 'CANCELLED' THEN 'Cancelado'
                        ELSE 'Desconocido'
                    END as Estado
                ")
            )->get();
    }

    public function headings(): array
    {
        return [
            'Fecha de entrada',
            'Fecha de salida',
            'Tipo de reserva',
            'Turno',
            'Locker/Room (S/)',
            'Personas extras (S/)',
            'Horas extras (S/)',
            'Cosas rotas (S/)',
            'Consumo (S/)',
            'Total (S/)',
            'Estado',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal('center');
    }
}
