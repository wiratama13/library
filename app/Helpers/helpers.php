<?php

use App\Models\Transaction;
use Illuminate\Support\Carbon;

use function PHPUnit\Framework\isEmpty;

function convert_date($value)
{
    return date('d-F-Y', strtotime($value));
}

function convert_diff($value)
{
    return date('d-m-Y', strtotime($value));
}

function notification()
{
    date_default_timezone_set('Asia/Jakarta');
    $now = date("Y-m-d");
    
    $late_date = Transaction::with('member:id,name')
        ->where('date_end', '<', $now)
        ->where('status', '=', 1)->get();

    $output = "";

    if ($late_date) {
        foreach ($late_date as $date) {
            $output .= '<a class="dropdown-item" href="#">';
            $output .= "<td>" . $date['member']['name'] . "</td>";
            $date1 = date_create($now);
            $date2 = date_create($date->date_end);
            $diff = date_diff($date1, $date2);
            $output .= "<td>" . " terlambat selama  " . $diff->format("%a hari")   . "</td>";
            $output .= '</a>';
        }
    }
    elseif (isEmpty($late_date)) {
        $output .= '<a class="dropdown-item" href="#">';
        $output .= "<td>" . " Tidak ada data keterlambatan  " . "</td>";
        $output .= '</a>';
    }

    return $output;
}
