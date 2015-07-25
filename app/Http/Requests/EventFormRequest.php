<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Event;

class EventFormRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules() {
        $event = Event::find($this->event_code);


        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'code' => 'required|unique:events,code',
                    'event_name' => 'required',
                    'event_place' => 'required',
                    'event_date' => 'required',
                    'cert_type' => 'required',
                    'theme' => 'required',
                ]; 
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    // 'code' => 'required|unique:events,code,'.$event->id,
                    'event_name' => 'required',
                    'event_place' => 'required',
                    'event_date' => 'required',
                    'cert_type' => 'required',
                    'theme' => 'required',
                ];
            }
            default:break;
        }

                   
    }

}