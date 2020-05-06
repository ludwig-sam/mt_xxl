<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FilesModel  extends Model {

    protected $table = 'files';


    protected $fillable = [
        'path', 'hkey', 'original_file'
    ];


}

