<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Financial extends Model
{
    protected $casts = [
        'income' => 'collection',
        'consuption' => 'collection',
        'franch_percent' => 'collection',
        'discount' => 'collection',
        'raw_income'=>'collection',
        'franch_perc_from_part'=>'collection',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }



    public function add_income($income, $financial)
    {

        $income_collect = collect($income);
        $income_temp = collect($financial->income);
        $income_temp->push($income_collect);
        $financial->income = $income_temp;

        $total_inc = 0;
        foreach ($financial->income as $item) {
            $total_inc = $total_inc + $item['sum'];
        }
        $financial->total_income = $total_inc;
        $financial->total = $financial->total_income - ($financial->total_consuption + $financial->franch_total);
        $financial->save();

        return $financial;
    }

    public function remove_income($id, $financial)
    {
        foreach ($financial->income as $key => $value)
        {
            if($value['user_id'] == $id){
                $income_temp = collect($financial->income->forget($key));
                $financial->income = $income_temp;
            }
        }
        
        if(User::find($id)->franchise_id && $financial->franch_percent != null){
            foreach ($financial->franch_percent as $key => $value)
            {
                if($value['client_id'] == $id){
                    $franch_temp = collect($financial->franch_percent->forget($key));
                    $financial->franch_percent = $franch_temp;
                }
            }

        }

/////////////////////////////////////////////////////////////////////////////////////////
    if($financial->franch_perc_from_part != null){   
        foreach ($financial->franch_perc_from_part as $key_p => $value_p)
            {
                if($value_p['client_id'] == $id){
                    $franch_temp_part = collect($financial->franch_perc_from_part->forget($key_p));
                    $financial->franch_perc_from_part = $franch_temp_part;
                }
            }

    } 

        
        // if(User::find($id)->franchise_id){
            
        //     $partner = User::find(User::find($id)->franchise_id);
            
        //     if ($partner->role->id== 5 && isset($partner->franchise_id)) {

        //     }
        // }
/////////////////////////////////////////////////////////////////////////////////////////

        if($financial->discount){
             foreach ($financial->discount as $key => $value)
            {
                if($value['user_id'] == $id){
                    $discount_temp = collect($financial->discount->forget($key));
                    $financial->discount = $discount_temp;
                }
            }
        }
        $total_inc = 0;
        foreach ($financial->income as $item)
        {
            $total_inc = $total_inc + $item['sum'];
        }
        $financial->total_income = $total_inc;

        $total_inc = 0;
        if(User::find($id)->franchise_id && $financial->franch_percent != null){
            foreach($financial->franch_percent as $item)
            {
                $total_inc = $total_inc + $item['full_sum'];
            }
        }
        $financial->franch_total = $total_inc;


        $total_inc = 0;
        if($financial->discount){
        foreach ($financial->discount as $item) {
            $total_inc = $total_inc + $item['discount'];
            }
        }
        $financial->total_discount = $total_inc;

        $financial->total = $financial->total_income - ($financial->total_consuption + $financial->franch_total);

        $financial->save();
        return $financial;
    }

    public function add_franchise_percent($franchise_percent, $financial)
    {

        $percent_collect = collect($franchise_percent);
        $percent_temp = collect($financial->franch_percent);
        $percent_temp->push($percent_collect);
        $financial->franch_percent = $percent_temp;

        $total_inc = 0;
        foreach($financial->franch_percent as $item)
        {
            $total_inc = $total_inc + $item['full_sum'];
        }
        $financial->franch_total = $total_inc;
        $financial->total = $financial->total_income - ($financial->total_consuption + $financial->franch_total);
        $financial->save();
        return $financial;
    }

    public function add_franchise_percent_from_partner($percent_from_partner, $financial)
    {
        $percent_collect = collect($percent_from_partner);
        $percent_temp = collect($financial->franch_perc_from_part);
        $percent_temp->push($percent_collect);
        $financial->franch_perc_from_part = $percent_temp;

        $financial->save();

    }







    public function add_consuption($consuption, $financial)
    {
        $consuption_collect = collect($consuption);
        $consuption_temp = collect($financial->consuption);
        $consuption_temp->push($consuption_collect);
        $financial->consuption = $consuption_temp;

        $total_inc = 0;
        foreach ($financial->consuption as $item) {
            $total_inc = $total_inc + $item['sum'];
        }
        $financial->total_consuption = $total_inc;
        $financial->total = ($financial->total_income + $financial->total_rawIncome)-  ($financial->total_consuption + $financial->franch_total);
        $financial->save();

        return $financial;
    }

    public function delete_consuption($id, $financial)
    {
        $consuption = $financial->consuption;
        unset($consuption[$id]);
        $financial->consuption = $consuption;
        $financial->save();
        $total_inc = 0;

        foreach ($financial->consuption as $item)
        {
            $total_inc = $total_inc + $item['sum'];
        }

        $financial->total_consuption = $total_inc;
        $financial->total = ($financial->total_income + $financial->total_rawIncome)- ($financial->total_consuption + $financial->franch_total);
        $financial->save();

        return $financial;
    }

    public function edit_consuption($id, $financial, $name, $sum)
    {
        $consuptions = collect($financial->consuption);
        $consuption = collect($consuptions[$id]);
        $consuption['title'] = $name;
        $consuption['sum'] = $sum;
        $consuptions[$id] = $consuption;
        $financial->consuption = $consuptions;
        $total_inc = 0;

        foreach($financial->consuption as $item)
        {
            $total_inc = $total_inc + $item['sum'];
        }

        $financial->total_consuption = $total_inc;
        $financial->total = ($financial->total_income + $financial->total_rawIncome) - ($financial->total_consuption + $financial->franch_total);
        $financial->save();

        return $financial;
    }

    public function franchise_clients_from_event($financial, $franchise)
    {
        $client_count = 0;
        $client_ids_ = [];
        foreach ($financial->franch_percent as $item)
        {
            if($item['user_id'] == $franchise->id)
            {
                array_push($client_ids_, $item['client_id']);
                $client_count++;

            }
        }
        // dd($client_ids_);
        
        return $client_count;
    }


    public function franchise_percent_from_event($financial, $franchise)
    {
        $client_income = 0;

        foreach ($financial->franch_percent as $item)
        {
            if($item['user_id'] == $franchise->id)
            {
                $client_income = $client_income + $item['sum'];
            }
        }

        return $client_income;
    }

    public function add_discount($discount, $financial)
    {
        $discount_collect = collect($discount);
        $discount_temp = collect($financial->discount);
        $discount_temp->push($discount_collect);
        $financial->discount = $discount_temp;

        $total_inc = 0;
        foreach($financial->discount as $item) {
            $total_inc = $total_inc + $item['discount'];
        }
        $financial->total_discount = $total_inc;
        $financial->save();

        return $financial;
    }
}
