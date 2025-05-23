<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TutorBooking extends Model
{
    use HasFactory;

    public function booking_to_schedule()
    {
        return $this->belongsTo(TutorSchedule::class, 'schedule_id', 'id');
    }

    public function booking_to_student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function booking_to_tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id', 'id');
    }

    public static function purchase_schedule($identifier)
    {
        // get payment details
        $payment_details = session('payment_details');

        if (Session::has('keys')) {
            $transaction_keys           = session('keys');
            $transaction_keys           = json_encode($transaction_keys);
            $payment['payment_details'] = $transaction_keys;
            $remove_session_item[]      = 'keys';
        }
        if (Session::has('session_id')) {
            $transaction_keys           = session('session_id');
            $payment['payment_details'] = $transaction_keys;
            $remove_session_item[]      = 'session_id';
        }

        // generate invoice for payment
        $payment['invoice']        = '#' . Str::random(20);
        $payment['student_id']     = auth()->user()->id;
        $payment['schedule_id']    = $payment_details['items'][0]['id'];
        $payment['price']          = $payment_details['payable_amount'];
        $payment['tax']            = $payment_details['tax'];
        $payment['payment_method'] = $identifier;

        $schedule = TutorSchedule::find($payment_details['items'][0]['id']);
        if (get_user_info($schedule->tutor_id)->role == 'admin') {
            $payment['admin_revenue'] = $payment_details['payable_amount'];
        } else {
            $payment['instructor_revenue'] = $payment_details['payable_amount'] * (get_settings('instructor_revenue') / 100);
            $payment['admin_revenue']      = $payment_details['payable_amount'] - $payment['instructor_revenue'];
        }

        $payment['tutor_id'] = $schedule->tutor_id;
        $payment['start_time'] = $schedule->start_time;
        $payment['end_time'] = $schedule->end_time;

        // insert payment details
        TutorBooking::insert($payment);

        $remove_session_item[] = 'payment_details';
        Session::forget($remove_session_item);
        Session::flash('success', get_phrase('Tutor schedule bookied successfully.'));
        return redirect()->route('my_bookings', ['tab' => 'live-upcoming']);
    }
}
