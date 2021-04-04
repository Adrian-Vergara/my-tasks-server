<?php


namespace App\Traits;


use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait GeneralLogic
{

    public function addTimestampsToUpdateData($array_data)
    {
        $timestamps = $this->getTimestamps();
        $data = array();
        foreach ($array_data as $item) {
            $data[$item] = $timestamps;
        }
        return $data;
    }

    public function getTimestamps()
    {
        return $timestamps = array(
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        );
    }

    public function calculateSkip($page, $limit)
    {
        return ($page - 1) * $limit;
    }

    private function deleteFile($storage, $file)
    {
        if (Storage::disk($storage)->exists($file)) {
            Storage::disk($storage)->delete($file);
        }
    }
}
