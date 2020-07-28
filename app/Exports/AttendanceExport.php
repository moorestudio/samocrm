<?php
namespace App\Exports;

use App\User;
use App\Ticket;
use App\Event;
use App\Certificate;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
class AttendanceExport implements FromCollection,WithEvents,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $event_id;

    public function __construct($id){
      $this->event_id = $id;
    }
    public function collection()
    {
        $event = Event::find($this->event_id);
        $tickets = Ticket::where('event_id',$event->id)->get();
        $all_ticket = intval($event->client_count);
        $buy_ticket = count($tickets->whereIn('type',['done','buy']));
        $price = $tickets->whereIn('type',['done','buy'])->sum('ticket_price');
        $data = array(
          array('Проект','Дата начало','Дата конец','Общ.кол билетов','Продано билетов','Выручка','Не продано билетов','Спикер','Город', 'Адрес'),
          array($event->title,$event->date,$event->end_date,$all_ticket,$buy_ticket,$price,$all_ticket - $buy_ticket,$event->mentor, $event->city, $event->address),
          array('ФИО клиент','Продавец','Роль продавца','Участвовал'),
        );

        foreach($tickets as $ticket){
          $user = User::find($ticket->user_id);
          if($user){
            $franchise = User::find($user->franchise_id);
            $participate = "Нет";
            $franchise_role = "";
            if($franchise){
              $franchise = $franchise->name;
              $franchise_role = User::find($user->franchise_id)->role->display_name;
            }else{
              $franchise = "";
            }
            if($ticket->type == "done"){
              $participate = "Да";
            }
            array_push($data,array($user->name,$franchise,$franchise_role,$participate));
          }
        }

        return collect($data);
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
                $cellRange2 = 'A3:K3'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(14);
            },
        ];
    }

}
