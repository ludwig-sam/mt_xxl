<?php namespace App\Models;

use Libs\Time;
use Illuminate\Database\Eloquent\Model;

class JobSingleModel extends Model
{

    protected $table = 'job_single';


    protected $fillable = [
        "name", "status","success_at","created_at"
    ];

    protected $dates = [
        'created_at'
    ];

    public $timestamps = false;

    public function getDoingJob($name)
    {
        return $this->where("name", $name)->where("status", "doing")->first();
    }

    public function jobSuccess($name)
    {
        $row = $this->getDoingJob($name);

        if($row){
            return $row->update([
                "status"     => "success",
                "success_at" => Time::date()
            ]);
        }

        return true;
    }

}

