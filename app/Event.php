<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'event_name', 'event_place', 'event_date', 'cert_type', 'theme', 'filename_prefix', 'user_id'];

    public function getTypeTitleAttribute() {
        switch ($this->cert_type) {
            case 'attendance':
                return 'Certificate of Attendance';
            case 'participation':
                return 'Certificate of Participation';
            default:
                return '';
        }
    }

    public function certTypes() {
        return [
            'attendance' => 'Certificate of Attendance',
            'participation' => 'Certificate of Participation'
        ];
    }
}
