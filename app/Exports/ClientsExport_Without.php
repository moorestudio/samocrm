<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
class ClientsExport_Without implements FromCollection, WithHeadings,WithEvents,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::query()->where('franchise_id', '=', NULL)->whereIn('role_id',[2])->get(['amo_id','id','last_name','name','middle_name','email','city','country','company','job','work_type','contacts']);
    }
    public function headings(): array
    {
        return [
            'AMO ID',
            'SamoTickets ID',            
            'Фамилия',
            'Имя',
            'Отчество',
            'email',
            'Город',
            'Страна',
            'Компания',
            'Должность',
            'Деятельность',
            'Телефон',
        ];

    }
    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
        ];
    }
     public function registerEvents(): array
    {
        
        $styleArray = [
        'font' => [
        'bold' => true,
        ]
        ];
                  
        return [
            AfterSheet::class    => function(AfterSheet $event) use ($styleArray)
            {
                $cellRange = 'A1:K1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

            },
        ];
    }

}
