<?php


namespace App\Modules\Core\Traits;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait Weightable
{
    public function updateWeights(string $table, array $idsAndWeights, $timestamp = true)
    {
        $cases = [];
        $ids = [];
        $params = [];

        foreach ($idsAndWeights as $value) {
            if (!array_key_exists('id', $value) || !array_key_exists('weight', $value)) {
                abort(422);
            }

            $id = (int)$value['id'];
            $cases[] = "WHEN {$id} then ?";
            $params[] = $value['weight'];
            $ids[] = $id;
        }

        $ids = implode(',', $ids);

        $cases = implode(' ', $cases);

        $params[] = Carbon::now();

        if($timestamp) {
            DB::update("UPDATE `{$table}` SET `weight` = CASE `id` {$cases} END, `updated_at` = ? WHERE `id` in ({$ids})", $params);
        } else {
            DB::update("UPDATE `{$table}` SET `weight` = CASE `id` {$cases} END WHERE `id` in ({$ids})", $params);
        }


        return response()->json();
    }
}
