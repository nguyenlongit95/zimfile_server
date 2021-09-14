<?php

namespace App\Exports;

use App\Models\Jobs;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JobsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $jobs = Jobs::whereMonth('created_at', Carbon::now()->format('m'))
            ->with([
                'users', 'files', 'editor'
            ])->get();
        // compare and merge text for const number
        if ($jobs) {
            foreach ($jobs as $job) {
                $job->customer = $job->users->email;
                $job->status_txt = Jobs::compareStatus($job->status);
                $job->type_txt = Jobs::compareType($job->type);
                $job->editor = $job->editor->email;
            }
        }
        // Return data
        return $jobs;
    }

    /**
     * Returns headers for report
     * @return array
     */
    public function headings(): array {
        return [
            'ID',
            'Tên job',
            'Khách hàng',
            'Editor được giao',
            'Loại sản phẩm',
            'Tình trạng hoàn thiện',
            'Thời gian upload',
            'Thời gian confirm',
            'Thời gian hoàn thành',
        ];
    }

    public function map($job): array {
        return [
            $job->id,
            $job->file_jobs,
            $job->customer,
            $job->editor,
            $job->type_txt,
            $job->status_txt,
            $job->time_upload,
            $job->time_confirm,
            $job->time_done,
        ];
    }
}
